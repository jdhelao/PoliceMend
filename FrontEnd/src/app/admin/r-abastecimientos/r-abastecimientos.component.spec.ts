import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RAbastecimientosComponent } from './r-abastecimientos.component';

describe('RAbastecimientosComponent', () => {
  let component: RAbastecimientosComponent;
  let fixture: ComponentFixture<RAbastecimientosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RAbastecimientosComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RAbastecimientosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
