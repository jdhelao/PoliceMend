import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { MatInputModule } from '@angular/material/input';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatSelectModule } from '@angular/material/select';
import { MatTableModule } from '@angular/material/table';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatSliderModule } from '@angular/material/slider';
import { MatCardModule } from '@angular/material/card';
import {MatProgressBarModule} from '@angular/material/progress-bar';


import {
  IgxIconModule,
  IgxBottomNavModule,
  IgxAvatarModule,
  IgxFilterModule,
  IgxListModule,
  IgxInputGroupModule,
  IgxButtonGroupModule,
  IgxRippleModule,
  IgxSwitchModule
} from "igniteui-angular";
import { NgApexchartsModule } from 'ng-apexcharts';

import { AdminRoutingModule } from './admin-routing.module';

import { UsuarioComponent } from './usuario/usuario.component';
import { UsuarioListComponent } from './usuario/usuario-list.component';
import { UsuarioEditComponent } from './usuario/usuario-edit.component';

import { PersonalComponent } from './personal/personal.component';
import { PersonalListComponent } from './personal/personal-list.component';
import { PersonalEditComponent } from './personal/personal-edit.component';
import { RSugerenciasComponent } from './r-sugerencias/r-sugerencias.component';
import { DistritoListComponent } from './distrito/distrito-list.component';
import { DistritoEditComponent } from './distrito/distrito-edit.component';
import { CircuitoListComponent } from './circuito/circuito-list.component';
import { CircuitoEditComponent } from './circuito/circuito-edit.component';
import { SubcircuitoListComponent } from './subcircuito/subcircuito-list.component';
import { SubcircuitoEditComponent } from './subcircuito/subcircuito-edit.component';
import { VehiculoListComponent } from './vehiculo/vehiculo-list.component';
import { VehiculoEditComponent } from './vehiculo/vehiculo-edit.component';
import { ContratoEditComponent } from './contrato/contrato-edit.component';
import { ContratoListComponent } from './contrato/contrato-list.component';
import { EntidadListComponent } from './entidad/entidad-list.component';
import { EntidadEditComponent } from './entidad/entidad-edit.component';
import { SVehicularListComponent } from './s-vehicular/s-vehicular-list.component';
import { SVehicularEditComponent } from './s-vehicular/s-vehicular-edit.component';
import { SVehicularAprobarListComponent } from './s-vehicular-aprobar/s-vehicular-aprobar-list.component';
import { SVehicularAprobarEditComponent } from './s-vehicular-aprobar/s-vehicular-aprobar-edit.component';
import { PerfilEditComponent } from './perfil/perfil-edit.component';
import { PerfilListComponent } from './perfil/perfil-list.component';
import { OrdenAbastecimientoEditComponent } from './orden-abastecimiento/orden-abastecimiento-edit.component';
import { OrdenAbastecimientoListComponent } from './orden-abastecimiento/orden-abastecimiento-list.component';
import { RAbastecimientosComponent } from './r-abastecimientos/r-abastecimientos.component';
import { OrdenMantenimientoListComponent } from './orden-mantenimiento/orden-mantenimiento-list.component';
import { OrdenMantenimientoEditComponent } from './orden-mantenimiento/orden-mantenimiento-edit.component';
import { RepuestoEditComponent } from './repuesto/repuesto-edit.component';
import { RepuestoListComponent } from './repuesto/repuesto-list.component';

@NgModule({
  declarations: [
    UsuarioComponent, UsuarioListComponent, UsuarioEditComponent,
    DistritoListComponent, DistritoEditComponent,
    CircuitoListComponent, CircuitoEditComponent,
    CircuitoListComponent, CircuitoEditComponent,
    SubcircuitoListComponent, SubcircuitoEditComponent,
    PersonalComponent, PersonalListComponent, PersonalEditComponent, RSugerenciasComponent, DistritoListComponent, DistritoEditComponent, CircuitoListComponent, CircuitoEditComponent, SubcircuitoListComponent, SubcircuitoEditComponent, VehiculoListComponent, VehiculoEditComponent, ContratoEditComponent, ContratoListComponent, EntidadListComponent, EntidadEditComponent, SVehicularListComponent, SVehicularEditComponent, PerfilEditComponent, PerfilListComponent, SVehicularAprobarListComponent, SVehicularAprobarEditComponent, OrdenAbastecimientoEditComponent, OrdenAbastecimientoListComponent, RAbastecimientosComponent, OrdenMantenimientoListComponent, OrdenMantenimientoEditComponent, RepuestoEditComponent, RepuestoListComponent
  ],
  imports: [
    CommonModule,
    AdminRoutingModule,

    MatButtonModule, MatIconModule,

    FormsModule, ReactiveFormsModule, FormsModule, MatFormFieldModule, MatInputModule, MatAutocompleteModule, MatToolbarModule, MatSelectModule, MatTableModule, MatCheckboxModule, MatSliderModule, MatCardModule, MatProgressBarModule,

    IgxAvatarModule,
    IgxFilterModule,
    IgxListModule,
    IgxInputGroupModule,
    IgxButtonGroupModule,
    IgxRippleModule,
    IgxSwitchModule,
    IgxIconModule,
    IgxBottomNavModule,
    /*IgxDatePickerModule,
    IgxDateRangePickerModule,*/
    NgApexchartsModule,

    MatDatepickerModule,
    MatNativeDateModule,
    MatIconModule,
  ]
})
export class AdminModule { }
