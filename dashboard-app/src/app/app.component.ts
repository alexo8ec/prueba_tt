import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { AuthService } from './services/auth.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet, LoginComponent],
  template: `
    <app-login *ngIf="!authService.isLoggedIn()"></app-login>
    <router-outlet *ngIf="authService.isLoggedIn()"></router-outlet>
  `,
  styleUrl: './app.component.css'
})
export class AppComponent {
  constructor(public authService: AuthService) {}
}
