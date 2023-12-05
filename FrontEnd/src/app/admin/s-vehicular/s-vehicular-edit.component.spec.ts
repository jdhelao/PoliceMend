import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SVehicularEditComponent } from './s-vehicular-edit.component';

describe('SVehicularEditComponent', () => {
  let component: SVehicularEditComponent;
  let fixture: ComponentFixture<SVehicularEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SVehicularEditComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SVehicularEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
