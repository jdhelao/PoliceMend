import { Component, OnInit } from '@angular/core';

import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, Personal, Circuito, Distrito, Pais, Provincia, Ciudad, Rango, TipoContacto, Contacto, Subcircuito } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { Observable, first, map, merge, startWith } from 'rxjs';

@Component({
  selector: 'app-personal-edit',
  templateUrl: './personal-edit.component.html',
  styleUrls: ['./personal-edit.component.scss']
})
export class PersonalEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: string;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsRanks: Rango[] = [];
  lsCountries: Pais[] = [];
  lsProvinces: Provincia[] = [];
  lsCities: Ciudad[] = [];
  lsContactTypes: TipoContacto[] = [];
  lsDistricts: Distrito[] = [];
  lsCircuits: Circuito[] = [];
  lsSubcircuits: Subcircuito[] = [];

  lsContacts: Contacto[] = [];
  lsDependencies: Dependencia[] = [];

  /**Blood**/
  lsBloods: string[] = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
  filteredOptions_blood: Observable<string[]> | undefined = undefined;

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
    this.accountService.checkAppPermission(5);
    this.loadCountriesList();
    this.loadRanksList();
    this.loadContactTypesList();
    this.loadDistrictsList();
    this.loadCircuitsList();
    this.loadSubcircuitsList();

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      pe_codigo: [null, (this.id ? Validators.required : null)],
      pe_dni: ['', [Validators.required, Validators.minLength(10), Validators.maxLength(10), Validators.pattern("^[0-9]*$")]],
      pe_nombre1: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(50), Validators.pattern("^[a-zA-Z ]+$")]],
      pe_nombre2: ['', [Validators.minLength(0), Validators.maxLength(50), Validators.pattern("^[a-zA-Z ]+$")]],
      pe_apellido1: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(50), Validators.pattern("^[a-zA-Z ]+$")]],
      pe_apellido2: ['', [Validators.minLength(0), Validators.maxLength(50), Validators.pattern("^[a-zA-Z ]+$")]],
      pe_sangre: ['', [Validators.required, Validators.minLength(2), Validators.maxLength(3), Validators.pattern("^[A-Z]{1,2}[-+]{1}$")]],
      pe_fnacimiento: [null],
      ci_codigo: [null, Validators.required],
      ra_codigo: [null],

      pa_codigo: [null],
      pr_codigo: [null],
    });

    this.title = 'Crear Personal';
    if (this.id) {
      // edit mode
      this.title = 'Editar Personal';
      this.loading = true;

      this.http.get<any>(environment.urlAPI + 'personas/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: Personal | any) => {
              if (data !== null && data !== undefined && data.pe_codigo !== null && data.pe_codigo !== undefined) {
                this.form.patchValue(data as Personal);
                this.loadProvincesList(data?.pa_codigo);
                this.loadCitiesList(data?.pr_codigo);
                this.loadContactsList(data?.pe_codigo);
                this.loadDependenciesList(data?.pe_codigo);
              }
              this.loading = false;
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/personal');
              this.loading = false;
            }
          }
        );
    }

    /**Blood**/
    this.filteredOptions_blood = this.form.get("pe_sangre")?.valueChanges.pipe(startWith(''), map(value => this.filterBlood(value || '')),);
  }

  async loadCountriesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'paises').subscribe((data: Pais | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsCountries = data;
      }
      this.loading = false;
    });
  }

  async loadDistrictsList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'distritos').subscribe((data: Distrito | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsDistricts = data;
      }
      this.loading = false;
    });
  }
  async loadCircuitsList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'circuitos').subscribe((data: Circuito | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsCircuits = data;
      }
      this.loading = false;
    });
  }
  async loadSubcircuitsList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'subcircuitos').subscribe((data: Subcircuito | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsSubcircuits = data;
      }
      this.loading = false;
    });
  }

  async loadRanksList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'rangos').subscribe((data: Circuito | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsRanks = data;
      }
    });
    this.loading = false;
  }

  async loadContactTypesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'contactos/tipos').subscribe((data: TipoContacto | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsContactTypes = data;
      }
    });
    this.loading = false;
  }

  async loadProvincesList($country: number = 0) {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'provincias/pais/' + $country).subscribe((data: Provincia | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsProvinces = data;
      }
    });
    this.loading = false;
  }

  async loadCitiesList($province: number = 0) {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'ciudades/provincia/' + $province).subscribe((data: Ciudad | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsCities = data;
      }
    });
    this.loading = false;
  }

  async loadContactsList($person: number = 0) {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'contactos/personas/' + $person).subscribe((data: Contacto | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsContacts = data;
      }
    });
    this.loading = false;
  }

  async loadDependenciesList($person: number = 0) {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'dependencias/personas/' + $person).subscribe((data: Dependencia | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsDependencies = data;
      }
    });
    this.loading = false;
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
      (this.form.value.pe_codigo ?
        this.http.put<any>(environment.urlAPI + 'personas', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'personas', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: Personal | any) => {
              if (data !== null && data !== undefined && data.pe_codigo !== null && data.pe_codigo !== undefined) {
                this.form.patchValue(data as Personal);
                this.title = 'Editar Personal';
                this.alertService.success('Personal #' + data.pe_codigo + ' guardado', { autoClose: true, keepAfterRouteChange: true });
                this.saveContacts();
                this.saveDependencies();
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

  private filterBlood(value: string): string[] {
    const filterValue = value.toLowerCase()
    return this.lsBloods.filter(option => option.toLowerCase().includes(filterValue));
  }

  addContactRow() {
    if (this.checkContacts()) {
      let c: Contacto = {};
      this.lsContacts[this.lsContacts.length] = c;
    }
    //console.log(this.lsContacts);
    //console.log(merge(this.lsContacts, {us_codigo: this.user?.us_codigo}));
  }

  private checkContacts(): boolean {
    this.alertService.clear();
    let ok: boolean = true;

    this.lsContacts.forEach(co => {

      co.pe_codigo = this.form.value.pe_codigo;//this.form.controls.pe_codigo.value;
      co.us_codigo = this.user?.us_codigo;

      if (co.tc_codigo === undefined || co.co_descripcion === undefined || co.co_descripcion.length < 1) {
        this.alertService.warn('Seleccione un tipo y llene su descripción', { autoClose: true });
        ok = false;
      }
      else {

        this.lsContactTypes.forEach(tc => {

          if (ok && co.tc_codigo == tc.tc_codigo && co.co_descripcion !== undefined) {
            if (ok && co.co_descripcion.length < tc.tc_min) {
              this.alertService.warn('[' + co.co_descripcion + '] no cumple el mínimo establecido de ' + tc.tc_min, { autoClose: true });
              ok = false;
            }
            else if (ok && co.co_descripcion?.length > tc.tc_max) {
              this.alertService.warn('[' + co.co_descripcion + '] sobrepasa máximo establecido de ' + tc.tc_max, { autoClose: true });
              ok = false;
            }
            else if (ok && tc.tc_rex.length && !co.co_descripcion.match(tc.tc_rex)) {
              this.alertService.warn('[' + co.co_descripcion + '] no cumple conel formato establecido para ' + tc.tc_nombre, { autoClose: true });
              ok = false;
            }
          }

        });
      }
    });

    return ok;
  }

  editContact_type($index: number, $type: number) {
    this.lsContacts[$index].tc_codigo = $type;
  }

  editContact_value($index: number, $detail: any) {
    this.lsContacts[$index].co_descripcion = $detail.value;
  }

  saveContacts() {
    if (this.checkContacts() && this.lsContacts.length) {
      if (navigator.onLine) {
        this.submitting = true;

        this.http.patch<any>(environment.urlAPI + 'contactos', this.lsContacts)
          .pipe(first())
          .subscribe(
            {
              next: (data: Contacto[] | any) => {
                if (data !== null && data !== undefined && data.length /*&& data.pe_codigo !== null && data.pe_codigo !== undefined*/) {
                  this.lsContacts = data;
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

  addDependencyRow() {
    if (this.checkDependencies()) {
      let d: Dependencia = {};
      this.lsDependencies[this.lsDependencies.length] = d;
    }
    console.log(this.lsDependencies);
    //console.log(merge(this.lsContacts, {us_codigo: this.user?.us_codigo}));
  }

  private checkDependencies(): boolean {
    /*this.alertService.clear();*/
    let ok: boolean = true;

    this.lsDependencies.forEach((de, index) => {

      de.pe_codigo = this.form.value.pe_codigo;
      de.us_codigo = this.user?.us_codigo;

      if (ok && de.di_codigo === undefined) {
        ok = false;
        this.alertService.warn('Seleccione un Distrito en el registro #' + (index + 1), { autoClose: true });
      }
      if (ok && de.cc_codigo === undefined) {
        ok = false;
        this.alertService.warn('Seleccione un Circuito en el registro #' + (index + 1), { autoClose: true });
      }
      if (ok && de.sc_codigo === undefined) {
        ok = false;
        this.alertService.warn('Seleccione un Subcircuito en el registro #' + (index + 1), { autoClose: true });
      }

    });

    return ok;
  }

  editDependency($index: number, $value: string, $level: number) {
    switch ($level) {
      case 1: {
        this.lsDependencies[$index].di_codigo = $value;
        this.lsDependencies[$index].cc_codigo = undefined;
        this.lsDependencies[$index].sc_codigo = undefined;
        break;
      }
      case 2: {
        this.lsDependencies[$index].cc_codigo = $value;
        this.lsDependencies[$index].sc_codigo = undefined;
        break;
      }
      case 3: {
        this.lsDependencies[$index].sc_codigo = $value;
        break;
      }
    }
  }

  saveDependencies() {
    if (this.checkDependencies() && this.lsDependencies.length) {
      if (navigator.onLine) {
        this.submitting = true;

        this.http.patch<any>(environment.urlAPI + 'dependencias/personas', this.lsDependencies)
          .pipe(first())
          .subscribe(
            {
              next: (data: Dependencia[] | any) => {
                if (data !== null && data !== undefined && data.length /*&& data.pe_codigo !== null && data.pe_codigo !== undefined*/) {
                  this.lsDependencies = data;
                  this.submitting = false;
                  //this.router.navigateByUrl('admin/personal');
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

}


export class Dependencia {
  ps_codigo?: number;
  pe_codigo?: number;
  sc_codigo?: string;

  cc_codigo?: string;
  di_codigo?: string;

  ps_estado?: boolean;

  us_codigo?: number;
}
