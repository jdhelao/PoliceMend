import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SubcircuitoListComponent } from './subcircuito-list.component';

describe('SubcircuitoListComponent', () => {
  let component: SubcircuitoListComponent;
  let fixture: ComponentFixture<SubcircuitoListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SubcircuitoListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SubcircuitoListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
