import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HomeComponent} from "./home.component";
import {RouterModule} from "@angular/router";

@NgModule({
    declarations: [
        HomeComponent
    ],
    imports: [
        CommonModule,
        RouterModule.forRoot([
            {path: '', pathMatch: 'full', redirectTo: 'home'},
            {
                path: 'home',
                component: HomeComponent,
                data: {
                    title: "Home",
                    icon: '<i class="fa fa-home" aria-hidden="true"></i>',
                    styleClass: "home"
                }
            },
        ])
    ],
    exports: [
        RouterModule
    ]
})
export class HomeModule {
}
