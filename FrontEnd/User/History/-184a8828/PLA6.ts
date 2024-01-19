import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, SolicitudVehicular } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-s-vehicular-list',
  templateUrl: './s-vehicular-list.component.html',
  styleUrls: ['./s-vehicular-list.component.scss']
})
export class SVehicularListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public vehicleRequests: SolicitudVehicular[] | any[] = [];
  public seachVehicleRequest: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(14);
    this.getVehicleRequestList();
  }

  async getVehicleRequestList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'solicitud-vehiculos/persona/' + (this.user?.pf_codigo == 1/*admin*/ ? 'all' : this.user?.pe_codigo)).subscribe((data: SolicitudVehicular | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.vehicleRequests = data;
      }
    });
    this.loading = false;
  }

  get filterVehicleRequests() {
    const fo = new IgxFilterOptions();
    fo.key = ['sv_descripcion', 'kt_nombre', 've_placa', 'vm_nombre', 'pe_dni', 'pe_nombres'];
    fo.inputValue = this.seachVehicleRequest;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.vehicleRequests.forEach((sv, i) => {
      if (sv.sv_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'solicitud-vehiculos'
            , { "sv_codigo": sv.sv_codigo, "sv_estado": !sv.sv_estado, "us_codigo": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.sv_estado !== null && data.sv_estado !== undefined
              ) {
                this.vehicleRequests[i].sv_estado = data.sv_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachVehicleRequest += ' '; setTimeout(() => { this.seachVehicleRequest = this.seachVehicleRequest.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
