import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { AuthService } from '../services/auth.service';
import { RegisterComponent } from '../register/register.component';


@Component({
    selector: 'app-login',
    standalone: true,
    imports: [CommonModule, FormsModule, RegisterComponent],
    templateUrl: './login.component.html',
    styleUrl: './login.component.css',
})
export class LoginComponent {
    email = '';
    password = '';
    error = '';
    loading = false;
    showRegister = false;

    constructor(public authService: AuthService) { }

    login() {
        this.loading = true;
        this.error = '';
        this.authService.login(this.email, this.password).subscribe({
            next: () => {
                this.loading = false;
                // El AuthService se encargará de redirigir o recargar
            },
            error: (err: any) => {
                this.loading = false;
                this.error = err?.error?.message || 'Error de autenticación';
            }
        });
    }

    onRegisterSuccess() {
        this.showRegister = false;
    }
}
