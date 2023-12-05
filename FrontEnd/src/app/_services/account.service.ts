import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, firstValueFrom } from 'rxjs';
import { map } from 'rxjs/operators';
import { Md5 } from 'ts-md5';
import { SHA256 } from 'crypto-js';

import { environment } from '@environments/environment';
import { User } from '@app/_models';
import { GetDataService } from './get-data.service';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { UpdateService } from './update.service';
import { AppRoutingModule } from '@app/app-routing.module';

@Injectable({ providedIn: 'root' })
export class AccountService {
    private userSubject: BehaviorSubject<User | null>;
    public user: Observable<User | null>;
    public showNavBar = true;

    constructor(
        private router: Router,
        private http: HttpClient,
        private dbService: NgxIndexedDBService
    ) {
        this.userSubject = new BehaviorSubject(JSON.parse(localStorage.getItem('user')!));
        this.user = this.userSubject.asObservable();
    }

    public get userValue() {
        return this.userSubject.value;
    }

    public set_showNavBar(show: boolean = true) {
        this.showNavBar = show;
    }
    public get_showNavBar() {
        return this.showNavBar;
    }

    login(username: string, password: string, ip?: string) {
        //console.log({ username,password});
        username = Md5.hashStr(username);
        password = SHA256(password).toString();
        //console.log({ username,password});
        /*
        return this.http.post<User>(`${environment.urlAP}/users/authenticate`, { username, password, ip })
            .pipe(map(user => {
                // store user details and jwt token in local storage to keep user logged in between page refreshes
                localStorage.setItem('user', JSON.stringify(user));
                this.userSubject.next(user);
                return user;
            }));
            */
        //return this.http.get<User>('http://127.0.0.1:8000/api/login/de1823459264ee88e404db3fa21233be/8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92')
        return this.http.get<User>(`${environment.urlAPI}login/${username}/${password}`)
        .pipe(map(user => {
            // store user details and jwt token in local storage to keep user logged in between page refreshes
            console.log('zxcv');
            console.log(user);
            localStorage.setItem('user', JSON.stringify(user));
            this.userSubject.next(user);
            return user;
        }));
    }

    logout() {
        //  remove menu options
        this.dbService.clear('aplicaciones').subscribe((successDeleted) => { console.log('aplicaciones deleted ', successDeleted); });
        // remove user from local storage and set current user to null
        localStorage.removeItem('user');
        this.userSubject.next(null);
        this.router.navigate(['/account/login']);
    }

    checkAppPermission(app_ID: number = 0) {
        if (app_ID > 0) {
            /*
            this.dbService.getByKey('aplicaciones', app_ID).subscribe((ap) => {
                console.log(ap);
                if (ap !== null && ap !== undefined) {
                    return true;
                }
                else {
                    return false;
                }
              });
              */
            /*const app = await firstValueFrom(this.dbService.getByKey('aplicaciones', app_ID));
            console.log(app);
            return true;*/
        }

        this.dbService.getByKey('aplicaciones', app_ID).subscribe((ap) => {
            console.log(ap);
            if (ap !== null && ap !== undefined) {
                return;
            }
            else {
                return this.router.navigateByUrl('/');;
            }
        });
    }

    register(user: User) {
        return this.http.post(`${environment.urlAP}/users/register`, user);
    }

    getAll() {
        return this.http.get<User[]>(`${environment.urlAP}/users`);
    }

    getById(id: number) {
        return this.http.get<User>(`${environment.urlAP}/users/${id}`);
    }

    update(id: number, params: any) {
        return this.http.put(`${environment.urlAP}/users/${id}`, params)
            .pipe(map(x => {
                // update stored user if the logged in user updated their own record
                if (id == this.userValue?.id) {
                    // update local storage
                    const user = { ...this.userValue, ...params };
                    localStorage.setItem('user', JSON.stringify(user));

                    // publish updated user to subscribers
                    this.userSubject.next(user);
                }
                return x;
            }));
    }

    delete(id: string) {
        return this.http.delete(`${environment.urlAP}/users/${id}`)
            .pipe(map(x => {
                // auto logout if the logged in user deleted their own record
                if (id == this.userValue?.id) {
                    this.logout();
                }
                return x;
            }));
    }
}
