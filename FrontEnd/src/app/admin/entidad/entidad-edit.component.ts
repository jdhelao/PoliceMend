import { Component, OnInit } from '@angular/core';

import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, Personal, Circuito, Distrito, Pais, Provincia, Ciudad, Subcircuito, Vehiculo, Usuario, Entidad } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
/*import { NgxIndexedDBService } from 'ngx-indexed-db';*/
import { Observable, first, map, merge, startWith } from 'rxjs';
import { verifiedAccount } from '@igniteui/material-icons-extended';
import { IgxFilterOptions } from 'igniteui-angular';

@Component({
  selector: 'app-entidad-edit',
  templateUrl: './entidad-edit.component.html',
  styleUrls: ['./entidad-edit.component.scss']
})
export class EntidadEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: string;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsContractTypes: TipoContrato[] = [];
  lsDistricts: Distrito[] = [];
  lsPeople: Personal[] = [];
  lsCustodians: Custodio[] = [];

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
    this.accountService.checkAppPermission(13);
    this.loadDistrictsList();
    this.loadPeopleList();
    this.loadContractTypesList();


    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      en_codigo: [null, (this.id ? Validators.required : null)],

      kt_codigo: [null, Validators.required],
      pe_codigo: [null],
      di_codigo: [null, Validators.required],
      en_nombre: [null, Validators.required],
      en_franquicia: [null],
      en_especialidad: [null],
      en_24horas: [null],
      en_latitud: [null, [Validators.min(-90), Validators.max(90)]],
      en_longitud: [null, [Validators.min(-180), Validators.max(180)]],
      en_plus_code: [null],

      searchCustodian: ['']
    });

    this.title = 'Crear Entidad';
    if (this.id) {
      // edit mode
      this.title = 'Editar Entidad';
      this.loading = true;

      this.http.get<any>(environment.urlAPI + 'entidades/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: Entidad | any) => {
              if (data !== null && data !== undefined && data.en_codigo !== null && data.en_codigo !== undefined) {
                this.form.patchValue(data as Entidad);
                this.loadCustodiansList(data?.en_codigo);
              }
              this.loading = false;
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/entidad');
              this.loading = false;
            }
          }
        );
    }
  }

  async loadContractTypesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'contrato/tipos').subscribe((data: TipoContrato | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsContractTypes = data;
      }
    });
    this.loading = false;
  }

  async loadDistrictsList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'distritos').subscribe((data: Distrito | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsDistricts = data;
      }
    });
    this.loading = false;
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

  async loadCustodiansList($entity: number = 0) {
    this.loading = true;
    this.lsCustodians = [];
    this.http.get<any>(environment.urlAPI + 'usuarios/entidad/' + $entity).subscribe((data: Custodio | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsCustodians = data;
      }
    });
    this.loading = false;
  }

  ngOnDestroy() {
    this.accountService.set_showNavBar(true);
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }

  clearSearchCustodians() {
    //this.form.value.searchCustodian = '';
    this.form.patchValue({ searchCustodian: '' });
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
      (this.form.value.en_codigo ?
        this.http.put<any>(environment.urlAPI + 'entidades', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'entidades', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: Personal | any) => {
              if (data !== null && data !== undefined && data.en_codigo !== null && data.en_codigo !== undefined) {
                this.form.patchValue(data as Personal);
                this.title = 'Editar Entidad';
                this.alertService.success('Entidad #' + data.en_codigo + ' guardado', { autoClose: true, keepAfterRouteChange: true });
                this.saveCustodians();
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

  get filterCustodians() {
    const fo = new IgxFilterOptions();
    fo.key = ['us_login', 'pf_nombre'];
    fo.inputValue = this.form.value.searchCustodian;//this.searchCustodian;
    return fo;
  }

  saveCustodians() {
    if (this.lsCustodians.length) {
      if (navigator.onLine) {
        this.submitting = true;
        /*
        console.log(this.lsCustodians.filter(object => object.us_edit !== undefined).map(object => {
          return {...object,en_codigo: this.form.value.en_codigo};
        }));*/

        this.http.patch<any>(environment.urlAPI + 'usuarios/entidad', (this.lsCustodians.filter(object => object.us_edit !== undefined).map(object => {
          return { ...object, en_codigo: this.form.value.en_codigo };
        })))
          .pipe(first())
          .subscribe(
            {
              next: (data: Custodio[] | any) => {
                if (data !== null && data !== undefined && data.length) {
                  this.lsCustodians = data;
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

  disableCustodian(us_codigo: number) {
    const index: number = -1;
    this.lsCustodians.forEach((eu, i) => {
      if (eu.us_codigo == us_codigo) {
        this.lsCustodians[i].eu_estado = !eu.eu_estado;
        this.lsCustodians[i].us_edit = this.user?.us_codigo; /*in order to send only edited elements*/
      }
    });
  }


}

export class TipoContrato {
  kt_codigo?: number;
  kt_nombre?: string;
  kt_estado?: boolean;
}
export class Custodio {
  eu_codigo?: number;
  en_codigo?: number;
  us_codigo?: number;
  eu_estado?: boolean;

  pe_nombres?: string;
  us_login?: string;
  pf_nombre?: string;

  us_edit?: number;
}
