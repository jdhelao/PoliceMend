import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SVehicularListComponent } from './s-vehicular-list.component';

describe('SVehicularListComponent', () => {
  let component: SVehicularListComponent;
  let fixture: ComponentFixture<SVehicularListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SVehicularListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SVehicularListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
