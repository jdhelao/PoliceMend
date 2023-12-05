import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SubcircuitoEditComponent } from './subcircuito-edit.component';

describe('SubcircuitoEditComponent', () => {
  let component: SubcircuitoEditComponent;
  let fixture: ComponentFixture<SubcircuitoEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SubcircuitoEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SubcircuitoEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
