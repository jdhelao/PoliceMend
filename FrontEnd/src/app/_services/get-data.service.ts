import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { User } from '@app/_models';
import { BehaviorSubject, from } from 'rxjs';

import {
  Observable,
  Observer,
  ReplaySubject,
  firstValueFrom,
  lastValueFrom,
  throwError,
} from 'rxjs';
import { retry, catchError, map } from 'rxjs/operators';
import { environment } from '@environments/environment';
import { InjectorInstance } from '@app/app.module';
import { Md5 } from 'ts-md5';

@Injectable({
  providedIn: 'root',
})
export class GetDataService {

  constructor(private http: HttpClient) { }
  // Http Headers
  httpOptions = {
    headers: new HttpHeaders({
      'Content-Type': 'application/json' /*,
      Authorization: 'my-auth-token'*/,
    }),
  };




  /**
   *
   * @returns Synchronous Static function to order get DataSet (ARRAY) from headquarter server, Sample: const DS = GetDataService.get_DataSet(32, "3,'1'", 1, true);
   */
  static get_Login(
    login: string,
    password: string,
    ip: string = ''
  ) {
    try {
      let usr: any = {};
      const us = new XMLHttpRequest();
      us.open('GET', 'http://localhost/cakePoliceMend/usuarios.json', false);
      us.send();
      const us_res = JSON.parse(us.response);

      //console.log(us_res);
      if (us_res !== null && us_res !== undefined && us_res.usuarios !== null && us_res.usuarios !== undefined && us_res.usuarios.length) {
        for (let u of us_res.usuarios) {
          /*
          if (u.pf_codigo == pf.pf_codigo) {
            us.pf_nombre = pf.pf_nombre;
          }*/
          if (login == Md5.hashStr(u.us_login) && login + password == u.us_password && u.us_estado) {
            usr.pe_codigo = u.pe_codigo;
            usr.pf_codigo = u.pf_codigo;
            usr.us_codigo = u.us_codigo;
            usr.us_login = u.us_login;

            usr.token = Md5.hashStr(u.pe_codigo+u.pf_codigo+u.us_codigo+u.us_login);

            const pe = new XMLHttpRequest();
            pe.open('GET', 'http://localhost/cakePoliceMend/personal/'+Number(u.pe_codigo).toString()+'.json', false);
            pe.send();
            const pe_res = JSON.parse(pe.response);

            if (pe_res !== null && pe_res !== undefined && pe_res.personal !== null && pe_res.personal !== undefined && pe_res.personal.pe_codigo !== null && pe_res.personal.pe_codigo !== undefined) {
              //console.log(pe_res);
              usr.pe_dni = pe_res.personal.pe_dni;
              usr.pe_nombre1 = pe_res.personal.pe_nombre1;
              usr.pe_nombre2 = pe_res.personal.pe_nombre2;
              usr.pe_apellido1 = pe_res.personal.pe_apellido1;
              usr.pe_apellido2 = pe_res.personal.pe_apellido2;
              usr.pe_sangre = pe_res.personal.pe_sangre;
              usr.pe_fnacimiento = pe_res.personal.pe_fnacimiento;
              usr.ci_codigo = pe_res.personal.ci_codigo;
              usr.ra_codigo = pe_res.personal.ra_codigo;
            }
            //console.log(usr);
          }
        }
      }
      return usr;

    } catch (e) {
      console.log(e);
      return null;
    }
  }





  /**
   *
   * @returns Asynchronous  function to order get Public IP, Sample: const ip = await this.GetDataService.getPublicIp();
   */
  async get_PublicIP(): Promise<string> {
    try {
      const url = 'https://api.ipify.org/?format=json';
      const response = await firstValueFrom(this.http.get(url)); //.toPromise();
      const json = JSON.parse(JSON.stringify(response));
      return json.ip;
    } catch (e) {
      return '';
    }
  }
  /**
   *
   * @returns Synchronous function to order get Public IP, Sample: const ip = this.GetDataService.getIP();
   */
  get_IP() {
    try {
      const xhr = new XMLHttpRequest();
      xhr.open('GET', environment.urlIP, false);
      xhr.send();
      const response = JSON.parse(xhr.response);
      return response.ip;
    } catch (e) {
      console.log(e);
      return '';
    }
  }
  /**
   *
   * @returns Synchronous Static function to order get Public IP, Sample: const ip = this.GetDataService.getIP();
   */
  static get_IP() {
    try {
      const xhr = new XMLHttpRequest();
      xhr.open('GET', environment.urlIP, false);
      xhr.send();
      const response = JSON.parse(xhr.response);
      return response.ip;
    } catch (e) {
      console.log(e);
      return '';
    }
  }


  // Error handling
  errorHandl(error: any) {
    let errorMessage = '';
    if (error.error instanceof ErrorEvent) {
      // Get client-side error
      errorMessage = error.error.message;
    } else {
      // Get server-side error
      errorMessage = `Error Code: ${error.status}\nMessage: ${error.message}`;
    }
    console.log(errorMessage);
    return throwError(() => {
      return errorMessage;
    });
  }
}
