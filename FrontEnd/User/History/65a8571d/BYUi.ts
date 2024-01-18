import { formatDate } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { OrdenMantenimiento, Personal, SolicitudVehicular, Vehiculo } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';

@Component({
  selector: 'app-r-mantenimientos',
  templateUrl: './r-mantenimientos.component.html',
  styleUrls: ['./r-mantenimientos.component.scss']
})
export class RMantenimientosComponent implements OnInit {
  dateIni!: Date;
  dateEnd!: Date;
  finished: boolean=true;
  dataSource !: RMantenimiento[];
  displayedColumns: string[] = ['om_codigo', 'vt_nombre', 've_modelo', 've_placa', 've_combustible', 'pe_nombres', 'om_progreso', 'om_total'];

  loading: boolean = false;
  submitting: boolean = false;

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private alertService: AlertService, private http: HttpClient) {
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(18);
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
      else if (type != 3)
        this.alertService.warn('Seleccione un rmngo valido!', { autoClose: true });
    }
  }

  loadReport() {
    this.alertService.clear();
    if (navigator.onLine) {
      this.submitting = true;
      this.loading = true;
      this.http.get<any>(environment.urlAPI + 'orden-mantenimiento/reporte/' + formatDate(this.dateIni, 'yyyy-MM-dd', 'en') + '/' + formatDate(this.dateEnd, 'yyyy-MM-dd', 'en') + '/' + Number(this.finished)).subscribe((data: RMantenimiento[]) => {
        console.log(data);
        if (data !== null && data !== undefined && data.length > 0
        ) {
          this.dataSource = data;
        }
        else {
          this.dataSource = [];
          this.alertService.info('No hay información con el rmgo  especificado.');
        }
      },
        error => {
          this.loading = false;
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

  download() {
    if (this.dataSource !== undefined && this.dataSource.length > 0) {
      let text = 'Orden,Tipo Vehículo,Modelo,Color,Chasis,Placa,Tipo Combustible,Cédula,Solicitante,Doc. Consumo,Avance,Costo\n';

      this.dataSource.forEach((rm: RMantenimiento, index) => {
        text = ''.concat(text
          , String(rm.om_codigo), ','
          , String(rm.vt_nombre), ','
          , String(rm.ve_modelo), ','
          , String(rm.ve_color), ','
          , String(rm.ve_chasis), ','
          , String(rm.ve_placa), ','
          , String(rm.ve_combustible), ','
          , String(rm.pe_dni), ','
          , String(rm.pe_nombres), ','
          , String(rm.om_documento), ','
          , String(rm.om_progreso), ','
          , String(rm.om_total), '\n');
      });

      const bom = new Uint8Array([0xEF, 0xBB, 0xBF]);
      const blob = new Blob([bom, text], { type: 'text/csv;charset=utf-8' });
      const downloadLink = document.createElement('a');
      downloadLink.href = window.URL.createObjectURL(blob);
      downloadLink.download = 'OrdenesMantenimiento.csv';
      downloadLink.click();
    }
  }
}

export interface RMantenimiento extends SolicitudVehicular, OrdenMantenimiento, Vehiculo, Personal {

}
