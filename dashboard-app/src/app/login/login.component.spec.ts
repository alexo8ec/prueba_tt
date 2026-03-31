import { ComponentFixture, TestBed } from '@angular/core/testing';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { AuthService } from '../services/auth.service';
import { LoginComponent } from './login.component';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { of, throwError } from 'rxjs';

describe('LoginComponent', () => {
  let component: LoginComponent;
  let fixture: ComponentFixture<LoginComponent>;
  let authService: AuthService;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LoginComponent, HttpClientTestingModule, FormsModule, CommonModule],
      providers: [AuthService]
    }).compileComponents();
    fixture = TestBed.createComponent(LoginComponent);
    component = fixture.componentInstance;
    authService = TestBed.inject(AuthService);
    // Evita reload real en pruebas
    authService.reloadFn = () => {};
    fixture.detectChanges();
  });

  it('debería crearse', () => {
    expect(component).toBeTruthy();
  });

  it('debería llamar a AuthService.login y limpiar error en login exitoso', () => {
    spyOn(authService, 'login').and.returnValue(of({ token: 'abc123' }));
    component.email = 'test@mail.com';
    component.password = '1234';
    component.error = 'error previo';
    component.login();
    expect(authService.login).toHaveBeenCalledWith('test@mail.com', '1234');
    expect(component.error).toBe('');
    expect(component.loading).toBeFalse();
  });

  it('debería mostrar error si el login falla', () => {
    spyOn(authService, 'login').and.returnValue(throwError(() => ({ error: { message: 'Credenciales inválidas' } })));
    component.email = 'test@mail.com';
    component.password = '1234';
    component.login();
    expect(component.error).toBe('Credenciales inválidas');
    expect(component.loading).toBeFalse();
  });
});
