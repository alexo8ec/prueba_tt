import { Component, Output, EventEmitter } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { AuthService } from '../services/auth.service';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css'
})
export class RegisterComponent {
  nombre = '';
  apellido = '';
  telefono = '';
  direccion = '';
  email = '';
  password = '';
  error = '';
  success = '';
  loading = false;

  @Output() registerSuccess = new EventEmitter<void>();
  @Output() cancel = new EventEmitter<void>();

  constructor(private http: HttpClient) {}

  register() {
    this.loading = true;
    this.error = '';
    this.success = '';
    const data = {
      nombre: this.nombre,
      apellido: this.apellido,
      telefono: this.telefono,
      direccion: this.direccion,
      email: this.email,
      password: this.password
    };
    this.http.post('https://localhost/api/auth/register', data).subscribe({
      next: () => {
        this.loading = false;
        this.success = 'Registro exitoso. Ahora puedes iniciar sesión.';
        setTimeout(() => this.registerSuccess.emit(), 1200);
      },
      error: (err: any) => {
        this.loading = false;
        this.error = err?.error?.message || 'Error al registrar usuario';
      }
    });
  }
}
