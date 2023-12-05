import { ComponentFixture, TestBed } from '@angular/core/testing';

import { VehiculoEditComponent } from './vehiculo-edit.component';

describe('VehiculoEditComponent', () => {
  let component: VehiculoEditComponent;
  let fixture: ComponentFixture<VehiculoEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ VehiculoEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(VehiculoEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
