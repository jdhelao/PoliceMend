import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DistritoEditComponent } from './distrito-edit.component';

describe('DistritoEditComponent', () => {
  let component: DistritoEditComponent;
  let fixture: ComponentFixture<DistritoEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DistritoEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(DistritoEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
