import { HttpClient } from '@angular/common/http';
import { AbstractType, Component, Inject, OnInit } from '@angular/core';
import { AbstractControl, AbstractFormGroupDirective, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, OrdenAbastecimiento, Entidad } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { first } from 'rxjs';

@Component({
  selector: 'app-orden-abastecimiento-edit',
  templateUrl: './orden-abastecimiento-edit.component.html',
  styleUrls: ['./orden-abastecimiento-edit.component.scss']
})
export class OrdenAbastecimientoEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: number;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsEntities: Entidad[] = [];

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
    this.loadEntitiesList();

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      oa_codigo: [Validators.required],
      en_codigo: [null, Validators.required],
      oa_total: [null, Validators.required],
      oa_galones: [null, Validators.required],
      oa_combustible_nivel: [null, [Validators.required, Validators.min(1), Validators.max(100)]],

      oa_documento: [null, Validators.required],
      oa_archivo_datos: [null],
      oa_archivo_tipo: [null],

      ve_codigo: [null],
      ve_combustible_nivel: [null],
      us_codigo: [null],
    });

    this.title = 'Crear Orden';
    if (this.id) {
      // edit mode
      this.title = 'Editar Orden';
      this.loading = true;
      this.http.get<any>(environment.urlAPI + 'orden-abastecimientos/'+this.id+'/persona/' + this.user?.pe_codigo)
        .pipe(first())
        .subscribe(
          {
            next: (data: OrdenAbastecimiento | any) => {
              if (data !== null && data !== undefined && data.oa_codigo !== null && data.oa_codigo !== undefined) {
                this.form.patchValue(data as OrdenAbastecimiento);
                this.loading = false;
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/orden-abastecimiento');
            }
          }
        );
    }

  }

  async loadEntitiesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'entidades/por-tipo/2').subscribe((data: Entidad | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsEntities = data;
      }
      this.loading = false;
    });
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

    this.form.patchValue(this.user as OrdenAbastecimiento);
    console.log(this.form.value);
    // stop here if form is invalid
    if (this.form.invalid) {
      return;
    }

    if (navigator.onLine) {
      this.submitting = true;
      (this.form.value.oa_codigo ?
        this.http.put<any>(environment.urlAPI + 'orden-abastecimientos', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'orden-abastecimientos', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: OrdenAbastecimiento | any) => {
              if (data !== null && data !== undefined && data.oa_codigo !== null && data.oa_codigo !== undefined) {
                this.form.patchValue(data as OrdenAbastecimiento);
                this.title = 'Editar Orden';
                this.alertService.success('Orden #' + data.oa_codigo + ' guardada', { autoClose: true, keepAfterRouteChange: true });
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
