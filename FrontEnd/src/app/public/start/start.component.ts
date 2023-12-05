import { Component } from '@angular/core';
import { AccountService } from '@app/_services';

@Component({
  selector: 'app-start',
  templateUrl: './start.component.html',
  styleUrls: ['./start.component.scss']
})
export class StartComponent {
  constructor(private accountService: AccountService ) { this.accountService.set_showNavBar(false); }
}
