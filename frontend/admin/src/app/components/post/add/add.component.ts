import { Component, OnInit } from "@angular/core";
import { Post } from "../../../models/post";
import { FormGroup, FormControl, Validators } from "@angular/forms";
import { AppComponent } from "../../app/app.component";
import { PostService } from "../../../services/post.service";
import { ActivatedRoute } from "@angular/router";
import { PostRelationService } from "../../../services/post-relation.service";
import { PostRelation } from "../../../models/post-relation";

@Component({
  selector: 'os-add-post',
  templateUrl: './add.component.html',
  styleUrls: ['./add.component.scss']
})
export class AddComponent implements OnInit {
  private post: Post;
  private form: FormGroup;
  private relationForm: FormGroup;
  private loading: boolean = null;
  private fromPost: Post;

  constructor(private app: AppComponent,
              private postService: PostService,
              private postRelationService: PostRelationService,
              private activatedRoute: ActivatedRoute) {
  }

  ngOnInit() {
    this.app.pageTitle = "Add Post";
    this.post = new Post;
    this.post.post_type = 'post';
    this.post.owner_id = 1; //TODO: this.app.user
    this.post.created_at = '';
    this.initForm();
    this.initRelationForm();
    if (this.app.fromUri != undefined) {
      this.postService.get(this.app.fromUri)
        .subscribe(response => {
          this.fromPost = response.json() as Post;
        });
      this.relationForm.get('position').disable();
      this.relationForm.get('has').valueChanges.subscribe(value => {
        let positionControl = this.relationForm.get('position');
        if (value) {
          positionControl.enable();
        } else {
          positionControl.disable();
        }
      });
    }
    this.form.patchValue(this.post);
  }

  get status(): string {
    switch (this.loading) {
      case true:
        return 'processing';
      case false:
        return 'done';
      default:
        return 'default';
    }
  }

  private initForm() {
    this.form = new FormGroup({
      internal_name: new FormControl(''),
      title: new FormControl('', [Validators.required, Validators.minLength(1)]),
      description: new FormControl(''),
      keywords: new FormControl(''),
      excerpt: new FormControl(''),
      classname: new FormControl(),
      body: new FormControl(''),
      uri: new FormControl('', [Validators.required, Validators.minLength(1)]),
      post_type: new FormControl(''),
      mime_type: new FormControl(''),
      owner_id: new FormControl(-1),
      access_level: new FormControl(0),
      created_at: new FormControl(null),
    });
  }

  private initRelationForm() {
    this.relationForm = new FormGroup({
      has: new FormControl(false),
      position: new FormControl('', [Validators.required, Validators.minLength(1)])
    });
  }

  private savePost($event?: Event) {
    if ($event) $event.preventDefault();
    if (this.form.valid) {
      this.loading = true;
      let post = this.form.value as Post;
      this.postService.add(post).subscribe((response) => {
          let data = response.json();
          this.loading = false;
          this.app.flashMessage("Post added.");

          if (this.relationForm.value.has && this.relationForm.valid) {
            let relation: PostRelation = {
              uri_a: post.uri,
              uri_b: this.fromPost.uri,
              position: this.relationForm.value.position,
              order: 0
            };
            this.postRelationService.add(relation).subscribe(response => {
                window.open(
                  post.uri +
                  (post.post_type == 'attachment' ? '?view=page' : '')
                  , '_blank');
                this.app.flashMessage("Post relation added.");
              },
              err => {
                this.app.flashMessage("Something went wrong");
              });
          } else {
            window.open(
              data.post.uri +
              (data.post.post_type == 'attachment' ? '?view=page' : '')
              , '_blank');
          }
        },
        err => {
          this.app.flashMessage("Something went wrong");
        });
    } else {

    }
  }

  private titleBlur($event) {
    let post = JSON.parse(JSON.stringify(this.form.value)) as Post;
    this.postService.createSlug(post);
    this.form.get('uri').setValue(post.uri);
  }

  private onUploadError($event) {
    console.log('onUploadError', $event);
    let response: any = $event[1];

    if (response.message) {
      this.app.flashMessage(response.message);
    }
  }

  private onUploadSuccess(response) {
    if (response.message) {
      this.app.flashMessage(response.message);
    }

    if (response.success && response.storage_path) {
      this.form.get('body').setValue(response.storage_path);
      this.form.get('post_type').setValue('attachment');
    }
  }

}
