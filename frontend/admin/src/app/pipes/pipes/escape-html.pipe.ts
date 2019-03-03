import { Pipe, PipeTransform } from "@angular/core";
import { DomSanitizer } from "@angular/platform-browser";

@Pipe({
    name: 'escapeHtml'
})
export class EscapeHtmlPipe implements PipeTransform {
    constructor(private sanitizer: DomSanitizer) {
    }

    transform(value: any, args: any[] = []) {
        // Escape 'value' and return it
        return this.sanitizer.bypassSecurityTrustHtml(value);
    }

}
