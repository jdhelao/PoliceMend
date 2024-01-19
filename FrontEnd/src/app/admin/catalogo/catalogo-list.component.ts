import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Catalogo } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-catalogo-list',
  templateUrl: './catalogo-list.component.html',
  styleUrls: ['./catalogo-list.component.scss']
})
export class CatalogoListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public catalogType: Catalogo[] = [];
  public catalogList: Catalogo[] = [];
  public seachCatalog: string = '';
  public selectedType: number = 0;

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(9);
    this.getCatalogTypes();
  }

  async getCatalogTypes() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'catalogos').subscribe({
      next: (data: Catalogo | any) => {
        if (data !== null && data !== undefined && data.length > 0) {
          this.catalogType = data;
        }
      },
      error: (error) => {
        this.alertService.error('Hubo un error', { autoClose: true });
        this.loading = false;
      },
      complete: () => {
        this.loading = false;
      }
    });
  }

  async getCatalogList($type: number = this.selectedType) {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'catalogos/' + $type + '/objeto/all').subscribe({
      next: (data: Catalogo | any) => {
        if (data !== null && data !== undefined && data.length > 0) {
          this.catalogList = data;
        }
      },
      error: (error) => {
        this.alertService.error('Hubo un error', { autoClose: true });
        this.loading = false;
      },
      complete: () => {
        this.loading = false;
      }
    });
  }

  get filterCatalogs() {
    const fo = new IgxFilterOptions();
    fo.key = ['ca_codigo', 'ca_nombre'];
    fo.inputValue = this.seachCatalog;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.catalogList.forEach((ca, i) => {
      if (ca.ca_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'catalogos/' + this.selectedType + '/objeto'
            , { "ca_codigo": ca.ca_codigo, "ca_nombre": ca.ca_nombre, "ca_estado": !ca.ca_estado, "us_codigo": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.ca_estado !== null && data.ca_estado !== undefined
              ) {
                this.catalogList[i].ca_estado = data.ca_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachCatalog += ' '; setTimeout(() => { this.seachCatalog = this.seachCatalog.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
