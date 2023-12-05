import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DistritoListComponent } from './distrito-list.component';

describe('DistritoListComponent', () => {
  let component: DistritoListComponent;
  let fixture: ComponentFixture<DistritoListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DistritoListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(DistritoListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
