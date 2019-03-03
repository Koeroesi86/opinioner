import {NgModule} from "@angular/core";
import {EscapeHtmlPipe} from "./pipes/escape-html.pipe";
import {CommonModule} from "@angular/common";

@NgModule({
    imports: [
        CommonModule
    ],
    declarations: [
        EscapeHtmlPipe
    ],
    exports: [
        EscapeHtmlPipe
    ]
})
export class PipesModule {
}
