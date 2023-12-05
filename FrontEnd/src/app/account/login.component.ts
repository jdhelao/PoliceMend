import { Component, ElementRef, OnInit, Renderer2, ViewEncapsulation } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { first } from 'rxjs/operators';
import { Observable, firstValueFrom, throwError } from 'rxjs';

import { AccountService, AlertService, GetDataService, UpdateService } from '@app/_services';
import { AlertOptions } from '@app/_models';
import { NgxIndexedDBService } from 'ngx-indexed-db';

@Component({
  /*selector: 'app-login',*/
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginComponent implements OnInit {
  form!: FormGroup;
  loading = false;
  submitted = false;
  loginValid = true;
  //dan: any /*any [][][]*/ = [];
  ip: string | undefined = '';

  constructor(
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private accountService: AccountService,
    private alertService: AlertService,
    private GetDataService: GetDataService,
    private dbService: NgxIndexedDBService,
    private updateService: UpdateService
  ) { }

  async ngOnInit() {
    this.form = this.formBuilder.group({
      username: ['', Validators.required],
      password: ['', Validators.required],
    });
    /*document.body.style.backgroundColor = '#5d5d5dbf';

        const dan = await this.GetDataService.dataSet(32, "3,'1'", 1, true);
        console.log(dan);

        const ip = await this.GetDataService.get_PublicIP();
        console.log('xxx');
        console.log(ip);
        this.ip = ip;
        console.log('zzz');

        console.log('mmm');
        let ii: String | undefined = 'Banana';
        ii = this.GetDataService.get_IP();
        console.log(ii);
        console.log('nnn');

    */
    /*
        this.dbService
          .bulkAdd('people2', [
            {
              name: `charles number ${Math.random() * 10}`,
              email: `email number ${Math.random() * 10}`,
            },
            {
              name: `charles number ${Math.random() * 10}`,
              email: `email number ${Math.random() * 10}`,
            },
          ])
          .subscribe((result: any) => {
            console.log('result: ', result);
          });

    */

  }

  // convenience getter for easy access to form fields
  get f() {
    return this.form.controls;
  }

  onSubmit() {
    // reset alerts on submit
    this.alertService.clear();

    // stop here if form is invalid
    if (this.form.invalid) {
      return;
    }



    //this.dbService.clear('menu').subscribe((successDeleted) => { console.log('success? ', successDeleted); });
    if (navigator.onLine) {
      this.submitted = true;
      this.loginValid = true;
      this.loading = true;

      this.accountService
        .login(this.f.username.value, this.f.password.value, this.ip)
        .pipe(first())
        .subscribe({
          next: (us) => {
            console.log('www');
            console.log(us);
            console.log('eee');
            this.loginValid = true;
            /*
                      if (us.Session !== null && us.Session !== undefined) {
                        const mnu = GetDataService.get_DataSet(2454, `0, '${us.Session}', 1`, 1, true);
                        if (mnu !== null && mnu !== undefined && mnu[0].length > 0) {
                          this.dbService
                            .bulkAdd('menu', mnu[0])
                            .subscribe((result: any) => {
                            });
                        }
                      }*/

            this.updateService.updateLocalAplicaciones();

            // get return url from query parameters or default to home page
            const returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/home';
            this.router.navigateByUrl(returnUrl);
          },
          error: (error) => {
            this.alertService.error(error);
            this.loading = false;
            this.loginValid = false;
          },
        });
    } else { this.alertService.warn('Sin Conexión', { autoClose: true }); }
  }
}
