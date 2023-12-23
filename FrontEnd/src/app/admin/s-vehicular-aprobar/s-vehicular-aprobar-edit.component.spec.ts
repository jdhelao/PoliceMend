import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SVehicularAprobarEditComponent } from './s-vehicular-aprobar-edit.component';

describe('SVehicularAprobarEditComponent', () => {
  let component: SVehicularAprobarEditComponent;
  let fixture: ComponentFixture<SVehicularAprobarEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SVehicularAprobarEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SVehicularAprobarEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
