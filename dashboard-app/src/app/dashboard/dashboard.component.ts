
import { Component, OnInit } from '@angular/core';
import { DashboardService } from '../services/dashboard.service';
import Chart from 'chart.js/auto';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { AuthService } from '../services/auth.service';

@Component({
    selector: 'app-dashboard',
    templateUrl: './dashboard.component.html',
    standalone: true,
    imports: [CommonModule, FormsModule]
})
export class DashboardComponent implements OnInit {
    totalPedidos = 0;
    clientesActivos = 0;

    constructor(private dashboardService: DashboardService, public auth: AuthService) { }

    logout() {
        this.auth.logout();
    }

    ngOnInit(): void {
        this.loadData();
    }

    loadData() {
        this.dashboardService.getDashboard().subscribe((resp: any) => {

            const data = resp.data;

            this.totalPedidos = data.total_pedidos;
            this.clientesActivos = data.clientes_activos;

            this.renderEstadoChart(data.pedidos_por_estado);
            this.renderDiaChart(data.pedidos_por_dia);
        });
    }

    renderEstadoChart(data: any[]) {

        const labels = data.map(d => d.estado);
        const values = data.map(d => d.total);

        new Chart('estadoChart', {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values
                }]
            }
        });
    }

    renderDiaChart(data: any[]) {

        const labels = data.map(d => d.fecha);
        const values = data.map(d => d.total);

        new Chart('diaChart', {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pedidos por día',
                    data: values
                }]
            }
        });
    }

    fechaDesde: string = '';
    fechaHasta: string = '';

    filtrar() {
        const params: any = {};

        if (this.fechaDesde) params.fecha_desde = this.fechaDesde;
        if (this.fechaHasta) params.fecha_hasta = this.fechaHasta;

        this.dashboardService.getDashboard(params).subscribe((resp: any) => {
            this.loadCharts(resp.data);
        });
    }

    loadCharts(data: any) {
        this.renderEstadoChart(data.pedidos_por_estado);
        this.renderDiaChart(data.pedidos_por_dia);
    }
}
