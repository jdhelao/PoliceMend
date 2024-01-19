import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrdenMovilizacionEditComponent } from './orden-movilizacion-edit.component';

describe('OrdenMovilizacionEditComponent', () => {
  let component: OrdenMovilizacionEditComponent;
  let fixture: ComponentFixture<OrdenMovilizacionEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OrdenMovilizacionEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OrdenMovilizacionEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
