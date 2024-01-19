import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RMovilizacionesComponent } from './r-movilizaciones.component';

describe('RMovilizacionesComponent', () => {
  let component: RMovilizacionesComponent;
  let fixture: ComponentFixture<RMovilizacionesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RMovilizacionesComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RMovilizacionesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
