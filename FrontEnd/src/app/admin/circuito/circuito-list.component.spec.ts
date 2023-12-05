import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CircuitoListComponent } from './circuito-list.component';

describe('CircuitoListComponent', () => {
  let component: CircuitoListComponent;
  let fixture: ComponentFixture<CircuitoListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CircuitoListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CircuitoListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
