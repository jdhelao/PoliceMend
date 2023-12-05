import { Component, OnInit } from '@angular/core';

import { NgxIndexedDBService } from 'ngx-indexed-db';
import { DisplayDensity, IgxFilterOptions } from 'igniteui-angular';
import { User,  Personal } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { HttpClient } from '@angular/common/http';
import { environment } from '@environments/environment';

@Component({
  selector: 'app-personal-list',
  templateUrl: './personal-list.component.html',
  styleUrls: ['./personal-list.component.scss']
})
export class PersonalListComponent implements OnInit {
  public user: User | null;
  loading = false;
  public density: DisplayDensity = 'comfortable';
  public lsPeople: Personal[] | any[] = [];
  public searchPerson: string = '';

  constructor(private dbService: NgxIndexedDBService, private accountService: AccountService, private http: HttpClient, private alertService: AlertService,) {
    this.user = this.accountService.userValue;
  }

  public ngOnInit() {
    this.accountService.checkAppPermission(5);
    this.getPeopleList();
  }

  async getPeopleList() {
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'personas/all').subscribe((data: Personal | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsPeople = data;
      }
      this.loading = false;
    });
  }

  get filterPeople() {
    const fo = new IgxFilterOptions();
    fo.key = ['pe_dni', 'pe_nombre1', 'pe_nombre2', 'pe_apellido1', 'pe_apellido2', 'pe_sangre'];
    fo.inputValue = this.searchPerson;
    return fo;
  }

  public mousedown(event: Event) {
    event.stopPropagation();
  }

  disable(id: number) {
    const index: number = -1;
    this.lsPeople.forEach((us, i) => {
      if (us.pe_codigo == id) {
        console.log(id);
        if (navigator.onLine) {
          this.http.put<any>(environment.urlAPI + 'personas'
            , { "pe_codigo": us.pe_codigo, "pe_estado": !us.pe_estado, "updated_by": this.user?.us_codigo }).subscribe((data: any) => {
              if (data !== null && data !== undefined
                && data.pe_estado !== null && data.pe_estado !== undefined
              ) {
                this.lsPeople[i].pe_estado = data.pe_estado;
              }
            },
              error => {
                console.log(error);
              },
              () => {
                /*Refresh list*/
                this.searchPerson += ' '; setTimeout(() => { this.searchPerson = this.searchPerson.trim(); }, 1);
              }
            );
        } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
        return;
      }
    });
  }

}
