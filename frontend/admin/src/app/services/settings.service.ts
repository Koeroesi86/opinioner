import {Injectable} from "@angular/core";
import {BaseService} from "./base.service";
import {Http, URLSearchParams, Response} from "@angular/http";
import {Observable} from "rxjs";
import {Option} from "../models/option";

@Injectable()
export class SettingsService implements BaseService {

    constructor(private http: Http) {
    }

    private prepare(option: Option): void {
        delete option.id;
    }

    get(id: number): Observable<Response> {
        return this.http.get(`/api/settings/${id}`);
    }

    getAll(filters?: URLSearchParams): Observable<Response> {
        return this.http.get('/api/settings', {search: filters});
    }

    add(option: Option): Observable<Response> {
        this.prepare(option);
        return this.http.post('/api/settings', { formFields: option });
    }

    update(option: Option): Observable<Response> {
        let id = option.id;
        this.prepare(option);
        return this.http.put(`/api/settings/${id}`, { formFields: option });
    }

    remove(option: Option): Observable<Response> {
        return this.http.delete(`/api/post-relation/${option.id}`);
    }

}
