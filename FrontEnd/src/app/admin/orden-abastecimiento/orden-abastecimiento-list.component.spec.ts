import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrdenAbastecimientoListComponent } from './orden-abastecimiento-list.component';

describe('OrdenAbastecimientoListComponent', () => {
  let component: OrdenAbastecimientoListComponent;
  let fixture: ComponentFixture<OrdenAbastecimientoListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OrdenAbastecimientoListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OrdenAbastecimientoListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
