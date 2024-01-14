import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RepuestoEditComponent } from './repuesto-edit.component';

describe('RepuestoEditComponent', () => {
  let component: RepuestoEditComponent;
  let fixture: ComponentFixture<RepuestoEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RepuestoEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RepuestoEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
