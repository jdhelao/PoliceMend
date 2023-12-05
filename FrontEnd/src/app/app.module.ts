import { NgModule, isDevMode, Injector } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';

// used to create fake backend
import { fakeBackendProvider } from './_helpers';

import { AppRoutingModule } from './app-routing.module';
import { JwtInterceptor, ErrorInterceptor } from './_helpers';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { MatInputModule } from '@angular/material/input';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';

import { MatToolbarModule } from '@angular/material/toolbar';

import { ServiceWorkerModule } from '@angular/service-worker';
/*import { AlertComponent } from './_componets/alert.component';*/
import { AlertComponent } from './_components';
import { HomeComponent } from './home/home.component';

import { NgxIndexedDBModule, DBConfig } from 'ngx-indexed-db';

import { MatButtonModule } from '@angular/material/button';

const dbConfig: DBConfig = {
  name: 'db_policemend',
  version: 1,
  objectStoresMeta: [
    {
      store: 'updates',
      storeConfig: { keyPath: 'up_type', autoIncrement: false },
      storeSchema: [
        { name: 'up_name', keypath: 'up_name', options: { unique: false } },
        { name: 'up_date', keypath: 'up_date', options: { unique: false } }
      ]
    },
    {
      store: 'usuarios',
      storeConfig: { keyPath: 'us_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'us_login', keypath: 'us_login', options: { unique: false } },
        { name: 'pe_codigo', keypath: 'pe_codigo', options: { unique: false } },
        { name: 'pf_codigo', keypath: 'pf_codigo', options: { unique: false } },
        { name: 'us_password', keypath: 'us_password', options: { unique: false } },
        { name: 'us_estado', keypath: 'us_estado', options: { unique: false } }
      ]
    },
    {
      store: 'perfiles',
      storeConfig: { keyPath: 'pf_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'pf_nombre', keypath: 'pf_nombre', options: { unique: false } },
        { name: 'us_estado', keypath: 'us_estado', options: { unique: false } }
      ]
    },
    {
      store: 'ciudades',
      storeConfig: { keyPath: 'ci_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'pr_codigo', keypath: 'pr_codigo', options: { unique: false } },
        { name: 'ci_nombre', keypath: 'ci_nombre', options: { unique: false } },
        { name: 'ci_estado', keypath: 'ci_estado', options: { unique: false } }
      ]
    },
    {
      store: 'rangos',
      storeConfig: { keyPath: 'ra_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'ra_nombre', keypath: 'ra_nombre', options: { unique: false } },
        { name: 'ra_abreviacion', keypath: 'ra_abreviacion', options: { unique: false } },
        { name: 'ra_jerarquia', keypath: 'ra_jerarquia', options: { unique: false } },
        { name: 'ra_estado', keypath: 'ra_estado', options: { unique: false } }
      ]
    },
    {
      store: 'personal',
      storeConfig: { keyPath: 'pe_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'pe_dni', keypath: 'pe_dni', options: { unique: false } },
        { name: 'pe_nombre1', keypath: 'pe_nombre1', options: { unique: false } },
        { name: 'pe_nombre2', keypath: 'pe_nombre2', options: { unique: false } },
        { name: 'pe_apellido1', keypath: 'pe_apellido1', options: { unique: false } },
        { name: 'pe_apellido2', keypath: 'pe_apellido2', options: { unique: false } },
        { name: 'pe_sangre', keypath: 'pe_sangre', options: { unique: false } },

        { name: 'ci_codigo', keypath: 'ci_codigo', options: { unique: false } },
        { name: 'ra_codigo', keypath: 'ra_codigo', options: { unique: false } },

        { name: 'pe_estado', keypath: 'pe_estado', options: { unique: false } }
      ]
    },
    {
      store: 'aplicaciones',
      storeConfig: { keyPath: 'ap_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'ap_nombre', keypath: 'ap_nombre', options: { unique: false } },
        { name: 'ap_ruta', keypath: 'ap_ruta', options: { unique: false } },
        { name: 'ap_imagen', keypath: 'ap_imagen', options: { unique: false } },
        { name: 'ap_estado', keypath: 'ap_estado', options: { unique: false } }
      ]
    },
    {
      store: 'distritos',
      storeConfig: { keyPath: 'di_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'di_nombre', keypath: 'di_nombre', options: { unique: false } },
        { name: 'di_estado', keypath: 'di_estado', options: { unique: false } }
      ]
    },
    {
      store: 'circuitos',
      storeConfig: { keyPath: 'cc_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'di_codigo', keypath: 'di_codigo', options: { unique: false } },
        { name: 'cc_nombre', keypath: 'cc_nombre', options: { unique: false } },
        { name: 'cc_estado', keypath: 'cc_estado', options: { unique: false } }
      ]
    },
    {
      store: 'subcircuitos',
      storeConfig: { keyPath: 'sc_codigo', autoIncrement: false },
      storeSchema: [
        { name: 'cc_codigo', keypath: 'cc_codigo', options: { unique: false } },
        { name: 'sc_nombre', keypath: 'sc_nombre', options: { unique: false } },
        { name: 'sc_estado', keypath: 'sc_estado', options: { unique: false } }
      ]
    },


  ]
};

export let InjectorInstance: Injector;

@NgModule({
  declarations: [
    AppComponent,
    AlertComponent,
    HomeComponent
  ],
  imports: [
    BrowserModule,
    ReactiveFormsModule,
    FormsModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    HttpClientModule,
    MatInputModule,
    MatFormFieldModule,
    MatIconModule,
    MatButtonModule,
    MatToolbarModule,
    NgxIndexedDBModule.forRoot(dbConfig),
    ServiceWorkerModule.register('ngsw-worker.js', {
      enabled: !isDevMode(),
      // Register the ServiceWorker as soon as the application is stable
      // or after 30 seconds (whichever comes first).
      registrationStrategy: 'registerWhenStable:30000'
    })
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true },
    { provide: HTTP_INTERCEPTORS, useClass: ErrorInterceptor, multi: true },

    // provider used to create fake backend
    fakeBackendProvider
  ],

  bootstrap: [AppComponent]
})
export class AppModule {
  constructor(private injector: Injector) {
    InjectorInstance = this.injector;
  }
}
