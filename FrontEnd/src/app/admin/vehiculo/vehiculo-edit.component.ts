import { Component, OnInit } from '@angular/core';

import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, Personal, Circuito, Distrito, Pais, Provincia, Ciudad, Subcircuito, Vehiculo } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
/*import { NgxIndexedDBService } from 'ngx-indexed-db';*/
import { Observable, first, map, merge, startWith } from 'rxjs';
import { verifiedAccount } from '@igniteui/material-icons-extended';
import { IgxFilterOptions } from 'igniteui-angular';

@Component({
  selector: 'app-vehiculo-edit',
  templateUrl: './vehiculo-edit.component.html',
  styleUrls: ['./vehiculo-edit.component.scss']
})
export class VehiculoEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: string;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsVehicleTypes: TipoVehiculo[] = [];
  lsVehicleBrands: MarcaVehiculo[] = [];
  lsVehicleModels: ModeloVehiculo[] = [];

  lsCountries: Pais[] = [];
  lsDistricts: Distrito[] = [];
  lsCircuits: Circuito[] = [];
  lsSubcircuits: Subcircuito[] = [];
  /*
  lsCities: Ciudad[] = [];
  lsContactTypes: TipoContacto[] = [];

  lsContacts: Contacto[] = [];
  */
  lsDependencies: Dependencia[] = [];
  lsCustodians: Custodio[] = [];

  /*public searchCustodian: string = '';*/


  /**AutoFills**/
  lsFuels: string[] = ['Gasolina', 'Diésel', 'Etanol', 'Hidrógeno', 'Biodiesel', 'Electricidad', 'Metanol', 'Gas'];
  lsColors: string[] = ['Blanco','Negro','Rojo','Naranja','Amarillo','Verde','Azul','Morado','Rosa','Celeste','Beige','Marrón','Gris','Turquesa','Lima','Flúor','Pastel','Carmesí','Carmín','Coral','Curry','Dorada','Escarlata','Fucsia','Índigo','Jade','Lavanda','Lila','Malva','Melocotón','Menta','Púrpura','Rosa palo','Rubio','Salmón','Sandía','Verde agua','Verde oliva','Violeta','Zafiro'];
  flFuels: Observable<string[]> | undefined = undefined;
  flModels: Observable<string[]> | undefined = undefined;
  flColor1: Observable<string[]> | undefined = undefined;
  flColor2: Observable<string[]> | undefined = undefined;

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
    this.accountService.checkAppPermission(6);
    this.loadVehicleTypesList();
    this.loadVehicleBrandsList();
    this.loadVehicleModelsList();
    this.loadCountriesList();
    this.loadDistrictsList();
    this.loadCircuitsList();
    this.loadSubcircuitsList();

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      ve_codigo: [null, (this.id ? Validators.required : null)],
      /*
      ve_dni: ['', [Validators.required, Validators.minLength(10), Validators.maxLength(10), Validators.pattern("^[0-9]*$")]],
      ve_nombre1: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(50), Validators.pattern("^[a-zA-Z ]+$")]],
      ve_nombre2: ['', [Validators.minLength(0), Validators.maxLength(50), Validators.pattern("^[a-zA-Z ]+$")]],
      ve_apellido1: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(50), Validators.pattern("^[a-zA-Z ]+$")]],
      ve_apellido2: ['', [Validators.minLength(0), Validators.maxLength(50), Validators.pattern("^[a-zA-Z ]+$")]],
      ve_sangre: ['', [Validators.required, Validators.minLength(2), Validators.maxLength(3), Validators.pattern("^[A-Z]{1,2}[-+]{1}$")]],
      ve_fnacimiento: [null],
      ci_codigo: [null, Validators.required],
      ra_codigo: [null],
      */
      ve_placa: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(7), Validators.pattern("^[A-Z0-9]+$")]],
      ve_chasis: ['', [Validators.required, Validators.pattern("^[A-Z0-9]+$")]],
      ve_motor: ['', [Validators.required, Validators.pattern("^[A-Z0-9]+$")]],
      vt_codigo: [null, Validators.required],
      vm_codigo: [null, Validators.required],
      pa_codigo: [null, Validators.required],
      ve_modelo: [null, Validators.required],
      ve_anio: [null, [Validators.required, Validators.minLength(4), Validators.maxLength(4), Validators.min((new Date().getFullYear()) - 50), Validators.max(new Date().getFullYear())]],
      ve_cilindaraje: [null, [Validators.required, Validators.min(0.01), Validators.max(5000)]],
      ve_capacidadCarga: [null, [Validators.required, Validators.min(0.01), Validators.max(5000)]],
      ve_capacidadPasajero: [null, [Validators.required, Validators.min(1), Validators.max(1000)]],
      ve_km: [null, [Validators.required, Validators.min(1), Validators.max(320000)]],
      ve_color: ['', Validators.required],
      ve_color2: [''],
      ve_combustible: [''],
      ve_torque: [''],
      ve_transmision: [''],
      ve_caballos: [''],

      searchCustodian: ['']
    });

    this.title = 'Crear Vehículo';
    if (this.id) {
      // edit mode
      this.title = 'Editar Vehículo';
      this.loading = true;

      this.http.get<any>(environment.urlAPI + 'vehiculos/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: Vehiculo | any) => {
              if (data !== null && data !== undefined && data.ve_codigo !== null && data.ve_codigo !== undefined) {
                this.form.patchValue(data as Vehiculo);
                /*this.loadProvincesList(data?.pa_codigo);
                this.loadCitiesList(data?.pr_codigo);
                this.loadContactsList(data?.ve_codigo);*/
                this.loadDependenciesList(data?.ve_codigo);
              }
              this.loading = false;
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/vehiculo');
              this.loading = false;
            }
          }
        );
    }

    /**Fuel**/
    this.flFuels = this.form.get("ve_combustible")?.valueChanges.pipe(startWith(''), map(value => this.filterFuels(value || '')),);
    this.flModels = this.form.get("ve_modelo")?.valueChanges.pipe(startWith(''), map(value => this.filterModels(value || '')),);
    this.flColor1 = this.form.get("ve_color")?.valueChanges.pipe(startWith(''), map(value => this.filterColors(value || '')),);
    this.flColor2 = this.form.get("ve_color2")?.valueChanges.pipe(startWith(''), map(value => this.filterColors(value || '')),);
  }

  async loadVehicleTypesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'vehiculo/tipos').subscribe((data: TipoVehiculo | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsVehicleTypes = data;
      }
      this.loading = false;
    });
  }

  async loadVehicleBrandsList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'vehiculo/marcas').subscribe((data: MarcaVehiculo | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsVehicleBrands = data;
      }
    });
    this.loading = false;
  }

  async loadVehicleModelsList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'vehiculo/modelos').subscribe((data: ModeloVehiculo | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsVehicleModels = data;
      }
    });
    this.loading = false;
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

  async loadDependenciesList($person: number = 0) {
    this.loading = true;
    this.lsDependencies = [];
    this.http.get<any>(environment.urlAPI + 'dependencias/vehiculos/' + $person).subscribe((data: Dependencia | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsDependencies = data;
        this.loadCustodiansList();
      }
    });
    this.loading = false;
  }

  async loadCustodiansList() {
    this.loading = true;
    this.lsCustodians = [];
    this.http.post<any>(environment.urlAPI + 'custodios/dependencias', this.lsDependencies).subscribe((data: Custodio | any) => {
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
      (this.form.value.ve_codigo ?
        this.http.put<any>(environment.urlAPI + 'vehiculos', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'vehiculos', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: Personal | any) => {
              if (data !== null && data !== undefined && data.ve_codigo !== null && data.ve_codigo !== undefined) {
                this.form.patchValue(data as Personal);
                this.title = 'Editar Vehículo';
                this.alertService.success('Vehículo #' + data.ve_codigo + ' guardado', { autoClose: true, keepAfterRouteChange: true });
                this.saveDependencies();
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

  private filterFuels(value: string): string[] {
    return this.lsFuels.filter(option => option.toLowerCase().includes(value.toLowerCase()));
  }
  private filterModels(value: string): string[] {
    return this.lsVehicleModels
      .filter((obj) => obj.vm_codigo == this.form.value.vm_codigo && obj.mm_nombre!.toLowerCase().includes(value.toLowerCase()))
      .map((obj) => obj.mm_nombre!);
  }
  private filterColors(value: string): string[] {
    return this.lsColors.filter(option => option.toLowerCase().includes(value.toLowerCase()));
  }

  get filterCustodians() {
    const fo = new IgxFilterOptions();
    fo.key = ['pe_nombres', 'ra_nombre'];
    fo.inputValue = this.form.value.searchCustodian;//this.searchCustodian;
    return fo;
  }

  /*
  addContactRow() {
    if (this.checkContacts()) {
      let c: Contacto = {};
      this.lsContacts[this.lsContacts.length] = c;
    }
  }

  private checkContacts(): boolean {
    // this.alertService.clear();
    let ok: boolean = true;

    this.lsContacts.forEach(co => {

      co.ve_codigo = this.form.value.ve_codigo;//this.form.controls.ve_codigo.value;
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
                if (data !== null && data !== undefined && data.length) {
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
*/
  addDependencyRow() {
    if (this.checkDependencies()) {
      let d: Dependencia = {};
      this.lsDependencies[this.lsDependencies.length] = d;
    }
  }

  private checkDependencies(): boolean {
    /*this.alertService.clear();*/
    let ok: boolean = true;

    this.lsDependencies.forEach((de, index) => {

      de.ve_codigo = this.form.value.ve_codigo;
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
    this.lsDependencies[$index].ve_codigo = this.form.value.ve_codigo;
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
        this.loadCustodiansList();
        break;
      }
    }
  }

  saveDependencies() {
    if (this.checkDependencies() && this.lsDependencies.length) {
      if (navigator.onLine) {
        this.submitting = true;

        this.http.patch<any>(environment.urlAPI + 'dependencias/vehiculos', this.lsDependencies)
          .pipe(first())
          .subscribe(
            {
              next: (data: Dependencia[] | any) => {
                if (data !== null && data !== undefined && data.length) {
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

  saveCustodians() {
    if (this.lsCustodians.length) {
      if (navigator.onLine) {
        this.submitting = true;

        this.http.patch<any>(environment.urlAPI + 'custodios/dependencias', this.lsCustodians)
          .pipe(first())
          .subscribe(
            {
              next: (data: Custodio[] | any) => {
                if (data !== null && data !== undefined && data.length) {
                  this.lsCustodians = data;
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

  disableCustodian(pe_codigo: number) {
    const index: number = -1;
    this.lsCustodians.forEach((vc, i) => {
      if (vc.pe_codigo == pe_codigo) {
        this.lsCustodians[i].vc_estado = !vc.vc_estado;
      }
      this.lsCustodians[i].us_codigo = this.user?.us_codigo;
    });
  }


}


export class Dependencia {
  vs_codigo?: number;
  ve_codigo?: number;
  sc_codigo?: string;

  cc_codigo?: string;
  di_codigo?: string;

  vs_estado?: boolean;

  us_codigo?: number;
}

export class TipoVehiculo {
  vt_codigo?: number;
  vt_nombre?: string;
  vt_estado?: boolean;
}
export class MarcaVehiculo {
  vm_codigo?: number;
  vm_nombre?: string;
  vm_estado?: boolean;
}
export class ModeloVehiculo {
  mm_codigo?: number;
  vm_codigo?: number;
  mm_nombre?: string;
  mm_estado?: boolean;
}

export class Custodio {
  vc_codigo?: number;
  ve_codigo?: number;
  pe_codigo?: number;
  vc_estado?: boolean;
  us_codigo?: number;

  pe_nombres?:string;
  ra_nombre?:string;
}
