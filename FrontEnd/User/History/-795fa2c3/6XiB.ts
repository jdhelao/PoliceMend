import { formatDate } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { OrdenMovilizacion, Circuito, Personal, SolicitudVehicular, Vehiculo } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';

@Component({
  selector: 'app-r-movilizaciones',
  templateUrl: './r-movilizaciones.component.html',
  styleUrls: ['./r-movilizaciones.component.scss']
})
export class RMovilizacionesComponent implements OnInit {
  dateIni!: Date;
  dateEnd!: Date;
  dataSource !: RMovilizacion[];
  displayedColumns: string[] = ['od_codigo', 'sv_descripcion', 'od_fecha_salida', 'od_hora_salida', 'vt_nombre', 've_modelo', 've_placa', 've_km', 'pe_nombres'];

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
      this.http.get<any>(environment.urlAPI + 'orden-movilizaciones/reporte/' + formatDate(this.dateIni, 'yyyy-MM-dd', 'en') + '/' + formatDate(this.dateEnd, 'yyyy-MM-dd', 'en')).subscribe((data: RMovilizacion[]) => {
        console.log(data);
        this.loading = false;
        if (data !== null && data !== undefined && data.length > 0
        ) {
          this.dataSource = data;
        }
        else {
          this.dataSource = [];
          this.alertService.info('No hay información con el rago  especificado.');
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
      let text = 'Orden,Tipo Vehículo,Modelo,Color,Chasis,Placa,Tipo Combustible,Cédula,Solicitante,Doc. Consumo,Galones,Costo\n';
/*
      this.dataSource.forEach((ra: RMovilizacion, index) => {
        text = ''.concat(text
          , String(ra.od_codigo), ','
          , String(ra.vt_nombre), ','
          , String(ra.ve_modelo), ','
          , String(ra.ve_color), ','
          , String(ra.ve_chasis), ','
          , String(ra.ve_placa), ','
          , String(ra.ve_combustible), ','
          , String(ra.pe_dni), ','
          , String(ra.pe_nombres), ','
          , String(ra.od_documento), ','
          , String(ra.od_galones), ','
          , String(ra.od_total), '\n');
      });*/

      const bom = new Uint8Array([0xEF, 0xBB, 0xBF]);
      const blob = new Blob([bom, text], { type: 'text/csv;charset=utf-8' });
      const downloadLink = document.createElement('a');
      downloadLink.href = window.URL.createObjectURL(blob);
      downloadLink.download = 'OrdenesAbastecimiento.csv';
      downloadLink.click();
    }
  }
}

export interface RMovilizacion extends SolicitudVehicular, OrdenMovilizacion, Vehiculo, Personal {

}
