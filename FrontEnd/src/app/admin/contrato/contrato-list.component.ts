import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Contrato } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-contrato-list',
  templateUrl: './contrato-list.component.html',
  styleUrls: ['./contrato-list.component.scss']
})
export class ContratoListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public contracts: Contrato[] | any[] = [];
  public seachContract: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(7);
    this.getdContractList();
  }

  async getdContractList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'contratos/all').subscribe((data: Contrato | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.contracts = data;
      }
    });
    this.loading = false;
  }

  get filterContracts() {
    const fo = new IgxFilterOptions();
    fo.key = ['ko_documento'];
    fo.inputValue = this.seachContract;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.contracts.forEach((us, i) => {
      if (us.ko_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'contratos'
            , { "ko_codigo": us.ko_codigo, "ko_estado": !us.ko_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.ko_estado !== null && data.ko_estado !== undefined
              ) {
                this.contracts[i].ko_estado = data.ko_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachContract += ' '; setTimeout(() => { this.seachContract = this.seachContract.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
