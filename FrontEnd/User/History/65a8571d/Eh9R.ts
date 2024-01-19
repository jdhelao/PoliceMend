import { formatDate } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { OrdenMantenimiento, Personal, SolicitudVehicular, Vehiculo } from '@app/_models';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';

@Component({
  selector: 'app-r-mantenimientos',
  templateUrl: './r-mantenimientos.component.html',
  styleUrls: ['./r-mantenimientos.component.scss']
})
export class RMantenimientosComponent {

}
