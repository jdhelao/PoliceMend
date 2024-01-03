import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrdenMantenimientoListComponent } from './orden-mantenimiento-list.component';

describe('OrdenMantenimientoListComponent', () => {
  let component: OrdenMantenimientoListComponent;
  let fixture: ComponentFixture<OrdenMantenimientoListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OrdenMantenimientoListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OrdenMantenimientoListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
