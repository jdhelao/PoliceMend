import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrdenAbastecimientoEditComponent } from './orden-abastecimiento-edit.component';

describe('OrdenAbastecimientoEditComponent', () => {
  let component: OrdenAbastecimientoEditComponent;
  let fixture: ComponentFixture<OrdenAbastecimientoEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OrdenAbastecimientoEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OrdenAbastecimientoEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
