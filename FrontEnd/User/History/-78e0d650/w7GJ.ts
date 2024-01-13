import { HttpClient } from '@angular/common/http';
import { AbstractType, Component, Inject, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, AbstractFormGroupDirective, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, SolicitudVehicular, OrdenMantenimiento, OrdenMantenimientoActividad } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { first } from 'rxjs';

@Component({
  selector: 'app-orden-mantenimiento-edit',
  templateUrl: './orden-mantenimiento-edit.component.html',
  styleUrls: ['./orden-mantenimiento-edit.component.scss']
})
export class OrdenMantenimientoEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: number;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsActivities: OrdenMantenimientoActividad[] = [];
  vehicleRequest: SolicitudVehicular | undefined;

  isCustodian: boolean = false;

  constructor(
    private http: HttpClient,
    private dbService: NgxIndexedDBService,
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private accountService: AccountService,
    private alertService: AlertService
  ) { this.accountService.set_showNavBar(false); this.user = this.accountService.userValue; }

  ngOnInit() {
    this.accountService.checkAppPermission(10);

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      om_codigo: [Validators.required],
      sv_codigo: [Validators.required] /* solicitud */,
      en_codigo: [Validators.required] /* taller */,
      pe_codigo: [null] /*tecnico*/,

      om_documento: [null],
      om_total: [null],

      om_ingreso_condicion: [null],
      om_ingreso_aceptacion: [null],
      om_entrega_condicion: [null],
      om_entrega_aceptacion: [null],

      om_progreso: [null],

      /*auditoria*/
      us_codigo: [null],
    });

    this.title = 'Crear Orden';
    if (this.id) {
      // edit mode
      this.title = 'Editar Orden';
      this.loading = true;
      this.http.get<any>(environment.urlAPI + 'orden-mantenimiento/' + this.id + '/persona/' + this.user?.pe_codigo)
        .pipe(first())
        .subscribe(
          {
            next: (data: OrdenMantenimiento | any) => {
              if (data !== null && data !== undefined && data.om_codigo !== null && data.om_codigo !== undefined) {
                this.form.patchValue(data as OrdenMantenimiento);
                this.loading = false;
                this.loadVehicleRequest(data.sv_codigo);
                this.loadActivities(data.om_codigo);
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/orden-mantenimiento');
            }
          }
        );
    }

  }

  ngOnDestroy() {
    this.accountService.set_showNavBar(true);
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }

  loadVehicleRequest($id: number) {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'solicitud-vehiculos/' + $id)
      .pipe(first())
      .subscribe(
        {
          next: (data: SolicitudVehicular | any) => {
            this.loading = false;
            if (data !== null && data !== undefined && data.sv_codigo !== null && data.sv_codigo !== undefined) {
              this.form.patchValue(data as SolicitudVehicular);
              this.vehicleRequest = data;
              this.isCustodian = this.vehicleRequest?.pe_codigo == this.user?.pe_codigo; // owner of request 
              this.disableControls();
            }
          },
          error: (error) => {
            this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
            this.loading = false;
            this.router.navigateByUrl('admin/orden-mantenimiento');
          }
        }
      );
  }

  disableControls() {
    /* Constancia */
    if (!this.isCustodian || this.form.value.om_entrega_condicion.length || this.form.value.om_ingreso_condicion.length == 0) {
      this.form.get('om_ingreso_aceptacion')?.disable();
    }
    if (!this.isCustodian || this.form.value.om_documento.length || this.form.value.om_entrega_condicion.length == 0) {
      this.form.get('om_entrega_aceptacion')?.disable();
    }
  }

  loadActivities($id: number) {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'orden-mantenimiento/' + $id + '/actividades')
      .pipe(first())
      .subscribe(
        {
          next: (data: OrdenMantenimientoActividad | any) => {
            this.loading = false;
            if (data !== null && data !== undefined) {
              this.lsActivities = data;
            }
          },
          error: (error) => {
            this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
            this.loading = false;
            this.router.navigateByUrl('admin/orden-mantenimiento');
          }
        }
      );
  }

  editActivity($index: number, $type: number, $detail: any) {
    if ($type == 1) {
      this.lsActivities[$index].oma_descripcion = $detail.value;
    }
    else if ($type == 2) {
      this.lsActivities[$index].oma_costo = $detail.value;
    }
    else if ($type == 3) {
      this.lsActivities[$index].oma_estado = !this.lsActivities[$index].oma_estado;
    }
    this.lsActivities[$index].us_codigo = this.user?.us_codigo;
    this.sumTotal();
  }

  addActivityRow() {
    if (this.checkActivities()) {
      let a: OrdenMantenimientoActividad = { oma_estado: true };
      this.lsActivities[this.lsActivities.length] = a;
    }
    //console.log(this.lsContacts);
    //console.log(merge(this.lsContacts, {us_codigo: this.user?.us_codigo}));
  }

  private checkActivities(): boolean {
    this.alertService.clear();
    this.sumTotal();
    for (const oma of this.lsActivities) {
      if (oma.oma_descripcion === undefined || oma.oma_descripcion.length < 1) {
        this.alertService.warn('Llene su descripción', { autoClose: true });
        return false;
      }
    }
    return true;
  }

  sumTotal() {
    let total: number = 0;
    console.log('TOTAL');
    console.log(this.lsActivities);
    for (const oma of this.lsActivities) {
      if (oma.oma_estado === true) {
        total += Number(oma.oma_costo);
      }
    }

    this.form.patchValue({ om_total: total.toFixed(2) });
    if (this.form.value.om_documento.length > 0 && this.form.value.om_progreso < 100) {
      this.form.patchValue({ om_progreso: 100 });
    }
  }

  saveActivities() {
    let _editedActivities = (this.lsActivities.filter(object => object.us_codigo !== undefined).map(object => {
      return { ...object, om_codigo: this.form.value.om_codigo };
    }));
    
    if (_editedActivities.length && this.checkActivities()) {
      if (navigator.onLine) {
        this.submitting = true;
        this.http.patch<any>(environment.urlAPI + 'orden-mantenimiento/actividades', _editedActivities)
          .pipe(first())
          .subscribe(
            {
              next: (data: OrdenMantenimientoActividad[] | any) => {
                if (data !== null && data !== undefined && data.length) {
                  this.lsActivities = data;
                  this.submitting = false;
                  //this.router.navigateByUrl('admin/entidad');
                }
              },
              error: (error) => {
                this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
                this.submitting = false;
              }
            }
          );
      } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
    }
  }

  onSubmit() {
    // reset alerts on submit
    this.alertService.clear();

    this.submitted = true;

    this.form.patchValue(this.user as SolicitudVehicular);
    console.log(this.form.value);
    // stop here if form is invalid
    if (this.form.invalid) {
      console.log('form.invalid');
      return;
    }

    if (navigator.onLine) {
      this.submitting = true;
      (this.form.value.om_codigo ?
        this.http.put<any>(environment.urlAPI + 'orden-mantenimiento', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'orden-mantenimiento', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: OrdenMantenimiento | any) => {
              if (data !== null && data !== undefined && data.om_codigo !== null && data.om_codigo !== undefined) {
                this.form.patchValue(data as OrdenMantenimiento);
                this.title = 'Editar Orden';
                this.alertService.success('Orden #' + data.om_codigo + ' guardada', { autoClose: true, keepAfterRouteChange: true });
                this.saveActivities();
                this.submitting = false;
                //this.router.navigateByUrl('admin/orden-mantenimiento');
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.submitting = false;
            }
          }
        );
    } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
  }
}
