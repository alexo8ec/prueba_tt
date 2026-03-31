import { TestBed } from '@angular/core/testing';
import { DashboardService } from './dashboard.service';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { AuthService } from './auth.service';

describe('DashboardService', () => {
  let service: DashboardService;
  let httpMock: HttpTestingController;
  let authService: AuthService;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [DashboardService, AuthService]
    });
    service = TestBed.inject(DashboardService);
    httpMock = TestBed.inject(HttpTestingController);
    authService = TestBed.inject(AuthService);
    spyOn(authService, 'getToken').and.returnValue('test-token');
  });

  it('debería obtener datos del dashboard usando el token', () => {
    service.getDashboard().subscribe();
    const req = httpMock.expectOne(r => r.url.includes('/api/v1/dashboard'));
    expect(req.request.method).toBe('GET');
    expect(req.request.headers.get('Authorization')).toBe('Bearer test-token');
    req.flush({ data: {} });
  });

  afterEach(() => {
    httpMock.verify();
  });
});
