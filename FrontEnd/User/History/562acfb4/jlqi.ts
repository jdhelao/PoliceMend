import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RMantenimientosComponent } from './r-mantenimientos.component';

describe('RMantenimientosComponent', () => {
  let component: RMantenimientosComponent;
  let fixture: ComponentFixture<RMantenimientosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RMantenimientosComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RMantenimientosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
