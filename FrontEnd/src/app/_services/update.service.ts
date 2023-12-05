import { Injectable } from '@angular/core';
import { GetDataService } from './get-data.service';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { Aplicacion, Update } from '@app/_models';
import { Observable } from 'rxjs/internal/Observable';
import { BehaviorSubject, Subject, from, mergeMap } from 'rxjs';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from '@environments/environment';

@Injectable({
  providedIn: 'root',
})
export class UpdateService {
  httpOptions = {
    headers: new HttpHeaders({
      'Content-Type': 'application/json' /*,
      Authorization: 'my-auth-token'*/,
    }),
  };


  private updateHomeAppsSource = new Subject<boolean>();

  // Observable string streams
  updateHomeApps$ = this.updateHomeAppsSource.asObservable();

  constructor(
    private dbService: NgxIndexedDBService,
    private http: HttpClient
  ) { }

  async getUsers() {
    /*
    this.http.get<any>(environment.urlAPI + 'usuarios.json').subscribe((data) => {
      console.log('hhhhhhh');
      console.log(data);
      console.log('jjjjjjj');
    });
    */
    return this.http.get<any>(environment.urlAPI + 'usuarios.json');
  }

  async updateLocalUsuarios() {
    this.http.get<any>(environment.urlAPI + 'usuarios.json').subscribe((data: any) => {
      if (data !== null && data !== undefined && data.usuarios !== null && data.usuarios !== undefined && data.usuarios.length > 0) {
        console.log('<usuarios>');
        //console.log(data.usuarios);
        this.dbService
          .bulkPut('usuarios', data.usuarios)
          .subscribe((result) => {
            console.log('result: ', result);
          });
        console.log('</usuarios>');
      }
    });
  }
  async updateLocalCiudades() {
    this.http.get<any>(environment.urlAPI + 'ciudades.json').subscribe((data: any) => {
      if (data !== null && data !== undefined && data.ciudades !== null && data.ciudades !== undefined && data.ciudades.length > 0) {
        console.log('<ciudades>');
        //console.log(data.ciudades);
        this.dbService
          .bulkPut('ciudades', data.ciudades)
          .subscribe((result) => {
            console.log('result: ', result);
          });
        console.log('</ciudades>');
      }
    });
  }
  async updateLocalRangos() {
    this.http.get<any>(environment.urlAPI + 'rangos.json').subscribe((data: any) => {
      if (data !== null && data !== undefined && data.rangos !== null && data.rangos !== undefined && data.rangos.length > 0) {
        console.log('<rangos>');
        //console.log(data.rangos);
        this.dbService
          .bulkPut('rangos', data.rangos)
          .subscribe((result) => {
            console.log('result: ', result);
          });
        console.log('</rangos>');
      }
    });
  }
  async updateLocalPerfiles() {
    this.http.get<any>(environment.urlAPI + 'perfiles.json').subscribe((data: any) => {
      if (data !== null && data !== undefined && data.perfiles !== null && data.perfiles !== undefined && data.perfiles.length > 0) {
        console.log('<perfiles>');
        //console.log(data.perfiles);
        this.dbService
          .bulkPut('perfiles', data.perfiles)
          .subscribe((result) => {
            console.log('result: ', result);
          });
        console.log('</perfiles>');
      }
    });
  }
  async updateLocalPersonal() {
    this.http.get<any>(environment.urlAPI + 'personal.json').subscribe((data: any) => {
      if (data !== null && data !== undefined && data.personal !== null && data.personal !== undefined && data.personal.length > 0) {
        console.log('<personal>');
        //console.log(data.personal);
        this.dbService
          .bulkPut('personal', data.personal)
          .subscribe((result) => {
            console.log('result: ', result);
          });
        console.log('</personal>');
      }
    });
  }

  async updateLocalAplicaciones____old() {
    if (navigator.onLine) {
      const us = new BehaviorSubject(JSON.parse(localStorage.getItem('user')!)).value;
      if (us !== null && us !== undefined && us.pf_codigo !== null && us.pf_codigo !== undefined && Number(us.pf_codigo) > 0) {
        //let us: any | null = this.accountService.userValue;
        console.log('perfillllll');

        this.dbService.clear('aplicaciones').subscribe((successDeleted) => { console.log('success-del-apps? ', successDeleted); });

        this.http.get<any>(environment.urlAPI + 'aplicacion-perfil/' + Number(us.pf_codigo).toString() + '.json').subscribe((data: any) => {
          if (data !== null && data !== undefined && data.aplicacionPerfil !== null && data.aplicacionPerfil !== undefined && data.aplicacionPerfil.length > 0) {
            console.log('<pefil-app>');
            console.log(data);

            data.aplicacionPerfil.forEach((pf: any) => {
              console.log(pf.ap_codigo);
              /***/
              this.http.get<any>(environment.urlAPI + 'aplicaciones/' + pf.ap_codigo + '.json').subscribe((dataApp: any) => {
                console.log(dataApp);
                if (dataApp !== null && dataApp !== undefined && dataApp.aplicacione !== null && dataApp.aplicacione !== undefined) {
                  console.log('<app>');
                  console.log(dataApp.aplicacione);
                  this.dbService.add('aplicaciones', dataApp.aplicacione).subscribe((key) => { console.log('key: ', key); });
                  console.log('</app>');
                  this.updateHomeAppsSource.next(true);
                }
              });

            });
            console.log('</pefil-app>');
          }
        });
      }
    } else { console.log('Sin Internet'); }
  }

  async updateLocalAplicaciones() {
    if (navigator.onLine) {
      const us = new BehaviorSubject(JSON.parse(localStorage.getItem('user')!)).value;
      if (us !== null && us !== undefined && us.pf_codigo !== null && us.pf_codigo !== undefined && Number(us.pf_codigo) > 0) {
        this.http.get<any>(environment.urlAPI + 'aplicaciones/perfil/' + Number(us.pf_codigo).toString()).subscribe((data: any) => {
          this.dbService.clear('aplicaciones').subscribe((successDeleted) => { console.log('success-del-apps? ', successDeleted); });
          //if (data !== null && data !== undefined && data.aplicaciones !== null && data.aplicaciones !== undefined && data.aplicaciones.length > 0) {
          if (data !== null && data !== undefined && data.length > 0) {
            console.log('<app>');
            //console.log(data);
            //this.dbService.bulkAdd('aplicaciones', data.aplicaciones).subscribe((apps) => { console.log('apps: ', apps); });
            this.dbService.bulkAdd('aplicaciones', data).subscribe((apps) => { console.log('apps: ', apps); });
            this.updateHomeAppsSource.next(true);
            console.log('</app>');
          }
        });
      }
    } else { console.log('Sin Internet'); }
  }

  async updateLocalDistritos() {
    if (navigator.onLine) {
      //this.http.get<any>(environment.urlAPI + 'distritos.json').subscribe((data: any) => {
      this.http.get<any>(environment.urlAPI + 'distritos').subscribe((data: any) => {
        //if (data !== null && data !== undefined && data.distritos !== null && data.distritos !== undefined && data.distritos.length > 0) {
        if (data !== null && data !== undefined && data.length > 0) {
          console.log('<distritos>');
          this.dbService
            //.bulkPut('distritos', data.distritos)
            .bulkPut('distritos', data)
            .subscribe((result) => {
              console.log('result: ', result);
            });
          console.log('</distritos>');
        }
      });
    } else { console.log('Sin Internet'); }
  }

  async updateLocalCircuitos() {
    if (navigator.onLine) {
      //this.http.get<any>(environment.urlAPI + 'circuitos.json').subscribe((data: any) => {
      this.http.get<any>(environment.urlAPI + 'circuitos').subscribe((data: any) => {
        //if (data !== null && data !== undefined && data.circuitos !== null && data.circuitos !== undefined && data.circuitos.length > 0) {
        if (data !== null && data !== undefined && data.length > 0) {
          console.log('<circuitos>');
          this.dbService
            //.bulkPut('circuitos', data.circuitos)
            .bulkPut('circuitos', data)
            .subscribe((result) => {
              console.log('result: ', result);
            });
          console.log('</circuitos>');
        }
      });
    } else { console.log('Sin Internet'); }
  }

  async updateLocalSubcircuitos() {
    if (navigator.onLine) {
      //this.http.get<any>(environment.urlAPI + 'subcircuitos.json').subscribe((data: any) => {
      this.http.get<any>(environment.urlAPI + 'subcircuitos').subscribe((data: any) => {
        //if (data !== null && data !== undefined && data.subcircuitos !== null && data.subcircuitos !== undefined && data.subcircuitos.length > 0) {
        if (data !== null && data !== undefined && data.length > 0) {
          console.log('<subcircuitos>');
          this.dbService
            //.bulkPut('subcircuitos', data.subcircuitos)\
            .bulkPut('subcircuitos', data)
            .subscribe((result) => {
              console.log('result: ', result);
            });
          console.log('</subcircuitos>');
        }
      });
    } else { console.log('Sin Internet'); }
  }


}
