import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Subcircuito } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-subcircuito-list',
  templateUrl: './subcircuito-list.component.html',
  styleUrls: ['./subcircuito-list.component.scss']
})
export class SubcircuitoListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public circuits: Subcircuito[] | any[] = [];
  public seachSubcircuit: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(4);
    this.getdSubcircuitList();
  }

  async getdSubcircuitList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'subcircuitos/all').subscribe((data: Subcircuito | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.circuits = data;
      }
      this.loading = false;
    });
  }

  get filterSubcircuits() {
    const fo = new IgxFilterOptions();
    fo.key = ['sc_codigo', 'sc_nombre'];
    fo.inputValue = this.seachSubcircuit;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.circuits.forEach((us, i) => {
      if (us.sc_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'subcircuitos'
            , { "sc_codigo": us.sc_codigo, "sc_estado": !us.sc_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.sc_estado !== null && data.sc_estado !== undefined
              ) {
                this.circuits[i].sc_estado = data.sc_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachSubcircuit += ' '; setTimeout(() => { this.seachSubcircuit = this.seachSubcircuit.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
