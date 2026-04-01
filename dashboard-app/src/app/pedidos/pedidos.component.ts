import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-pedidos',
  templateUrl: './pedidos.component.html',
  styleUrls: ['./pedidos.component.css'],
  standalone: true,
  imports: [FormsModule, CommonModule],
})
export class PedidosComponent {
  pedidos: { id: number; cliente: string; producto: string; cantidad: number; detalles?: string }[] = [
    { id: 1, cliente: 'Juan', producto: 'Producto A', cantidad: 2, detalles: 'Entrega urgente' },
    { id: 2, cliente: 'Ana', producto: 'Producto B', cantidad: 1, detalles: 'Pago contra entrega' }
  ];
  modalAbierto = false;
  nuevoPedido = { cliente: '', producto: '', cantidad: 1, detalles: '' };

  abrirModalAgregar() {
    this.modalAbierto = true;
  }

  cerrarModal() {
    this.modalAbierto = false;
    this.nuevoPedido = { cliente: '', producto: '', cantidad: 1, detalles: '' };
  }

  agregarPedido() {
    this.pedidos.push({ ...this.nuevoPedido, id: this.pedidos.length + 1 });
    this.cerrarModal();
  }
}
