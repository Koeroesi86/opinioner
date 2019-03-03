import { Component, OnInit } from "@angular/core";
import { AppComponent } from "../../app/app.component";
import { ActivatedRoute, Router } from "@angular/router";
import { PostService } from "../../../services/post.service";
import { Post } from "../../../models/post";
import { FormControl, FormGroup, Validators } from "@angular/forms";
import { PostRelationService } from "../../../services/post-relation.service";
import { URLSearchParams } from "@angular/http";
import { PostRelation } from "../../../models/post-relation";

@Component({
  selector: 'os-edit-post',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.scss']
})
export class EditComponent implements OnInit {
  private fromPost: Post;
  private loading: boolean = null;
  private form: FormGroup;
  private message: string = "";
  private relatedPosts: PostRelation[];
  private versions: Post[];

  constructor(private app: AppComponent,
              private postService: PostService,
              private postRelationService: PostRelationService,
              private router: Router,
              private activatedRoute: ActivatedRoute,) {
  }

  ngOnInit() {
    this.app.pageTitle = "Edit Post";
    this.initForm();
    if (this.app.fromUri == undefined) {
      this.router.navigate(['/home']);
    } else {
      this.postService.get(this.app.fromUri)
        .subscribe(response => {
          let fromPost = response.json() as Post;
          this.setFromPost(fromPost);
          this.refreshRelations();
        });
      this.getVersions();
    }
  }

  private refreshRelations() {
    let params = new URLSearchParams;
    params.set('uri_b', this.fromPost.uri);
    params.set('orderBy', 'created_at');
    this.postRelationService.getAll(params)
      .subscribe(response => {
        this.relatedPosts = response.json() as PostRelation[];
      });
  }

  private getVersions() {
    let params = new URLSearchParams;
    params.set('uri', this.app.fromUri)
    params.set('orderBy', 'created_at');
    this.postService.getAll(params)
      .subscribe(response => {
        let versions = response.json() as Post[];
        this.versions = versions.reverse();
      });
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
      id: new FormControl(),
      internal_name: new FormControl(),
      title: new FormControl('', [Validators.required, Validators.minLength(1)]),
      description: new FormControl(),
      keywords: new FormControl(),
      excerpt: new FormControl(),
      classname: new FormControl(),
      body: new FormControl(),
      uri: new FormControl('', [Validators.required, Validators.minLength(1)]),
      post_type: new FormControl(),
      mime_type: new FormControl(),
      owner_id: new FormControl(),
      access_level: new FormControl(),
      created_at: new FormControl(),
    });
  }

  private savePost($event?: Event) {
    if ($event) $event.preventDefault();
    if (this.form.valid) {
      this.loading = true;
      this.postService.add(this.form.value).subscribe((response) => {
          this.loading = false;
        },
        err => {
          this.loading = null;
          this.app.flashMessage("Something went wrong");
        });
    } else {

    }
  }

  private titleBlur($event) {
    let post = JSON.parse(JSON.stringify(this.form.value)) as Post;
    if (!post.uri) {
      this.postService.createSlug(post);
      this.form.get('uri').setValue(post.uri);
    }
  }

  private deleteAll() {
    if (confirm(`Are you sure you want to delete all versions of "${this.fromPost.title}" ?`)) {
      let params = new URLSearchParams;
      params.set('uri', this.fromPost.uri);
      this.postService.removeAll(params)
        .subscribe(response => {
          if (response.status == 204) {
            this.app.flashMessage(`All versions of "${this.fromPost.title}" deleted`);
            this.router.navigate(['/home']);
          }
        });
    }
  }

  setFromPost(post: Post) {
    this.fromPost = post;
    this.form.patchValue(this.fromPost);
  }

  private removeVersion(version: Post) {
    if (confirm(`Are you sure you want to delete version #${version.id} "${version.title}" ?`)) {
      this.postService.remove(version)
        .subscribe(response => {
          if (response.status == 204) {
            this.app.flashMessage(`Version #${version.id} of "${version.title}" deleted`);
            this.getVersions();
          }
        });
    }
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
