import { HttpClient } from '@angular/common/http';
import { AbstractType, Component, Inject, OnInit } from '@angular/core';
import { AbstractControl, AbstractFormGroupDirective, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { TipoContrato, User, Vehiculo, SolicitudVehicular } from '@app/_models';
import { Perfil } from '@app/_models/perfil';
import { Personal } from '@app/_models/personal';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { first } from 'rxjs';

@Component({
  selector: 'app-s-vehicular-edit',
  templateUrl: './s-vehicular-edit.component.html',
  styleUrls: ['./s-vehicular-edit.component.scss']
})
export class SVehicularEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: number;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsContractTypes: TipoContrato[] = [];
  lsVehicles: Vehiculo[] = [];

  ve_km_min: number = 0;

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
    this.accountService.checkAppPermission(14);
    this.loadContractsTypesList();
    this.loadVehiclesList();

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      sv_codigo: [null, (this.id ? Validators.required : null)],
      kt_codigo: [null, Validators.required],
      pe_codigo: [null, Validators.required],
      ve_codigo: [null, Validators.required],
      ve_km: [null, [Validators.required, Validators.min(1), Validators.max(320000)]],
      ve_combustible_nivel: [null, [Validators.required, Validators.min(1), Validators.max(99)]],
      sv_fecha_requerimiento: [null, [Validators.required, this.minDateValidator]],
      sv_descripcion: [null, Validators.required],

      us_codigo: [null],
    });

    this.title = 'Crear Solicitud';
    if (this.id) {
      // edit mode
      this.title = 'Editar Solicitud';
      this.loading = true;
      this.http.get<any>(environment.urlAPI + 'solicitud-vehicular/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: SolicitudVehicular | any) => {
              if (data !== null && data !== undefined && data.sv_codigo !== null && data.sv_codigo !== undefined) {
                this.form.patchValue(data as SolicitudVehicular);
                this.loading = false;
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/solicitud-vehicular');
            }
          }
        );
    }

  }

  async loadContractsTypesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'contrato/tipos').subscribe((data: TipoContrato | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsContractTypes = data;
        // remove option "Ninguno"
        if (this.lsContractTypes.length > 0) { this.lsContractTypes.splice(0, 1); }
      }
      this.loading = false;
    });
  }
  async loadVehiclesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'vehiculo/personas/' + ((this.user?.pe_codigo !== null && this.user?.pe_codigo !== undefined) ? Number(this.user?.pe_codigo) : 0)).subscribe((data: Vehiculo | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsVehicles = data;
      }
      this.loading = false;
    });
  }
  minDateValidator(control: AbstractControl): { [key: string]: any } | null {
    const today = new Date(); today.setHours(0, 0, 0, 0);
    const selectedDate = control.value;
    if (selectedDate && selectedDate < today) {
      return { minDate: true };
    }
    return null;
  }
  setMinKM(ve_codigo: number = this.form.value.ve_codigo) {
    console.log('setMinKM');
    const ve = this.lsVehicles.find((ve) => Number(ve.ve_codigo) === ve_codigo);
    if (ve !== undefined && ve.ve_km != undefined) {
      this.ve_km_min = ve.ve_km;
      this.form.controls["ve_km"].setValidators([Validators.required, Validators.min(ve.ve_km), Validators.max(320000)]);
      this.form.patchValue({ ve_km: this.form.value.ve_km }); // assign the same value to re-throw validation prompt
    }
  }
  setMaxFuel() {
    console.log('setMaxFuel');
    this.form.controls["ve_combustible_nivel"].setValidators([Validators.required, Validators.min(1), Validators.max(this.form.value.kt_codigo == 2 ? 50 : 99)]);
    this.form.patchValue({ ve_combustible_nivel: this.form.value.ve_combustible_nivel }); // assign the same value to re-throw validation prompt
  }

  ngOnDestroy() {
    this.accountService.set_showNavBar(true);
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }


  onSubmit() {
    // reset alerts on submit
    this.alertService.clear();

    this.submitted = true;

    this.form.patchValue(this.user as SolicitudVehicular);
    console.log(this.form.value);
    // stop here if form is invalid
    if (this.form.invalid) {
      return;
    }

    if (navigator.onLine) {
      this.submitting = true;
      (this.form.value.sv_codigo ?
        this.http.put<any>(environment.urlAPI + 'solicitud-vehiculos', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'solicitud-vehiculos', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: SolicitudVehicular | any) => {
              if (data !== null && data !== undefined && data.sv_codigo !== null && data.sv_codigo !== undefined) {
                this.form.patchValue(data as SolicitudVehicular);
                this.title = 'Editar Solicitud';
                this.alertService.success('Solicitud #' + data.sv_codigo + ' guardada', { autoClose: true, keepAfterRouteChange: true });
                this.submitting = false;
                //this.router.navigateByUrl('admin/usuario');
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
