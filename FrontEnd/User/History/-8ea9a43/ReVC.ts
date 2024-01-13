import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RespuestoListComponent } from './repuesto-list.component';

describe('RespuestoListComponent', () => {
  let component: RespuestoListComponent;
  let fixture: ComponentFixture<RespuestoListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RespuestoListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RespuestoListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
