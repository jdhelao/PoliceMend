import { Component, OnInit } from '@angular/core';

import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, Aplicacion, Perfil } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
/*import { NgxIndexedDBService } from 'ngx-indexed-db';*/
import { Observable, first, map, merge, startWith } from 'rxjs';
import { verifiedAccount } from '@igniteui/material-icons-extended';
import { IgxFilterOptions } from 'igniteui-angular';

@Component({
  selector: 'app-perfil-edit',
  templateUrl: './perfil-edit.component.html',
  styleUrls: ['./perfil-edit.component.scss']
})
export class PerfilEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: string;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsApps: Aplicacion[] = [];

  constructor(
    private http: HttpClient,
    /*private dbService: NgxIndexedDBService,*/
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private accountService: AccountService,
    private alertService: AlertService
  ) { this.accountService.set_showNavBar(false); this.user = this.accountService.userValue; }

  ngOnInit() {
    this.accountService.checkAppPermission(16);

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      pf_codigo: [null, (this.id ? Validators.required : null)],
      pf_nombre: [null, [Validators.min(4), Validators.max(50)]],

      searchApp: ['']
    });

    this.title = 'Crear Perfil';
    if (this.id) {
      // edit mode
      this.title = 'Editar Perfil';
      this.loading = true;

      this.http.get<any>(environment.urlAPI + 'perfiles/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: Perfil | any) => {
              if (data !== null && data !== undefined && data.pf_codigo !== null && data.pf_codigo !== undefined) {
                this.form.patchValue(data as Perfil);
                this.loadAppsList(data?.pf_codigo);
              }
              this.loading = false;
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/perfil');
              this.loading = false;
            }
          }
        );
    }
    else {
      this.loadAppsList();
    }
  }

  async loadAppsList($profile: number = 0) {
    this.loading = true;
    this.lsApps = [];
    this.http.get<any>(environment.urlAPI + 'aplicaciones/permisos-perfil/' + $profile).subscribe((data: AplicacionPerfil | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsApps = data;
      }
    });
    this.loading = false;
  }

  ngOnDestroy() {
    this.accountService.set_showNavBar(true);
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }

  clearSearchApps() {
    //this.form.value.searchApp = '';
    this.form.patchValue({ searchApp: '' });
  }

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
      (this.form.value.pf_codigo ?
        this.http.put<any>(environment.urlAPI + 'perfiles', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'perfiles', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: Perfil | any) => {
              if (data !== null && data !== undefined && data.pf_codigo !== null && data.pf_codigo !== undefined) {
                this.form.patchValue(data as Perfil);
                this.title = 'Editar Perfil';
                this.alertService.success('Perfil #' + data.pf_codigo + ' guardado', { autoClose: true, keepAfterRouteChange: true });
                this.saveApps();
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

  get filterApps() {
    const fo = new IgxFilterOptions();
    fo.key = ['ap_nombre'];
    fo.inputValue = this.form.value.searchApp;//this.searchApp;
    return fo;
  }

  saveApps() {
    if (this.lsApps.length) {
      if (navigator.onLine) {
        this.submitting = true;
        /**/
        console.log(this.lsApps.filter(object => object.us_edit !== undefined).map(object => {
          return {...object,pf_codigo: this.form.value.pf_codigo};
        }));

        this.http.patch<any>(environment.urlAPI + 'aplicaciones/permisos-perfil', (this.lsApps.filter(object => object.us_edit !== undefined).map(object => {
          return { ...object, pf_codigo: this.form.value.pf_codigo };
        })))
          .pipe(first())
          .subscribe(
            {
              next: (data: AplicacionPerfil[] | any) => {
                if (data !== null && data !== undefined && data.length) {
                  this.lsApps = data;
                  this.submitting = false;
                  //this.router.navigateByUrl('admin/perfil');
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

  disableApp(ap_codigo: number) {
    const index: number = -1;
    this.lsApps.forEach((ap, i) => {
      if (ap.ap_codigo == ap_codigo) {
        this.lsApps[i].ap_estado = !ap.ap_estado;
        this.lsApps[i].us_edit = this.user?.us_codigo; /*in order to send only edited elements*/
      }
    });
  }


}

export class AplicacionPerfil {
  pf_codigo?: number;
  ap_codigo?: number;
  ap_estado?: boolean;

  us_edit?: number;
}
