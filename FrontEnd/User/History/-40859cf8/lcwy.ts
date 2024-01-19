import { HttpClient } from '@angular/common/http';
import { AbstractType, Component, Inject, OnInit } from '@angular/core';
import { AbstractControl, AbstractFormGroupDirective, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, OrdenMovilizacion, Entidad } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { first } from 'rxjs';

@Component({
  selector: 'app-orden-movilizacion-edit',
  templateUrl: './orden-movilizacion-edit.component.html',
  styleUrls: ['./orden-movilizacion-edit.component.scss']
})
export class OrdenMovilizacionEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: number;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsEntities: Entidad[] = [];

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
    this.accountService.checkAppPermission(19);

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      od_codigo: [Validators.required],
      od_ocupantes: [null, Validators.required],
      us_codigo: [null],
    });

    this.title = 'Crear Orden';
    if (this.id) {
      // edit mode
      this.title = 'Editar Orden';
      this.loading = true;
      this.http.get<any>(environment.urlAPI + 'orden-movilizaciones/'+this.id+'/persona/' + this.user?.pe_codigo)
        .pipe(first())
        .subscribe(
          {
            next: (data: OrdenMovilizacion | any) => {
              if (data !== null && data !== undefined && data.od_codigo !== null && data.od_codigo !== undefined) {
                this.form.patchValue(data as OrdenMovilizacion);
                this.loading = false;
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/orden-movilizacion');
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


  onSubmit() {
    // reset alerts on submit
    this.alertService.clear();

    this.submitted = true;

    this.form.patchValue(this.user as OrdenMovilizacion);
    console.log(this.form.value);
    // stop here if form is invalid
    if (this.form.invalid) {
      return;
    }

    if (navigator.onLine) {
      this.submitting = true;
      (this.form.value.od_codigo ?
        this.http.put<any>(environment.urlAPI + 'orden-movilizaciones', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'orden-movilizaciones', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: OrdenMovilizacion | any) => {
              if (data !== null && data !== undefined && data.od_codigo !== null && data.od_codigo !== undefined) {
                this.form.patchValue(data as OrdenMovilizacion);
                this.title = 'Editar Orden';
                this.alertService.success('Orden #' + data.od_codigo + ' guardada', { autoClose: true, keepAfterRouteChange: true });
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
