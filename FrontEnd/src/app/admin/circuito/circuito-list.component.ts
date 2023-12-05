import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Circuito } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-circuito-list',
  templateUrl: './circuito-list.component.html',
  styleUrls: ['./circuito-list.component.scss']
})
export class CircuitoListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public circuits: Circuito[] | any[] = [];
  public seachCircuit: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(3);
    this.getCircuitList();
  }

  async getCircuitList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'circuitos/all').subscribe((data: Circuito | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.circuits = data;
      }
      this.loading = false;
    });
  }

  get filterCircuits() {
    const fo = new IgxFilterOptions();
    fo.key = ['cc_codigo', 'cc_nombre'];
    fo.inputValue = this.seachCircuit;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.circuits.forEach((us, i) => {
      if (us.cc_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'circuitos'
            , { "cc_codigo": us.cc_codigo, "cc_estado": !us.cc_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.cc_estado !== null && data.cc_estado !== undefined
              ) {
                this.circuits[i].cc_estado = data.cc_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachCircuit += ' '; setTimeout(() => { this.seachCircuit = this.seachCircuit.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
