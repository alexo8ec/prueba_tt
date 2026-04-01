import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { AuthService } from './auth.service';

@Injectable({
    providedIn: 'root'
})
export class DashboardService {

    private apiUrl = '/crm/api/v1/dashboard';

    constructor(private http: HttpClient, private auth: AuthService) { }

    getDashboard(params: any = {}) {
        const token = this.auth.getToken();
        let headers = new HttpHeaders();
        if (token) {
            headers = headers.set('Authorization', 'Bearer ' + token);
        }
        return this.http.get(this.apiUrl, { params, headers });
    }
}
