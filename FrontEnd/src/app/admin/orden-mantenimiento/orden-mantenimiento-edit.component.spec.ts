import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrdenMantenimientoEditComponent } from './orden-mantenimiento-edit.component';

describe('OrdenMantenimientoEditComponent', () => {
  let component: OrdenMantenimientoEditComponent;
  let fixture: ComponentFixture<OrdenMantenimientoEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OrdenMantenimientoEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OrdenMantenimientoEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
