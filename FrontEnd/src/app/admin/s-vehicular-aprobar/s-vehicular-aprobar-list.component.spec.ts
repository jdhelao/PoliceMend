import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SVehicularAprobarListComponent } from './s-vehicular-aprobar-list.component';

describe('SVehicularAprobarListComponent', () => {
  let component: SVehicularAprobarListComponent;
  let fixture: ComponentFixture<SVehicularAprobarListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SVehicularAprobarListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SVehicularAprobarListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
