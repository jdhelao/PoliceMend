import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpResponse,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HTTP_INTERCEPTORS,
} from '@angular/common/http';
import { BehaviorSubject, Observable, of, throwError } from 'rxjs';
import { delay, materialize, dematerialize } from 'rxjs/operators';
import { GetDataService } from '@app/_services';
import { User } from '@app/_models';

// array in local storage for registered users
/*
const usersKey = 'angular-14-registration-login-example-users';
let users: any[] = JSON.parse(localStorage.getItem(usersKey)!) || [];
*/

@Injectable()
export class FakeBackendInterceptor implements HttpInterceptor {
  intercept(
    request: HttpRequest<any>,
    next: HttpHandler
  ): Observable<HttpEvent<any>> {
    const { url, method, headers, body } = request;

    return handleRoute();

    function handleRoute() {
      switch (true) {
        case url.endsWith('/users/authenticate') && method === 'POST':
          return authenticate();
        /*
        case url.endsWith('/users/register') && method === 'POST':
          return register();
        case url.endsWith('/users') && method === 'GET':
          return getUsers();
        case url.match(/\/users\/\d+$/) && method === 'GET':
          return getUserById();
        case url.match(/\/users\/\d+$/) && method === 'PUT':
          return updateUser();
        case url.match(/\/users\/\d+$/) && method === 'DELETE':
          return deleteUser();
  */
        default:
          // pass through any requests not handled above
          return next.handle(request);
      }
    }

    // route functions
    function authenticate() {
      const { username, password, ip } = body;

      console.log('jjj');
      console.log(body);
      console.log('kkk');
      /**/
      console.log("GGG");
      const DS = GetDataService.get_Login(username, password, ip);
      console.log(DS);
      console.log("HHH");
      /**/

      if (DS === null || DS === undefined) {
        return error(`Error en el Servidor`);
      }
      else if (DS.token !== null && DS.token !== undefined) {
          return ok({
            ...basicDetails(DS),
            token: DS.token,
          });
      }
      else {
        return error(`Credenciales no validas`);
      }
      /*
            const user = users.find(
              (x) => x.username === username && x.password === password
            );
            if (!user) return error(`Usuario o contraseña incorrecta`);
            return ok({
              ...basicDetails(user),
              token: 'fake-jwt-token',
            });
      */

    }


    /*
    function register() {
      const user = body;

      if (users.find((x) => x.username === user.username)) {
        return error('Username "' + user.username + '" is already taken');
      }

      user.id = users.length ? Math.max(...users.map((x) => x.id)) + 1 : 1;
      users.push(user);
      localStorage.setItem(usersKey, JSON.stringify(users));
      return ok();
    }

    function getUsers() {
      if (!isLoggedIn()) return unauthorized();
      return ok(users.map((x) => basicDetails(x)));
    }

    function getUserById() {
      if (!isLoggedIn()) return unauthorized();

      const user = users.find((x) => x.id === idFromUrl());
      return ok(basicDetails(user));
    }

    function updateUser() {
      if (!isLoggedIn()) return unauthorized();

      let params = body;
      let user = users.find((x) => x.id === idFromUrl());

      // only update password if entered
      if (!params.password) {
        delete params.password;
      }

      // update and save user
      Object.assign(user, params);
      localStorage.setItem(usersKey, JSON.stringify(users));

      return ok();
    }

    function deleteUser() {
      if (!isLoggedIn()) return unauthorized();

      users = users.filter((x) => x.id !== idFromUrl());
      localStorage.setItem(usersKey, JSON.stringify(users));
      return ok();
    }
*/

    // helper functions

    function ok(body?: any) {
      return of(new HttpResponse({ status: 200, body })).pipe(delay(500)); // delay observable to simulate server api call
    }

    function error(message: string) {
      return throwError(() => ({ error: { message } })).pipe(
        materialize(),
        delay(500),
        dematerialize()
      ); // call materialize and dematerialize to ensure delay even if an error is thrown (https://github.com/Reactive-Extensions/RxJS/issues/648);
    }

    function unauthorized() {
      return throwError(() => ({
        status: 401,
        error: { message: 'Unauthorized' },
      })).pipe(materialize(), delay(500), dematerialize());
    }

    function basicDetails(user: any) {
      /*const { id, username, firstName, lastName } = user;
      return { id, username, firstName, lastName };
      */
     /*
      const  { Profile, Code, Login, Name, TT, TI, Session } = user;
      return { Profile, Code, Login, Name, TT, TI, Session };*/
      return user;
    }

    function isLoggedIn() {
      try {
        console.log("ooo");
        const userSubject = new BehaviorSubject(JSON.parse(localStorage.getItem('user')!));
        const user: any/*User*/ = userSubject.value;
        console.log(user.token);
        console.log(headers.get('Authorization'));
        console.log("ppp")

        //return headers.get('Authorization') === 'Bearer fake-jwt-token';
        return headers.get('Authorization') === 'Bearer ' + user.token;
      } catch (e) {
        console.log(e);
        return false;
      }

    }

    function idFromUrl() {
      const urlParts = url.split('/');
      return parseInt(urlParts[urlParts.length - 1]);
    }
  }
}

export const fakeBackendProvider = {
  // use fake backend in place of Http service for backend-less development
  provide: HTTP_INTERCEPTORS,
  useClass: FakeBackendInterceptor,
  multi: true,
};
