import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Perfil } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-perfil-list',
  templateUrl: './perfil-list.component.html',
  styleUrls: ['./perfil-list.component.scss']
})
export class PerfilListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public profiles: Perfil[] | any[] = [];
  public seachProfile: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(16);
    this.getProfileList();
  }

  async getProfileList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'perfiles/all').subscribe((data: Perfil | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.profiles = data;
        // remove option "Ninguno"
        console.log('ninguno');
        console.log(this.profiles[0]);
        if (this.profiles.length > 0 && this.profiles[0] !== undefined) { this.profiles.splice(0, 1); }
      }
    });
    this.loading = false;
  }

  get filterProfiles() {
    const fo = new IgxFilterOptions();
    fo.key = ['pf_nombre'];
    fo.inputValue = this.seachProfile;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.profiles.forEach((pf, i) => {
      if (pf.pf_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'perfiles'
            , { "pf_codigo": pf.pf_codigo, "pf_estado": !pf.pf_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.pf_estado !== null && data.pf_estado !== undefined
              ) {
                this.profiles[i].pf_estado = data.pf_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.seachProfile += ' '; setTimeout(() => { this.seachProfile = this.seachProfile.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
