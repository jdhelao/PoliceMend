import { Component, OnInit } from '@angular/core';
import { Aplicacion, User, Usuario } from '@app/_models';
import { Personal } from '@app/_models/personal';
import { AccountService, UpdateService } from '@app/_services';
import { NgxIndexedDBService } from 'ngx-indexed-db';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  user: Session | null;
  menu: Aplicacion[] = [];
  searchFilter: String = '';
  clearSearch_hidden: boolean = true;

  constructor(private accountService: AccountService, private dbService: NgxIndexedDBService, private updateService: UpdateService) {
    this.user = this.accountService.userValue;
    this.accountService.set_showNavBar(true);
    this.updateService.updateHomeApps$.subscribe(res => {
      if (res !== null && res !== undefined && res == true) { this.filterMenu(); }
    })
  }

  ngOnInit(): void {
    this.filterMenu();
  }

  clearSearch() {
    this.searchFilter = '';
    this.filterMenu();
  }

  filterMenu() {
    console.log(this.searchFilter);

    if (this.searchFilter.trim() == '') {
      this.clearSearch_hidden = true;
      this.dbService.getAll('aplicaciones').subscribe((result: any) => { this.menu = result; });
    }
    else {
      this.clearSearch_hidden = false;
      this.dbService.getAll('aplicaciones').subscribe((result: any) => {
        console.log(result);
        this.menu = [];
        result.forEach((opt: Aplicacion) => {
          if (opt.ap_nombre?.toLowerCase().includes(this.searchFilter.toString().toLowerCase())) {
            this.menu.push(opt);
          }
        });
      });
    }



  }




}

interface Session extends Usuario, Personal { }
