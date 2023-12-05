import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RSugerenciasComponent } from './r-sugerencias.component';

describe('RSugerenciasComponent', () => {
  let component: RSugerenciasComponent;
  let fixture: ComponentFixture<RSugerenciasComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RSugerenciasComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RSugerenciasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
