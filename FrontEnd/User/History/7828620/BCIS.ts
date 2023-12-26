import { HttpClient } from '@angular/common/http';
import { AbstractType, Component, Inject, OnInit } from '@angular/core';
import { AbstractControl, AbstractFormGroupDirective, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { TipoContrato, User, Vehiculo, SolicitudVehicular } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { first } from 'rxjs';
/*For chart*/
import { AfterViewInit, ViewChild, ChangeDetectionStrategy, ChangeDetectorRef } from '@angular/core';
import { IgxLegendComponent, IgxCategoryChartComponent } from 'igniteui-angular-charts';

@Component({
  selector: 'app-s-vehicular-aprobar-edit',
  templateUrl: './s-vehicular-aprobar-edit.component.html',
  styleUrls: ['./s-vehicular-aprobar-edit.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class SVehicularAprobarEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: number;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  vehicleRequest: SolicitudVehicular | undefined;

  constructor(
    private http: HttpClient,
    private dbService: NgxIndexedDBService,
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private accountService: AccountService,
    private alertService: AlertService,
    private _detector: ChangeDetectorRef
  ) { this.accountService.set_showNavBar(false); this.user = this.accountService.userValue; }

  @ViewChild("legend", { static: true } )
  private legend!: IgxLegendComponent
  @ViewChild("chart", { static: true } )
  private chart!: IgxCategoryChartComponent

  private _countryRenewableElectricity: CountryRenewableElectricity | null= null;
  public get countryRenewableElectricity(): CountryRenewableElectricity {
      if (this._countryRenewableElectricity == null)
      {
          this._countryRenewableElectricity = new CountryRenewableElectricity(); 
      }
      return this._countryRenewableElectricity;
  }

  ngOnInit() {
    this.accountService.checkAppPermission(15);

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      sv_codigo: [Validators.required],
      sv_aprobacion: [null, Validators.required],
      sv_observacion: [null, Validators.required],
      us_codigo: [null],
    });

    this.title = 'Crear Solicitud';
    if (this.id) {
      // edit mode
      this.title = 'Editar Solicitud';
      this.loading = true;
      this.http.get<any>(environment.urlAPI + 'solicitud-vehiculos/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: SolicitudVehicular | any) => {
              if (data !== null && data !== undefined && data.sv_codigo !== null && data.sv_codigo !== undefined) {
                this.form.patchValue(data as SolicitudVehicular);
                this.loading = false;
                this.vehicleRequest = data;
                if (data.sv_aprobacion !== undefined && data.sv_aprobacion !== null) {
                  this.title = 'Solicitud ' + (Boolean(data.sv_aprobacion)?'Aprobada':'Negada');
                  this.form.disable();
                }
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/aprobar-solicitud-vehicular');
            }
          }
        );
    }
    else {
      this.router.navigateByUrl('admin/aprobar-solicitud-vehicular');
    }

  }

  ngOnDestroy() {
    this.accountService.set_showNavBar(true);
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }

  aprobar(value: boolean) {
    if (this.form.value.sv_aprobacion === null || Boolean(this.form.value.sv_aprobacion) != value) {
      this.form.patchValue({sv_aprobacion: value});
    }
    else if (Boolean(this.form.value.sv_aprobacion) == value) {
      this.form.patchValue({sv_aprobacion: null});
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




export class CountryRenewableElectricityItem {
  public constructor(init: Partial<CountryRenewableElectricityItem>) {
      Object.assign(this, init);
  }

  public year!: string;
  public europe!: number;
  public china!: number;
  public america!: number;

}
export class CountryRenewableElectricity extends Array<CountryRenewableElectricityItem> {
  public constructor() {
      super();
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2009`,
          europe: 34,
          china: 21,
          america: 19
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2010`,
          europe: 43,
          china: 26,
          america: 24
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2011`,
          europe: 66,
          china: 29,
          america: 28
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2012`,
          europe: 69,
          china: 32,
          america: 26
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2013`,
          europe: 58,
          china: 47,
          america: 38
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2014`,
          europe: 40,
          china: 46,
          america: 31
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2015`,
          europe: 78,
          china: 50,
          america: 19
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2016`,
          europe: 13,
          china: 90,
          america: 52
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2017`,
          europe: 78,
          china: 132,
          america: 50
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2018`,
          europe: 40,
          china: 134,
          america: 34
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2018`,
          europe: 40,
          china: 134,
          america: 34
      }));
      this.push(new CountryRenewableElectricityItem(
      {
          year: `2019`,
          europe: 80,
          china: 96,
          america: 38
      }));
  }
}