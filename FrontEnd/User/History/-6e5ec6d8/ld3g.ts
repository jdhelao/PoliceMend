import { Component, OnInit } from '@angular/core';

import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, Catalogo } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { first } from 'rxjs';

@Component({
  selector: 'app-catalogo-edit',
  templateUrl: './catalogo-edit.component.html',
  styleUrls: ['./catalogo-edit.component.scss']
})
export class CatalogoEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  type?: string;
  id?: string;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsCatalogTypes: Catalogo[] = [];

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
    this.accountService.checkAppPermission(9);
    this.loadCatalogTypes();

    this.type = this.route.snapshot.params['type'];
    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      ca_codigo: [null, (this.id ? Validators.required : null)],
      ca_nombre: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(50), Validators.pattern("^[a-zA-Z0-9 ]+$")]],
      ca_estado: [null],
      ct_codigo: [null],
    });

    this.title = 'Crear Catálogo';
    if (this.id && this.type) {
      // edit mode
      this.title = 'Editar Catálogo';
      this.loading = true;

      this.http.get<any>(environment.urlAPI + 'catalogos/'+ this.type +'/objeto/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: Catalogo | any) => {
              if (data !== null && data !== undefined && data.cc_codigo !== null && data.cc_codigo !== undefined) {
                this.form.patchValue(data as Catalogo);
                this.loading = false;
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/circuito');
            }
          }
        );
    }

  }

  async loadCatalogTypes() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'catalogos').subscribe((data: Catalogo | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsCatalogTypes = data;
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

    // stop here if form is invalid
    if (this.form.invalid) {
      return;
    }

    if (navigator.onLine) {
      this.submitting = true;

      this.form.value.created_by = this.user?.us_codigo;
      this.form.value.updated_by = this.user?.us_codigo;
      (this.form.value.cc_codigo ?
        this.http.put<any>(environment.urlAPI + 'circuitos', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'circuitos', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: Catalogo | any) => {
              if (data !== null && data !== undefined && data.cc_codigo !== null && data.cc_codigo !== undefined) {
                this.form.patchValue(data as Catalogo);
                this.title = 'Editar Catalogo';
                this.alertService.success('Catalogo #' + data.cc_codigo + ' guardado', { autoClose: true, keepAfterRouteChange: true });
                this.submitting = false;
                //this.router.navigateByUrl('admin/circuito');
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
