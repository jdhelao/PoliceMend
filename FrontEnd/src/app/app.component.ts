import { Component } from '@angular/core';

import { AccountService, UpdateService } from './_services';
import { User } from './_models';
import { interval, switchMap } from 'rxjs';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
/*
export class AppComponent {
  title = 'safied';
}*/
export class AppComponent {
  showNavBar = true;
  user?: User | null;

  constructor(private accountService: AccountService
    , private updateService: UpdateService
  ) {
    this.accountService.user.subscribe(x => this.user = x);
    /*
          const intervalTime = 4*60*1000;
          interval(intervalTime).pipe(switchMap(() => this.refreshData(Date().toString()))).subscribe(()=>{
            console.log('do something...');
          });

          this.updateProducts(); */
    this.updateEntities();
  }

  get_showNavBar(){
    return this.accountService.get_showNavBar();
  }

  logout() {
    this.accountService.logout();
  }

  async refreshData(token: string) {
    console.log("update db: " + token);
    console.log(navigator.onLine);
  };

  async updateEntities() {
    this.updateService.updateLocalAplicaciones();
    this.updateService.updateLocalDistritos();
    this.updateService.updateLocalCircuitos();
    this.updateService.updateLocalSubcircuitos();

    /*
    this.updateService.updateLocalUsuarios();
    this.updateService.updateLocalCiudades();
    this.updateService.updateLocalRangos();
    this.updateService.updateLocalPerfiles();
    this.updateService.updateLocalPersonal();
    */
  }

}
