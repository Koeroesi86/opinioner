import { Injectable } from "@angular/core";
import { Http, Response, URLSearchParams, RequestOptionsArgs } from "@angular/http";
import { BaseService } from "./base.service";
import { Observable } from "rxjs";
import { Post } from "../models/post";
import { isNumeric } from "rxjs/internal-compatibility";
import { AdminService } from "./admin.service";

@Injectable()
export class PostService implements BaseService {

  constructor(private http: Http,
              private adminService: AdminService) {
  }

  private prepare(post: Post): void {
    delete post.id;
  }

  get(content: any): Observable<Response> {
    if (isNumeric(content)) {
      return this.http.get(`/api/post/${content}`);
    } else {
      return this.http.get(`/api/post/na?uri=${content}`);
    }
  }

  getAll(filters?: URLSearchParams): Observable<Response> {
    return this.http.get('/api/post', {search: filters});
  }

  add(post: Post): Observable<Response> {
    return this.http.post('/api/post', {formFields: post});
  }

  update(post: Post): Observable<Response> {
    let id = post.id;
    this.prepare(post);
    return this.http.put(`/api/post/${id}`, {formFields: post});
  }

  remove(post: Post): Observable<Response> {
    return this.http.delete(`/api/post/${post.id}`);
  }

  removeAll(filters: URLSearchParams): Observable<Response> {
    const options = {} as RequestOptionsArgs;
    options.params = filters;
    return this.http.delete('/api/post/na', options);
  }

  createSlug(post: Post): void {
    if (post.uri == '' && post.title !== '') {
      post.uri =
        `/${new Date().getFullYear()}/${this.adminService.convertToSlug(post.title)}`;
    }
  }

}
