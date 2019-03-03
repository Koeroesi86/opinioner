import {NgModule} from "@angular/core";
import {CommonModule} from "@angular/common";
import {RelationComponent} from "./relation.component";
import {ReactiveFormsModule} from "@angular/forms";

@NgModule({
    imports: [
        CommonModule,
        ReactiveFormsModule,
    ],
    declarations: [
        RelationComponent
    ],
    exports: [
        RelationComponent
    ]
})
export class RelationModule {
}
