import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Distrito } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-distrito-list',
  templateUrl: './distrito-list.component.html',
  styleUrls: ['./distrito-list.component.scss']
})
export class DistritoListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public districts: Distrito[] | any[] = [];
  public seachDistrict: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(2);
    this.getdDistrictList();
  }

  async getdDistrictList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'distritos/all').subscribe((data: Distrito | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.districts = data;
      }
      this.loading = false;
    });
  }

  get filterDistricts() {
    const fo = new IgxFilterOptions();
    fo.key = ['di_codigo', 'di_nombre'];
    fo.inputValue = this.seachDistrict;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.districts.forEach((us, i) => {
      if (us.di_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'distritos'
            , { "di_codigo": us.di_codigo, "di_estado": !us.di_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.di_estado !== null && data.di_estado !== undefined
              ) {
                this.districts[i].di_estado = data.di_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachDistrict += ' '; setTimeout(() => { this.seachDistrict = this.seachDistrict.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
