import {Injectable} from "@angular/core";
import {BaseService} from "./base.service";
import {Http, URLSearchParams, Response} from "@angular/http";
import {Observable} from "rxjs";
import {PostRelation} from "../models/post-relation";

@Injectable()
export class PostRelationService implements BaseService {

    constructor(private http: Http) {
    }

    private prepare(postRelation: PostRelation): void {
        delete postRelation.id;
    }

    get(id: number): Observable<Response> {
        return this.http.get(`/api/post-relation/${id}`);
    }

    getAll(filters?: URLSearchParams): Observable<Response> {
        return this.http.get('/api/post-relation', {search: filters});
    }

    add(postRelation: PostRelation): Observable<Response> {
        this.prepare(postRelation);
        return this.http.post('/api/post-relation', { formFields: postRelation });
    }

    update(postRelation: PostRelation): Observable<Response> {
        let id = postRelation.id;
        this.prepare(postRelation);
        return this.http.put(`/api/post-relation/${id}`, { formFields: postRelation });
    }

    remove(postRelation: PostRelation): Observable<Response> {
        return this.http.delete(`/api/post-relation/${postRelation.id}`);
    }

}
