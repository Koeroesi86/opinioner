import {Injectable} from '@angular/core';
import {Http} from "@angular/http";

@Injectable()
export class AdminService {
    public bodyClass: string = '';
    public message: string|boolean = false;
    public static csrf: string = '';

    constructor(
        private http: Http
    ) {
        let meta = document.querySelector('meta[property="csrf-token"]');
        if(meta != null) {
            AdminService.csrf =meta['content'];
        } else {
            console.error("No csrf-token present in meta tags.");
        }
    }

    convertToSlug = function (Text) {
        let from = "ãàáäâẽèéëêìíïîõòóöôőùúüûűñç·/_,:;";
        let to = "aaaaaeeeeeiiiioooooouuuuunc------";

        Text = Text.toLowerCase();

        for (let i = 0, l = from.length; i < l; i++) {
            Text = Text.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        return Text
            .replace(/ /g, '-')
            .replace(/[\-]+/g, '-')
            .replace(/[^\w-]+/g, '');
    }

    ajaxCall(params) {
        let ajax_params = {
            handler: 'Admin',
            type: '',
            '_token': AdminService.csrf
        };

        for (let prop of Object.getOwnPropertyNames(params)) {
            ajax_params[prop] = params[prop];
        }

        return this.http.post("/ajax", ajax_params);
    }

    redirectTo(url: string) {

    }
}
