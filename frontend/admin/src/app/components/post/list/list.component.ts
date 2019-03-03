import { Component, OnInit } from "@angular/core";
import { PostService } from "../../../services/post.service";
import { URLSearchParams } from "@angular/http";
import { Post } from "../../../models/post";

@Component({
  selector: 'os-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit {
  private posts: Post[];

  constructor(private postService: PostService) {
  }

  ngOnInit() {
    let params = new URLSearchParams;
    // let typeFilter = ['post', 'page']; //TODO: filter
    // params.set('type', typeFilter.join(','));
    params.set('groupBy', 'uri');
    this.postService.getAll(params)
      .subscribe(response => {
        this.posts = response.json() as Post[];
      });
  }

}
