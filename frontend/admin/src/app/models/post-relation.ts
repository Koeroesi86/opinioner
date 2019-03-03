import {Post} from "./post";

export class PostRelation {
    public id?: number;
    public uri_a: string;
    public uri_b: string;
    public position: string;
    public order:number;
    public created_at?: string;
    public updated_at?: string;
    public post?: Post;
}
