import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { HomeComponent } from '@app/home';
import { UsuarioListComponent } from './usuario/usuario-list.component';
import { UsuarioEditComponent } from './usuario/usuario-edit.component';

import { DistritoListComponent } from './distrito/distrito-list.component';
import { DistritoEditComponent } from './distrito/distrito-edit.component';
import { CircuitoListComponent } from './circuito/circuito-list.component';
import { CircuitoEditComponent } from './circuito/circuito-edit.component';
import { SubcircuitoListComponent } from './subcircuito/subcircuito-list.component';
import { SubcircuitoEditComponent } from './subcircuito/subcircuito-edit.component';

import { PersonalListComponent } from './personal/personal-list.component';
import { PersonalEditComponent } from './personal/personal-edit.component';

import { VehiculoListComponent } from './vehiculo/vehiculo-list.component';
import { VehiculoEditComponent } from './vehiculo/vehiculo-edit.component';

import { ContratoListComponent } from './contrato/contrato-list.component';
import { ContratoEditComponent } from './contrato/contrato-edit.component';

import { EntidadListComponent } from './entidad/entidad-list.component';
import { EntidadEditComponent } from './entidad/entidad-edit.component';

import { SVehicularListComponent } from './s-vehicular/s-vehicular-list.component';
import { SVehicularEditComponent } from './s-vehicular/s-vehicular-edit.component';
import { SVehicularAprobarListComponent } from './s-vehicular-aprobar/s-vehicular-aprobar-list.component';
import { SVehicularAprobarEditComponent } from './s-vehicular-aprobar/s-vehicular-aprobar-edit.component';
import { OrdenAbastecimientoListComponent } from './orden-abastecimiento/orden-abastecimiento-list.component';
import { OrdenAbastecimientoEditComponent } from './orden-abastecimiento/orden-abastecimiento-edit.component';
import { OrdenMantenimientoListComponent } from './orden-mantenimiento/orden-mantenimiento-list.component';
import { OrdenMantenimientoEditComponent } from './orden-mantenimiento/orden-mantenimiento-edit.component';
import { OrdenMovilizacionListComponent } from './orden-movilizacion/orden-movilizacion-list.component';
import { OrdenMovilizacionEditComponent } from './orden-movilizacion/orden-movilizacion-edit.component';

import { RSugerenciasComponent } from './r-sugerencias/r-sugerencias.component';
import { RAbastecimientosComponent } from './r-abastecimientos/r-abastecimientos.component';
import { RMantenimientosComponent } from './r-mantenimientos/r-mantenimientos.component';
import { RMovilizacionesComponent } from './r-movilizaciones/r-movilizaciones.component';

import { PerfilListComponent } from './perfil/perfil-list.component';
import { PerfilEditComponent } from './perfil/perfil-edit.component';

import { RepuestoListComponent } from './repuesto/repuesto-list.component';
import { RepuestoEditComponent } from './repuesto/repuesto-edit.component';

import { CatalogoListComponent } from './catalogo/catalogo-list.component';
import { CatalogoEditComponent } from './catalogo/catalogo-edit.component';

const routes: Routes = [
  { path: '', component: HomeComponent },

  { path: 'usuario', component: UsuarioListComponent },
  { path: 'usuario/list', component: UsuarioListComponent },
  { path: 'usuario/edit/:id', component: UsuarioEditComponent },
  { path: 'usuario/add', component: UsuarioEditComponent },

  { path: 'distrito', component: DistritoListComponent },
  { path: 'distrito/edit/:id', component: DistritoEditComponent },
  { path: 'distrito/add', component: DistritoEditComponent },
  { path: 'circuito', component: CircuitoListComponent },
  { path: 'circuito/edit/:id', component: CircuitoEditComponent },
  { path: 'circuito/add', component: CircuitoEditComponent },
  { path: 'subcircuito', component: SubcircuitoListComponent },
  { path: 'subcircuito/edit/:id', component: SubcircuitoEditComponent },
  { path: 'subcircuito/add', component: SubcircuitoEditComponent },


  { path: 'personal', component: PersonalListComponent },
  { path: 'personal/edit/:id', component: PersonalEditComponent },
  { path: 'personal/add', component: PersonalEditComponent },

  { path: 'vehiculo', component: VehiculoListComponent },
  { path: 'vehiculo/edit/:id', component: VehiculoEditComponent },
  { path: 'vehiculo/add', component: VehiculoEditComponent },

  { path: 'contrato', component: ContratoListComponent },
  { path: 'contrato/edit/:id', component: ContratoEditComponent },
  { path: 'contrato/add', component: ContratoEditComponent },

  { path: 'entidad', component: EntidadListComponent },
  { path: 'entidad/edit/:id', component: EntidadEditComponent },
  { path: 'entidad/add', component: EntidadEditComponent },

  { path: 'solicitud-vehicular', component: SVehicularListComponent },
  { path: 'solicitud-vehicular/edit/:id', component: SVehicularEditComponent },
  { path: 'solicitud-vehicular/add', component: SVehicularEditComponent },
  { path: 'aprobar-solicitud-vehicular', component: SVehicularAprobarListComponent },
  { path: 'aprobar-solicitud-vehicular/edit/:id', component: SVehicularAprobarEditComponent },

  { path: 'orden-abastecimiento', component: OrdenAbastecimientoListComponent },
  { path: 'orden-abastecimiento/edit/:id', component: OrdenAbastecimientoEditComponent },
  { path: 'orden-mantenimiento', component: OrdenMantenimientoListComponent },
  { path: 'orden-mantenimiento/edit/:id', component: OrdenMantenimientoEditComponent },
  { path: 'orden-movilizacion', component: OrdenMovilizacionListComponent },
  { path: 'orden-movilizacion/edit/:id', component: OrdenMovilizacionEditComponent },

  { path: 'reporte-abastecimiento', component: RAbastecimientosComponent },
  { path: 'reporte-mantenimiento', component: RMantenimientosComponent },
  { path: 'reporte-movilizacion', component: RMovilizacionesComponent },

  { path: 'perfil', component: PerfilListComponent },
  { path: 'perfil/edit/:id', component: PerfilEditComponent },
  { path: 'perfil/add', component: PerfilEditComponent },

  { path: 'repuesto', component: RepuestoListComponent },
  { path: 'repuesto/edit/:id', component: RepuestoEditComponent },
  { path: 'repuesto/add', component: RepuestoEditComponent },

  { path: 'catalogo', component: CatalogoListComponent },
  { path: 'catalogo/:type/edit/:id', component: CatalogoEditComponent },
  { path: 'catalogo/add', component: CatalogoEditComponent },

  { path: 'reporte-sugerencias', component: RSugerenciasComponent },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AdminRoutingModule { }
