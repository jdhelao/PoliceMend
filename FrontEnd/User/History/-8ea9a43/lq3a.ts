import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RepuestoListComponent } from './repuesto-list.component';

describe('RepuestoListComponent', () => {
  let component: RepuestoListComponent;
  let fixture: ComponentFixture<RepuestoListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RepuestoListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RepuestoListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
