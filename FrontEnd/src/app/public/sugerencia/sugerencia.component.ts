import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { Circuito } from '@app/_models/circuito';
import { Distrito } from '@app/_models/distrito';
import { Subcircuito } from '@app/_models/subcircuito';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';

@Component({
  selector: 'app-sugerencia',
  templateUrl: './sugerencia.component.html',
  styleUrls: ['./sugerencia.component.scss']
})
export class SugerenciaComponent implements OnInit {
  form!: FormGroup;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsDistricts: Distrito[] = [];
  lsCircuits: Circuito[] = [];
  lsSubcircuits: Subcircuito[] = [];
  lsTypes: any[] = [{ su_tipo: 1, td_nombre: 'Reclamo' }, { su_tipo: 2, td_nombre: 'Sugerencia' }];

  constructor(
    private dbService: NgxIndexedDBService,
    private formBuilder: FormBuilder,
    private accountService: AccountService,
    private alertService: AlertService,
    private http: HttpClient
  ) { this.accountService.set_showNavBar(false); }

  ngOnInit() {
    this.loadDistrictList();

    // form with validation rules
    this.form = this.formBuilder.group({
      su_tipo: ['', Validators.required],
      di_codigo: [''],
      cc_codigo: [''],
      sc_codigo: ['', Validators.required],
      su_contacto: ['', [Validators.required, Validators.minLength(10), Validators.maxLength(10), Validators.pattern("^[0-9]*$")]],
      su_nombres: ['', Validators.required],
      su_apellidos: ['', Validators.required],
      su_detalles: ['', Validators.required]
    });
  }

  async loadDistrictList() {
    this.lsDistricts = [];
    this.dbService.getAll('distritos').subscribe((result: Distrito | any) => {
      result.forEach((di: Distrito) => {
        if (di.di_estado) {
          this.lsDistricts.push(di);
        }
      });
    });
  }

  async loadCircuitList(district: string = '') {
    this.lsCircuits = [];
    if (district.length) {
      this.dbService.getAll('circuitos').subscribe((result: Circuito | any) => {
        result.forEach((cc: Circuito) => {
          if (cc.cc_estado && cc.di_codigo == district) {
            this.lsCircuits.push(cc);
          }
        });
      });
    }
    this.loadSubcircuitList();
  }

  async loadSubcircuitList(circuit: string = '') {
    this.lsSubcircuits = [];
    if (circuit.length) {
      this.dbService.getAll('subcircuitos').subscribe((result: Subcircuito | any) => {
        result.forEach((sc: Subcircuito) => {
          if (sc.sc_estado && sc.cc_codigo == circuit) {
            this.lsSubcircuits.push(sc);
          }
        });
      });
    }
  }

  changeTitle(td: number) {
    this.title = (td == 1) ? 'reclamo' : 'sugerencia';
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }

  onSubmit() {
    this.submitted = true;

    // reset alerts on submit
    this.alertService.clear();

    // stop here if form is invalid
    if (this.form.invalid) {
      return;
    }

    if (navigator.onLine) {
      console.log(this.form.value);
      this.submitting = true;
      this.loading = true;
      this.http.post<any>(environment.urlAPI + 'sugerencias'
        , this.form.value).subscribe((data: any) => {
          console.log(data);
          if (data !== null && data !== undefined
            && data.su_codigo !== null && data.su_codigo !== undefined && Number(data.su_codigo) > 0
          ) {
            this.form.reset();
            Object.keys(this.form.controls).forEach((key) => { this.form.controls[key].setErrors(null); });
            this.alertService.success('Genial, ' + this.title + ' #' + data.su_codigo + ' creada correctamente.');
          }
        },
          error => {
            console.log(error);
            this.alertService.error('Oop, Hubo un problema al guardar su ' + this.title);
          },
          () => {
            this.submitting = false;
            this.submitted = false;
            this.loading = false;
          }

        );
    }
    else {
      this.alertService.warn('Sin Conexión', { autoClose: true });
    }


  }
}
