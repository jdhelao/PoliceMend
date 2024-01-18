import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RMantenimientosComponentComponent } from './r-mantenimientos.component';

describe('RMantenimientosComponentComponent', () => {
  let component: RMantenimientosComponentComponent;
  let fixture: ComponentFixture<RMantenimientosComponentComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RMantenimientosComponentComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RMantenimientosComponentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
