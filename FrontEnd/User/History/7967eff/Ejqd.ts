import { formatDate } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { OrdenAbastecimiento, Personal, SolicitudVehicular, Vehiculo } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';

@Component({
  selector: 'app-r-abastecimientos',
  templateUrl: './r-abastecimientos.component.html',
  styleUrls: ['./r-abastecimientos.component.scss']
})
export class RAbastecimientosComponent implements OnInit {
  dateIni!: Date;
  dateEnd!: Date;
  dataSource !: RAbastecimiento[];
  displayedColumns: string[] = ['oa_codigo', 'vt_nombre', 've_modelo', 've_placa', 've_combustible'];

  loading: boolean = false;
  submitting: boolean = false;

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private alertService: AlertService, private http: HttpClient) {
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(17);
    //this.getPesonalList();
  }

  onChangeDates(date: any, type: number) {
    if (date !== undefined) {
      if (type == 1) this.dateIni = date;
      if (type == 2) this.dateEnd = date;
    }

    if (this.dateIni !== undefined && this.dateEnd !== undefined) {
      if (this.dateIni <= this.dateEnd)
        this.loadReport();
      else
        this.alertService.warn('Seleccione un rango valido!', { autoClose: true });
    }
  }

  loadReport() {
    this.alertService.clear();
    if (navigator.onLine) {
      this.submitting = true;
      this.loading = true;
      this.http.get<any>(environment.urlAPI + 'orden-abastecimientos/reporte/' + formatDate(this.dateIni, 'yyyy-MM-dd', 'en') + '/' + formatDate(this.dateEnd, 'yyyy-MM-dd', 'en')).subscribe((data: RAbastecimiento[]) => {
        console.log(data);
        if (data !== null && data !== undefined  && data.length > 0
        ) {
          this.dataSource = data;
        }
        else {
          this.dataSource = [];
          this.alertService.info('No hay información con el rago  especificado.');
        }
      },
        error => {
          console.log(error);
          this.alertService.error('Hubo un problema al consultar los datos.');
        },
        () => {
          this.submitting = false;
          this.loading = false;
        }
      );
    }
    else {
      this.alertService.info('Sin Conexión!!', { autoClose: true });
    }
  }
}

export interface RAbastecimiento extends SolicitudVehicular, OrdenAbastecimiento, Vehiculo, Personal{

}