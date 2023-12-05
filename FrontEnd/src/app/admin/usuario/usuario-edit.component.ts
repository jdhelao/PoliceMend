import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { User, Usuario } from '@app/_models';
import { Perfil } from '@app/_models/perfil';
import { Personal } from '@app/_models/personal';
import { AccountService, AlertService } from '@app/_services';
import { environment } from '@environments/environment';
import { NgxIndexedDBService } from 'ngx-indexed-db';
import { first } from 'rxjs';

@Component({
  selector: 'app-usuario-edit',
  templateUrl: './usuario-edit.component.html',
  styleUrls: ['./usuario-edit.component.scss']
})
export class UsuarioEditComponent implements OnInit {
  public user: User | null;
  form!: FormGroup;
  id?: number;
  title!: string;
  loading = false;
  submitting = false;
  submitted = false;

  lsPeople: Personal[] = [];
  lsProfiles: Perfil[] = [];

  constructor(
    private http: HttpClient,
    private dbService: NgxIndexedDBService,
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private accountService: AccountService,
    private alertService: AlertService
  ) { this.accountService.set_showNavBar(false); this.user = this.accountService.userValue; }

  ngOnInit() {
    this.accountService.checkAppPermission(1);
    this.getPeopleList();
    this.getProfilesList();

    this.id = this.route.snapshot.params['id'];

    // form with validation rules
    this.form = this.formBuilder.group({
      us_codigo: [null, (this.id ? Validators.required : null)],
      us_login: ['', [Validators.required, Validators.minLength(4), Validators.maxLength(50), Validators.pattern("^[a-z0-9]*$")]],
      pe_codigo: [null/*, Validators.required*/],
      pf_codigo: [null, Validators.required],

      // password only required in add mode
      us_password: ['', [Validators.minLength(6), ...(!this.id ? [Validators.required, Validators.minLength(6), Validators.maxLength(50)] : [])]]
    });

    this.title = 'Crear Usuario';
    if (this.id) {
      // edit mode
      this.title = 'Editar Usuario';
      this.loading = true;
      /*
      this.dbService.getByKey('usuarios', Number(this.id)).subscribe((user: Usuario|any) => {
        user.us_password = '';
        this.form.patchValue(user as Usuario);
        this.loading = false;
      });*/
      this.http.get<any>(environment.urlAPI + 'usuarios/' + this.id)
        .pipe(first())
        .subscribe(
          {
            next: (data: Usuario | any) => {
              if (data !== null && data !== undefined && data.us_codigo !== null && data.us_codigo !== undefined) {
                this.form.patchValue(data as Usuario);
                this.loading = false;
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.loading = false;
              this.router.navigateByUrl('admin/usuario');
            }
          }
        );
    }

  }

  async getPeopleList() {
    /*
    this.dbService.getAll('personal').subscribe((result: Personal | any) => {
      this.lsPeople = result;
    });*/
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'personas').subscribe((data: Personal | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsPeople = data;
      }
    });
    this.loading = false;
  }
  async getProfilesList() {
    /*
    this.dbService.getAll('perfiles').subscribe((result: Perfil | any) => {
      this.lsProfiles = result;
    });*/
    this.loading = true;
    this.http.get<any>(environment.urlAPI + 'perfiles').subscribe((data: Usuario | any) => {
      if (data !== null && data !== undefined && data.length > 0) {
        this.lsProfiles = data;
      }
    });
    this.loading = false;
  }

  ngOnDestroy() {
    this.accountService.set_showNavBar(true);
  }

  // convenience getter for easy access to form fields
  get f() { return this.form.controls; }


  onSubmit() {

    // reset alerts on submit
    this.alertService.clear();

    this.submitted = true;

    // stop here if form is invalid
    if (this.form.invalid) {
      return;
    }

    if (navigator.onLine) {

      this.submitting = true;

      this.form.value.created_by = this.user?.us_codigo;
      this.form.value.updated_by = this.user?.us_codigo;
      (this.form.value.us_codigo ?
        this.http.put<any>(environment.urlAPI + 'usuarios', this.form.value) :
        this.http.post<any>(environment.urlAPI + 'usuarios', this.form.value))
        .pipe(first())
        .subscribe(
          {
            next: (data: Usuario | any) => {
              if (data !== null && data !== undefined && data.us_codigo !== null && data.us_codigo !== undefined) {
                this.form.patchValue(data as Usuario);
                this.title = 'Editar Usuario';
                this.alertService.success('Usuario #' + data.us_codigo + ' guardado', { autoClose: true, keepAfterRouteChange: true });
                this.submitting = false;
                //this.router.navigateByUrl('admin/usuario');
              }
            },
            error: (error) => {
              this.alertService.error(error, { autoClose: true, keepAfterRouteChange: true });
              this.submitting = false;
            }
          }
        );
    } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }

    /*
    this.saveUser()
      .pipe(first())
      .subscribe({
        next: () => {
          this.alertService.success('User saved', { keepAfterRouteChange: true });
          this.router.navigateByUrl('/users');
        },
        error: error => {
          this.alertService.error(error);
          this.submitting = false;
        }
      });*/
  }

  private saveUser() {
    // create or update user based on id param
    return this.id
      ? this.accountService.update(this.id!, this.form.value)
      : this.accountService.register(this.form.value);
  }


}
