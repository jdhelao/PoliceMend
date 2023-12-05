import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Entidad } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-entidad-list',
  templateUrl: './entidad-list.component.html',
  styleUrls: ['./entidad-list.component.scss']
})
export class EntidadListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public entities: Entidad[] | any[] = [];
  public seachEntity: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(13);
    this.getdEntityList();
  }

  async getdEntityList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'entidades/all').subscribe((data: Entidad | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.entities = data;
      }
    });
    this.loading = false;
  }

  get filterEntities() {
    const fo = new IgxFilterOptions();
    fo.key = ['en_nombre','en_franquicia','en_especialidad','di_nombre','en_representante','kt_nombre','en_plus_code'];
    fo.inputValue = this.seachEntity;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.entities.forEach((us, i) => {
      if (us.en_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'entidades'
            , { "en_codigo": us.en_codigo, "en_estado": !us.en_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.en_estado !== null && data.en_estado !== undefined
              ) {
                this.entities[i].en_estado = data.en_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachEntity += ' '; setTimeout(() => { this.seachEntity = this.seachEntity.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
