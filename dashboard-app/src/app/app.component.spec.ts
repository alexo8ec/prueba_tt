import { TestBed } from '@angular/core/testing';
import { AppComponent } from './app.component';
import { AuthService } from './services/auth.service';
import { RouterTestingModule } from '@angular/router/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { LoginComponent } from './login/login.component';

describe('AppComponent', () => {
  let authService: AuthService;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AppComponent, RouterTestingModule, HttpClientTestingModule],
      providers: [AuthService]
    }).compileComponents();
    authService = TestBed.inject(AuthService);
    // Evita reload real en pruebas
    authService.reloadFn = () => {};
  });

  it('debería renderizar el login si no hay sesión', () => {
    spyOn(authService, 'isLoggedIn').and.returnValue(false);
    const fixture = TestBed.createComponent(AppComponent);
    fixture.detectChanges();
    const compiled = fixture.nativeElement as HTMLElement;
    expect(compiled.querySelector('app-login')).toBeTruthy();
  });

  it('debería renderizar el dashboard si hay sesión', () => {
    spyOn(authService, 'isLoggedIn').and.returnValue(true);
    const fixture = TestBed.createComponent(AppComponent);
    fixture.detectChanges();
    const compiled = fixture.nativeElement as HTMLElement;
    expect(compiled.querySelector('router-outlet')).toBeTruthy();
  });
});
