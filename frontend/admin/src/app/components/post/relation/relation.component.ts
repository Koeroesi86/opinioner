import {Component, OnInit, Input, Output, EventEmitter} from "@angular/core";
import {PostRelation} from "../../../models/post-relation";
import {PostRelationService} from "../../../services/post-relation.service";
import {FormGroup, FormControl, Validators} from "@angular/forms";
import {AppComponent} from "../../app/app.component";

@Component({
    selector: 'os-post-relation',
    templateUrl: './relation.component.html',
    styleUrls: ['./relation.component.scss']
})
export class RelationComponent implements OnInit {
    @Input('relation') relation: PostRelation;
    @Input('targetURI') targetURI: string;
    @Output('onSave') public onSave: EventEmitter<any> = new EventEmitter();
    @Output('onDelete') public onDelete: EventEmitter<any> = new EventEmitter();
    private form: FormGroup;

    constructor(private postRelationService: PostRelationService,
                private app: AppComponent) {
    }

    ngOnInit() {
        if (!this.relation) {
            this.relation = new PostRelation;
            this.relation.uri_a = '';
            this.relation.uri_b = this.targetURI;
            this.relation.position = '';
            this.relation.order = 0;
        }

        this.initForm();
        if (!this.relation.id) {
            this.form.removeControl('id');
        } else {
            this.form.removeControl('uri_a');
            this.form.removeControl('uri_b');
        }

        let formValues = JSON.parse(JSON.stringify(this.relation)) as PostRelation;
        delete formValues.post;
        // delete formValues.uri_a;
        // delete formValues.uri_b;
        delete formValues.created_at;
        delete formValues.updated_at;
        this.form.patchValue(formValues);
    }

    initForm() {
        this.form = new FormGroup({
            id: new FormControl(),
            uri_a: new FormControl('', [Validators.required, Validators.minLength(2)]),
            uri_b: new FormControl({value: '', disabled: true}, [Validators.required, Validators.minLength(2)]),
            position: new FormControl('', [Validators.required, Validators.minLength(2)]),
            order: new FormControl(0),
        });
    }

    private get id(): string|number {
        if (this.relation.id) {
            return this.relation.id;
        } else {
            return 'add';
        }
    }

    private saveRelation() {
        let currentRelation: PostRelation = this.form.value;
        currentRelation.uri_b = this.relation.uri_b;
        if (this.id == 'add') {
            this.postRelationService.add(currentRelation).subscribe((response) => {
                this.app.flashMessage("Post relation added.");
                this.onSave.emit(response);
            });
        } else {
            this.postRelationService.update(currentRelation).subscribe((response) => {
                this.app.flashMessage("Post relation updated.");
                this.onSave.emit(response);
            });
        }
    }

    private deleteRelation() {
        if (confirm(`Are you sure you want to remove relation #${this.relation.id}?`)) {
            this.postRelationService.remove(this.form.value).subscribe((response) => {
                this.app.flashMessage("Post relation removed.");
                this.onDelete.emit(response);
            });
        }
    }
}
