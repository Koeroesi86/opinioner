import { Component, OnInit } from "@angular/core";
import { AdminService } from "../../services/admin.service";
import { Router, ActivatedRoute } from "@angular/router";

@Component({
    selector: 'os-root',
    templateUrl: 'app.component.html',
    styleUrls: ['app.component.scss']
})
export class AppComponent implements OnInit {
    public title: string;
    public pageTitle: string = '';
    public fromUri: string;
    private showAdminMenu: boolean = true;
    private message: string = '';

    constructor(private adminService: AdminService,
                private router: Router,
                private activatedRoute: ActivatedRoute) {

    }

    ngOnInit(): void {
        this.title = 'Administration';
        let showMenu = localStorage.getItem('showAdminMenu');
        this.showAdminMenu = (showMenu != 'false');

        this.activatedRoute.queryParams.subscribe(params => {
            if (params['from']) {
                this.fromUri = params['from'];
            }
        });
    }

    toggleMenu($event?: Event) {
        if ($event) {
            $event.preventDefault();
        }
        this.showAdminMenu = !this.showAdminMenu;
        localStorage.setItem('showAdminMenu', this.showAdminMenu ? 'true' : 'false');
    }

    flashMessage(message: string) {
        this.message = message;
        setTimeout(() => {
            this.message = '';
        }, 10000);
    }

    get queryParams() {
        let queryParams = {};
        if(this.fromUri != undefined) {
            queryParams['from'] = this.fromUri;
        }
        return queryParams;
    }
}
