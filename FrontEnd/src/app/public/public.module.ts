import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatInputModule } from '@angular/material/input';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatSelectModule } from '@angular/material/select';

import { PublicRoutingModule } from './public-routing.module';
import { StartComponent } from './start/start.component';
import { SugerenciaComponent } from './sugerencia/sugerencia.component';


@NgModule({
  declarations: [
    StartComponent,
    SugerenciaComponent
  ],
  imports: [
    CommonModule,
    PublicRoutingModule,

    FormsModule, ReactiveFormsModule,

    MatIconModule, MatButtonModule, MatInputModule, MatFormFieldModule, MatAutocompleteModule, MatToolbarModule, MatSelectModule
  ]
})
export class PublicModule { }
