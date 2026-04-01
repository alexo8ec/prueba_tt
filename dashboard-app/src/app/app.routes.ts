import { Routes } from '@angular/router';
import { DashboardComponent } from './dashboard/dashboard.component';
import { ClientesComponent } from './clientes/clientes.component';
import { PedidosComponent } from './pedidos/pedidos.component';

export const routes: Routes = [
  { path: '', component: DashboardComponent },
  { path: 'clientes', component: ClientesComponent },
  { path: 'pedidos', component: PedidosComponent }
];
