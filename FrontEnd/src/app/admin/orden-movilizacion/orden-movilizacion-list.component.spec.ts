import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrdenMovilizacionListComponent } from './orden-movilizacion-list.component';

describe('OrdenMovilizacionListComponent', () => {
  let component: OrdenMovilizacionListComponent;
  let fixture: ComponentFixture<OrdenMovilizacionListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OrdenMovilizacionListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OrdenMovilizacionListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
