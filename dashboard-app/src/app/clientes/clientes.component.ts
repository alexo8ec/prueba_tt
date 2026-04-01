import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ClientesService, Cliente } from '../services/clientes.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-clientes',
  templateUrl: './clientes.component.html',
  styleUrls: ['./clientes.component.css'],
  standalone: true,
  imports: [FormsModule, CommonModule]
})
export class ClientesComponent implements OnInit {
  clientes: Cliente[] = [];
  modalAbierto = false;
  editando = false;
  clienteSeleccionado: Cliente | null = null;
  nuevoCliente: Cliente = { nombre: '', apellido: '', email: '', telefono: '' };

  constructor(private clientesService: ClientesService) {}

  ngOnInit() {
    this.cargarClientes();
  }

  cargarClientes() {
    this.clientesService.getClientes().subscribe(clientes => {
      this.clientes = clientes;
    });
  }

  abrirModalAgregar() {
    this.editando = false;
    this.nuevoCliente = { nombre: '', apellido: '', email: '', telefono: '' };
    this.modalAbierto = true;
  }

  abrirModalEditar(cliente: Cliente) {
    this.editando = true;
    this.clienteSeleccionado = cliente;
    this.nuevoCliente = { ...cliente };
    this.modalAbierto = true;
  }

  cerrarModal() {
    this.modalAbierto = false;
    this.nuevoCliente = { nombre: '', apellido: '', email: '', telefono: '' };
    this.clienteSeleccionado = null;
  }

  guardarCliente() {
    if (this.editando && this.clienteSeleccionado && this.clienteSeleccionado.id) {
      this.clientesService.actualizarCliente(this.clienteSeleccionado.id, this.nuevoCliente).subscribe(() => {
        this.cargarClientes();
        this.cerrarModal();
      });
    } else {
      this.clientesService.crearCliente(this.nuevoCliente).subscribe(() => {
        this.cargarClientes();
        this.cerrarModal();
      });
    }
  }

  eliminarCliente(cliente: Cliente) {
    if (cliente.id && confirm('¿Seguro que deseas eliminar este cliente?')) {
      this.clientesService.eliminarCliente(cliente.id).subscribe(() => {
        this.cargarClientes();
      });
    }
  }
}
