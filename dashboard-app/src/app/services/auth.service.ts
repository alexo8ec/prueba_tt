import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AuthService {
  // Permite sobreescribir el reload en pruebas
  reloadFn = () => window.location.reload();
  private apiUrl = 'https://localhost:7128/api/auth/login';

  constructor(private http: HttpClient) {}

  login(email: string, password: string): Observable<any> {
    return this.http.post(this.apiUrl, { email, password }).pipe(
      tap((resp: any) => {
        console.log('Respuesta de login:', resp);
        localStorage.setItem('token', resp.token);
        this.reloadFn(); // recarga para que el guard muestre el dashboard
      })
    );
  }

  logout() {
    localStorage.removeItem('token');
    this.reloadFn();
  }

  isLoggedIn(): boolean {
    return !!localStorage.getItem('token');
  }

  getToken(): string | null {
    return localStorage.getItem('token');
  }
}
