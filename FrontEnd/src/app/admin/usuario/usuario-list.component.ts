import { Component } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User, Usuario } from '@app/_models';
import { Perfil } from '@app/_models/perfil';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-usuario-list',
  templateUrl: './usuario-list.component.html',
  styleUrls: ['./usuario-list.component.scss']
})
export class UsuarioListComponent {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public users: Usuario[] | any[] = [];
  public profiles: Perfil[] = [];
  public searchUser: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(1);
    this.getUserList();
    this.getProfileist();
  }

  async getUserList() {
    /*
    this.dbService.getAll('usuarios').subscribe((us: Usuario | any) => {
      this.users = us;
    });*/
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'usuarios/all').subscribe((data: Usuario | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.users = data;
      }
      this.loading = false;
    });
  }
  async getProfileist() {
    /*
    this.dbService.getAll('perfiles').subscribe((pf: Perfil | any) => {
      this.profiles = pf;
      this.addProfileUser();
    });*/
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'perfiles').subscribe((data: Perfil | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.profiles = data;
        this.addProfileUser();
      }
      this.loading = false;
    });
  }
  async addProfileUser() {
    for (let us of this.users) {
      for (let pf of this.profiles) {
        if (us.pf_codigo == pf.pf_codigo) {
          us.pf_nombre = pf.pf_nombre;
        }
      }
    }
  }

  get filterUsers() {
    const fo = new IgxFilterOptions();
    fo.key = ['us_login'];
    fo.inputValue = this.searchUser;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable_old(id: number) {
    const index: number = -1;
    this.users.forEach((us, i) => {
      if (us.us_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.patch<any>(environment.urlAPI + 'usuarios/' + us.us_codigo + '.json'
            , { "us_estado": !us.us_estado }).subscribe((data: any) => {
              console.log(data);
              if (data !== null && data !== undefined
                && data.status !== null && data.status !== undefined && Number(data.status) == 1
                && data.usuario !== null && data.usuario !== undefined
              ) {
                this.dbService
                  .update('usuarios', data.usuario)
                  .subscribe((storeData) => {
                    console.log('usuarios: ', storeData);
                  });
              }
            },
              error => {
                console.log(error);
              },
              () => {
                this.getUserList();
              }
            );
        }
        else {
          console.log('NO INTERNET');
          this.getUserList();
        }
        return;
      }
    });

  }

  disable(id: number) {
    const index: number = -1;
    this.users.forEach((us, i) => {
      if (us.us_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'usuarios'
            , { "us_codigo": us.us_codigo, "us_estado": !us.us_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.us_estado !== null && data.us_estado !== undefined
              ) {
                this.users[i].us_estado = data.us_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*this.getUserList();*/
                /*Refresh list*/
                this.searchUser += ' '; setTimeout(() => { this.searchUser = this.searchUser.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
