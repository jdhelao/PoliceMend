import { Component, OnInit } from '@angular/core';

import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, Personal, Contrato, TipoContrato } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
/*import { NgxIndexedDBService } from 'ngx-indexed-db';*/
import { Observable, first, map, merge, startWith } from 'rxjs';
import { verifiedAccount } from '@igniteui/material-icons-extended';
import { IgxFilterOptions, IgxIconComponent } from 'igniteui-angular';
import { NgxIndexedDBService } from 'ngx-indexed-db';

@Component({
  selector: 'app-contrato-edit',
  templateUrl: './contrato-edit.component.html',
  styleUrls: ['./contrato-edit.component.scss'],
})
export class ContratoEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: string;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsContractTypes: TipoContrato[] = [];
  lsPeople: Personal[] = [];
  lsEntities: ContratoEntidad[] = [];

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
    this.accountService.checkAppPermission(7);
    this.loadContractsTypesList();
    this.loadPeopleList();

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      ko_codigo:  [null, (this.id ? Validators.required : null)],
      ko_documento: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(50), Validators.pattern("^[a-zA-Z0-9 -]+$")]],
      kt_codigo: [null, Validators.required],
      ko_inicio: [null],
      ko_fin: [null],
      ko_monto: [null, [Validators.min(0), Validators.max(9999999)]],
      ko_compadecientes: [null, Validators.required],
      ko_clausulas: [null, Validators.required],
      kt_contratante: [null, Validators.required],
      kt_contratista: [null, Validators.required],

      searchEntity: ['']
    });

    this.title = 'Crear Contrato';
    if (this.id) {
      // edit mode
      this.title = 'Editar Contrato';
      this.loading = true;

      this.http.get<any>(environment.urlAPI + 'contratos/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: Contrato | any) => {
              if (data !== null && data !== undefined && data.ko_codigo !== null && data.ko_codigo !== undefined) {
                this.form.patchValue(data as Contrato);
                this.loadEntitiesList(data?.ko_codigo);
              }
              this.loading = false;
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/contrato');
            }
          }
        );
    }

  }

  async loadEntitiesList($contract: number = this.form.value.ko_codigo) {
    this.loading = true;
    this.lsEntities = [];
    this.http.get<any>(environment.urlAPI + 'entidades/contrato/' + $contract +'/' + this.form.value.kt_codigo).subscribe((data: ContratoEntidad | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsEntities = data;
      }
    });
    this.loading = false;
  }


  ngOnDestroy() {
    this.accountService.set_showNavBar(true);
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }

  clearSearchEntities() {
    this.form.patchValue({ searchEntity: '' });
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
      (this.form.value.ko_codigo ?
        this.http.put<any>(environment.urlAPI + 'contratos', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'contratos', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: Contrato | any) => {
              if (data !== null && data !== undefined && data.ko_codigo !== null && data.ko_codigo !== undefined) {
                this.form.patchValue(data as Contrato);
                this.title = 'Editar Contrato';
                this.alertService.success('Contrato #' + data.ko_codigo + ' guardado', { autoClose: true, keepAfterRouteChange: true });
                this.saveEntities();
                this.submitting = false;
                //this.router.navigateByUrl('admin/contrato');
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

  async loadContractsTypesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'contrato/tipos').subscribe((data: TipoContrato | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsContractTypes = data;
      }
      this.loading = false;
    });
  }

  async loadPeopleList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'personas').subscribe((data: Personal | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsPeople = data;
      }
    });
    this.loading = false;
  }

  get filterEntities() {
    const fo = new IgxFilterOptions();
    fo.key = ['en_nombre', 'di_nombre', 'pe_nombres', 'en_plus_code', 'en_franquicia', 'en_especialidad', '', '', ''];
    fo.inputValue = this.form.value.searchEntity;//this.searchEntity;
    return fo;
  }

  saveEntities() {
    if (this.lsEntities.length) {
      if (navigator.onLine) {
        this.submitting = true;
        /*
        console.log(this.lsEntities.filter(object => object.us_edit !== undefined).map(object => {
          return {...object,ko_codigo: this.form.value.ko_codigo};
        }));*/

        this.http.patch<any>(environment.urlAPI + 'entidades/contrato', (this.lsEntities.filter(object => object.us_edit !== undefined).map(object => {
          return { ...object, ko_codigo: this.form.value.ko_codigo };
        })))
          .pipe(first())
          .subscribe(
            {
              next: (data: ContratoEntidad[] | any) => {
                if (data !== null && data !== undefined && data.length) {
                  this.lsEntities = data;
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

  disableEntity(en_codigo: number) {
    const index: number = -1;
    this.lsEntities.forEach((ke, i) => {
      if (ke.en_codigo == en_codigo) {
        this.lsEntities[i].ke_estado = !ke.ke_estado;
        this.lsEntities[i].us_edit = this.user?.us_codigo; /*in order to send only edited elements*/
      }
    });
  }

  encodeURI(value: string): string {
    return encodeURIComponent(value);
  }

}

export class ContratoEntidad {
  ke_codigo?: number;
  ko_codigo?: number;
  en_codigo?: number;
  ke_estado?: boolean;
  us_edit?: number;

  kt_codigo?: number;
  pe_codigo?: number;
  di_codigo?: string;
  en_nombre?: string;
  en_franquicia?: string;
  en_especialidad?: string;
  en_24horas?: boolean;
  en_latitud?: number;
  en_longitud?: number;
  en_plus_code?: string;
  en_estado?: boolean;
  pe_nombres?: string;
  di_nombre?: string;
}
