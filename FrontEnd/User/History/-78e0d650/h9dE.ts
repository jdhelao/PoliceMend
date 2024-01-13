import { HttpClient } from '@angular/common/http';
import { AbstractType, Component, Inject, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, AbstractFormGroupDirective, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, SolicitudVehicular, OrdenMantenimiento, Entidad } from '@app/_models';
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

  lsEntities: Entidad[] = [];
  vehicleRequest: SolicitudVehicular | undefined;

  isCustodian: boolean = false;
  isTechnical: boolean = false;

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
      sv_codigo: [Validators.required], /* solicitud */
      en_codigo: [Validators.required], /* taller */
      pe_codigo: [null], /*tecnico*/

      om_documento: [null],
      om_total: [null],

      om_ingreso_aceptacion: [null],
      om_ingreso_condicion: [null],
      om_entrega_aceptacion: [null],
      om_entrega_condicion: [null],

      om_progreso: [null],

      /*auditoria*/
      us_codigo: [null],
    });

    this.title = 'Crear Orden';
    if (this.id) {
      // edit mode
      this.title = 'Editar Orden';
      this.loading = true;
      this.http.get<any>(environment.urlAPI + 'orden-mantenimiento/'+this.id+'/persona/' + this.user?.pe_codigo)
        .pipe(first())
        .subscribe(
          {
            next: (data: OrdenMantenimiento | any) => {
              if (data !== null && data !== undefined && data.om_codigo !== null && data.om_codigo !== undefined) {
                this.form.patchValue(data as OrdenMantenimiento);
                this.loading = false;
                this.loadVehicleRequest(data.sv_codigo);
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
              if (data !== null && data !== undefined && data.sv_codigo !== null && data.sv_codigo !== undefined) {
                this.form.patchValue(data as SolicitudVehicular);
                this.loading = false;
                this.vehicleRequest = data;
                this.isCustodian = (this.vehicleRequest?.pe_codigo == this.user?.pe_codigo);
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

  aprobar(value: boolean) {
    if (this.form.value.om_aprobacion === null || Boolean(this.form.value.om_aprobacion) != value) {
      this.form.patchValue({ om_aprobacion: value });
    }
    else if (Boolean(this.form.value.om_aprobacion) == value) {
      this.form.patchValue({ om_aprobacion: null });
    }
    console.log(this.form.value);
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
