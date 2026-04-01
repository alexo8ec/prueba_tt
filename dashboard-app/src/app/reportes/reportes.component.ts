import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
    selector: 'app-reportes',
    templateUrl: './reportes.component.html',
    styleUrls: ['./reportes.component.css'],
    standalone: true,
    imports: [FormsModule]
})
export class ReportesComponent {
    busqueda = '';
    resultados: { nombre: string; dato: number }[] = [];

    buscar() {
        // Aquí se implementaría la lógica de búsqueda
        this.resultados = [{ nombre: 'Ejemplo', dato: 123 }];
    }
}
