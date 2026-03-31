import { TestBed } from '@angular/core/testing';
import { AuthService } from './auth.service';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';

describe('Flujo completo de login', () => {
  let service: AuthService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [AuthService]
    });
    service = TestBed.inject(AuthService);
    httpMock = TestBed.inject(HttpTestingController);
    service.reloadFn = () => {}; // Evita reload real
  });

  it('debería autenticar y guardar el token usando credenciales reales', (done) => {
    service.login('alexo8ec@hotmail.com', '0921605895').subscribe(resp => {
      expect(resp.token).toBeDefined();
      expect(localStorage.getItem('token')).toBe(resp.token);
      done();
    });
    const req = httpMock.expectOne('https://localhost:7128/api/auth/login');
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({ email: 'alexo8ec@hotmail.com', password: '0921605895' });
    // Simula respuesta real
    req.flush({ token: 'token-de-ejemplo' });
  });

  afterEach(() => {
    httpMock.verify();
  });
});
