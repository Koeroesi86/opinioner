import {Injectable} from '@angular/core';
import {Response, URLSearchParams} from "@angular/http";
import {Observable} from "rxjs";

@Injectable()
export abstract class BaseService {

    abstract get(content: any): Observable<Response>;

    abstract getAll(filters?: URLSearchParams): Observable<Response>;

    abstract add(content: any): Observable<Response>;

    abstract update(content: any): Observable<Response>;

    abstract remove(content: any): Observable<Response>;

}
