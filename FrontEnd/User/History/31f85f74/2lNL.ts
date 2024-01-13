import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Repuesto } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-respuesto-list',
  templateUrl: './repuesto-list.component.html',
  styleUrls: ['./repuesto-list.component.scss']
})
export class RepuestoListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public lsSpareParts: Repuesto[] | any[] = [];
  public seachSparePart: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(8);
    this.getSparePartList();
  }

  async getSparePartList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'repuestos/all').subscribe((data: Repuesto | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsSpareParts = data;
      }
    });
    this.loading = false;
  }

  get filterSpareParts() {
    const fo = new IgxFilterOptions();
    fo.key = ['re_nombre'];
    fo.inputValue = this.seachSparePart;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.lsSpareParts.forEach((re, i) => {
      if (re.re_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'repuestos'
            , { "re_codigo": re.re_codigo, "re_estado": !re.re_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.re_estado !== null && data.re_estado !== undefined
              ) {
                this.lsSpareParts[i].re_estado = data.re_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachSparePart += ' '; setTimeout(() => { this.seachSparePart = this.seachSparePart.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
