import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User,  Vehiculo } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-vehiculo-list',
  templateUrl: './vehiculo-list.component.html',
  styleUrls: ['./vehiculo-list.component.scss']
})
export class VehiculoListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public lsVehicles: Vehiculo[] | any[] = [];
  public searchVehicle: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(6);
    this.getVehiclesList();
  }

  async getVehiclesList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'vehiculos/all').subscribe((data: Vehiculo | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsVehicles = data;
      }
    });
    this.loading = false;
  }

  get filterVehicles() {
    const fo = new IgxFilterOptions();
    fo.key = ['ve_placa', 've_chasis', 've_motor', 've_apellido1', 've_apellido2', 've_sangre'];
    fo.inputValue = this.searchVehicle;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.lsVehicles.forEach((us, i) => {
      if (us.ve_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'vehiculos'
            , { "ve_codigo": us.ve_codigo, "ve_estado": !us.ve_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.ve_estado !== null && data.ve_estado !== undefined
              ) {
                this.lsVehicles[i].ve_estado = data.ve_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.searchVehicle += ' '; setTimeout(() => { this.searchVehicle = this.searchVehicle.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
