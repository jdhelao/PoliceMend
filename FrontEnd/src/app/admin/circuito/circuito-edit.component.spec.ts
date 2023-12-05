import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CircuitoEditComponent } from './circuito-edit.component';

describe('CircuitoEditComponent', () => {
  let component: CircuitoEditComponent;
  let fixture: ComponentFixture<CircuitoEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CircuitoEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CircuitoEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
