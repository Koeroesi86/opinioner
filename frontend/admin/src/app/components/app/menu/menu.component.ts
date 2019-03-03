import { Component, Input, OnInit } from "@angular/core";
import { NavigationEnd, Route, Router } from "@angular/router";
import { AppComponent } from "../app.component";

@Component({
    selector: 'os-menu',
    templateUrl: 'menu.component.html',
    styleUrls: ['menu.component.scss']
})
export class MenuComponent implements OnInit {
    @Input('fromUri') fromUri: string;
    @Input('routes') routes: Route[];
    @Input('currentPath') currentPath: string;
    @Input('parent') parent: boolean = true;
    private activeUrl: string;
    private urlParser: RegExp = /(^\/[a-z\/]+)([\?\;\#].*)?/i; //TODO: something better

    constructor(
        private app: AppComponent,
        private router: Router
    ) {
    }

    ngOnInit() {
        this.activeUrl = '';
        this.router.events.subscribe(event => {
            if (event instanceof NavigationEnd) {
                if (this.urlParser.test(event.urlAfterRedirects)) {
                    let matches = this.urlParser.exec(event.urlAfterRedirects);
                    this.activeUrl = matches[1];
                }
            }
        });
    }

    get menuPoints(): Route[] {
        let menuPoints: any[] = [];

        for (let route of this.routes) {
            if (route.hasOwnProperty('data') && route.data) {
                menuPoints.push(route);
            }
        }

        return menuPoints;
    }

    get queryParams() {
        let queryParams = {};
        if (this.fromUri != undefined) {
            queryParams['from'] = this.fromUri;
        }
        return queryParams;
    }

    getMenuLink(route: Route): string {
        return this.currentPath + '/' + route.path;
    }

    isActive(route) {
        return this.activeUrl.indexOf(this.getMenuLink(route)) == 0 && this.activeUrl !== this.getMenuLink(route);
    }

}
