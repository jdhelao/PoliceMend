import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RespuestoEditComponent } from './repuesto-edit.component';

describe('RespuestoEditComponent', () => {
  let component: RespuestoEditComponent;
  let fixture: ComponentFixture<RespuestoEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RespuestoEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RespuestoEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
