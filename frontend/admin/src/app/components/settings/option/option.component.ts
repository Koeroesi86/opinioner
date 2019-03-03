import {Component, EventEmitter, Input, OnInit, Output} from "@angular/core";
import {Option} from "../../../models/option";
import {FormControl, FormGroup, Validators} from "@angular/forms";
import {SettingsService} from "../../../services/settings.service";
import {AppComponent} from "../../app/app.component";

@Component({
    selector: 'os-option',
    templateUrl: './option.component.html',
    styleUrls: ['./option.component.scss']
})
export class OptionComponent implements OnInit {
    @Input('option') public option: Option;
    @Output('save') public saveEvent: EventEmitter<Option>;
    private form: FormGroup;

    constructor(private app: AppComponent,
                private settingsService: SettingsService) {
        this.saveEvent = new EventEmitter<Option>();
    }

    ngOnInit() {
        this.initForm();
        if (!this.option) {
            this.option = new Option;
            this.option.name = '';
            this.option.value = ''
        }

        if (!this.option.id) {
            this.form.removeControl('id');
        }

        this.form.setValue(this.option);
        if (this.option.id) {
            this.form.get('id').disable();
            this.form.get('name').disable();
        }
    }

    private initForm() {
        this.form = new FormGroup({
            id: new FormControl(null),
            name: new FormControl('', [Validators.required]),
            value: new FormControl(''),
        });
    }

    save() {
        if(this.option.id) {
            this.updateOption();
        } else {
            this.addOption();
        }
    }

    private addOption() {
        let option = this.form.value as Option;
        this.settingsService.add(option)
            .subscribe(response => {
                if (response.status == 200) {
                    this.app.flashMessage(`Option "${option.name}" added`);
                    this.saveEvent.emit(option);
                }
            });
    }

    private updateOption() {
        let option = this.form.value as Option;
        this.settingsService.update(option)
            .subscribe(response => {
                if (response.status == 200) {
                    this.app.flashMessage(`Option "${option.name}" updated`);
                    this.saveEvent.emit(option);
                }
            });
    }

    private removeOption(option: Option) {
        if (confirm(`Are you sure you want to delete option #${option.id} "${option.name}" ?`)) {
            this.settingsService.remove(option)
                .subscribe(response => {
                    if (response.status == 204) {
                        this.app.flashMessage(`Option #${option.id} of "${option.name}" deleted`);
                        this.saveEvent.emit(option);
                    }
                });
        }
    }

}
