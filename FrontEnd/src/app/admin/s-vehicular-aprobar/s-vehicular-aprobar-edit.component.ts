import { HttpClient } from '@angular/common/http';
import { AbstractType, Component, Inject, OnInit, ViewChild } from '@angular/core';
import { AbstractControl, AbstractFormGroupDirective, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, SolicitudVehicular } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { first } from 'rxjs';

import {
  ChartComponent,
  ApexAxisChartSeries,
  ApexChart,
  ApexXAxis,
  ApexDataLabels,
  ApexYAxis,
  ApexFill,
  ApexMarkers,
  ApexStroke
} from "ng-apexcharts";

@Component({
  selector: 'app-s-vehicular-aprobar-edit',
  templateUrl: './s-vehicular-aprobar-edit.component.html',
  styleUrls: ['./s-vehicular-aprobar-edit.component.scss']
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

  chartOptions1: ChartOptions1 = {
    series: [/*{ name: "Galones", data: [] }, { name: "Galones x Kms", data: [] }, { name: "Galones x Día", data: [] }*/],
    chart: {
      id: "chart2",
      type: "line",
      height: 230,
      toolbar: {
        autoSelected: "pan",
        show: false
      }
    },
    colors: ["#003f5c", "#bc5090", "#ffa600"],
    stroke: {
      width: 3
    },
    dataLabels: {
      enabled: true
    },
    fill: {
      opacity: 1
    },
    markers: {
      size: 0
    },
    xaxis: {
      type: "datetime"
    }
  };

  chartOptions2: ChartOptions2 = {
    series: [
      {
        name: "series1",
        data: []
      }
    ],
    chart: {
      id: "chart1",
      height: 130,
      type: "area",
      brush: {
        target: "chart2",
        enabled: true
      },
      selection: {
        enabled: true,
        xaxis: {
          /*min: new Date("19 Jun 2023").getTime(),
          max: new Date("14 Aug 2023").getTime()*/
          min: new Date().getTime() - 7776000000,
          max: new Date().getTime()
        }
      }
    },
    colors: ["#008FFB"],
    fill: {
      type: "gradient",
      gradient: {
        opacityFrom: 0.91,
        opacityTo: 0.1
      }
    },
    xaxis: {
      type: "datetime",
      tooltip: {
        enabled: false
      }
    },
    yaxis: {
      tickAmount: 2
    }
  };




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
                  this.title = 'Solicitud ' + (Boolean(data.sv_aprobacion) ? 'Aprobada' : 'Negada');
                  this.form.disable();
                }
                this.loadHistory(data.ve_codigo);
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
    console.log('chartOptions1'); console.log(this.chartOptions1); console.log('chartOptions2'); console.log(this.chartOptions2);
  }

  ngOnDestroy() {
    this.accountService.set_showNavBar(true);
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }

  aprobar(value: boolean) {
    if (this.form.value.sv_aprobacion === null || Boolean(this.form.value.sv_aprobacion) != value) {
      this.form.patchValue({ sv_aprobacion: value });
    }
    else if (Boolean(this.form.value.sv_aprobacion) == value) {
      this.form.patchValue({ sv_aprobacion: null });
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


  loadHistory(ve_codigo: number) {
    this.http.get<any>(environment.urlAPI + 'vehiculo/historial/gas/' + ve_codigo).subscribe((data: any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        var series_data: any[][] = [];
        data.forEach((gas: { _date: any; _value: number }) => {
          series_data.push([new Date(gas._date).getTime(), gas._value]);
        });
        this.chartOptions2.series[0].data = series_data;
      }
    });

    this.http.get<any>(environment.urlAPI + 'vehiculo/historial/gal/' + ve_codigo).subscribe((data: any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        var series_data: any[][] = [];
        var min = 9999999;
        var max = 0;
        var avg = 0;
        var _n = 0;
        data.forEach((gas: { _date: any; _value: number }) => {
          series_data.push([new Date(gas._date).getTime(), gas._value]);
          min = (gas._value > 0 && gas._value <= min ? gas._value : min);
          max = (gas._value >= max ? gas._value : max);
          avg += Number(gas._value);
          _n = (gas._value > 0 ? _n + 1 : _n);
        });
        if (_n > 0) {
          avg = avg / _n;
        }
        this.chartOptions1.series.push({ name: "Min(" + Number(min).toFixed(2) + ") Max(" + Number(max).toFixed(2) + ") x̅(" + Number(avg).toFixed(2) + ") <br/>Galones", data: series_data });
      }
    });

    this.http.get<any>(environment.urlAPI + 'vehiculo/historial/gal-kms/' + ve_codigo).subscribe((data: any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        var series_data: any[][] = [];
        var min = 9999999;
        var max = 0;
        var avg = 0;
        var _n = 0;
        data.forEach((gas: { _date: any; _value: number }) => {
          series_data.push([new Date(gas._date).getTime(), gas._value]);
          min = (gas._value > 0 && gas._value <= min ? gas._value : min);
          max = (gas._value >= max ? gas._value : max);
          avg += Number(gas._value);
          _n = (gas._value > 0 ? _n + 1 : _n);
        });
        if (_n > 0) {
          avg = avg / _n;
        }
        this.chartOptions1.series.push({ name: "Min(" + Number(min).toFixed(2) + ") Max(" + Number(max).toFixed(2) + ") x̅(" + Number(avg).toFixed(2) + ") <br/>Galones x Km", data: series_data });
      }
    });

    this.http.get<any>(environment.urlAPI + 'vehiculo/historial/gal-day/' + ve_codigo).subscribe((data: any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        var series_data: any[][] = [];
        var min = 9999999;
        var max = 0;
        var avg = 0;
        var _n = 0;
        data.forEach((gas: { _date: any; _value: number }) => {
          series_data.push([new Date(gas._date).getTime(), gas._value]);
          min = (gas._value > 0 && gas._value <= min ? gas._value : min);
          max = (gas._value >= max ? gas._value : max);
          avg += Number(gas._value);
          _n = (gas._value > 0 ? _n + 1 : _n);
        });
        if (_n > 0) {
          avg = avg / _n;
        }
        this.chartOptions1.series.push({ name: "Min(" + Number(min).toFixed(2) + ") Max(" + Number(max).toFixed(2) + ") x̅(" + Number(avg).toFixed(2) + ") <br/>Galones x Día", data: series_data });
      }
    });
  }

}




export type ChartOptions1 = {
  series: ApexAxisChartSeries;
  chart: ApexChart;
  xaxis: ApexXAxis;
  dataLabels: ApexDataLabels;
  fill: ApexFill;
  stroke: ApexStroke;
  markers: ApexMarkers;
  colors: string[];
};

export type ChartOptions2 = {
  series: ApexAxisChartSeries;
  chart: ApexChart;
  xaxis: ApexXAxis;
  yaxis: ApexYAxis;
  fill: ApexFill;
  colors: string[];
};
