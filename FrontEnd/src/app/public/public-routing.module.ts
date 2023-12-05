import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { StartComponent } from './start/start.component';
import { SugerenciaComponent } from './sugerencia/sugerencia.component';

const routes: Routes = [
  { path: '', component: StartComponent },
  { path: 'inicio', component: StartComponent },
  { path: 'sugerencias', component: SugerenciaComponent }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PublicRoutingModule { }
