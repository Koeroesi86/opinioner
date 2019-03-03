import { Component, OnInit } from "@angular/core";
import { PostRelationService } from "../../services/post-relation.service";
import { AppComponent } from "../app/app.component";
import { URLSearchParams } from "@angular/http";
import { PostRelation } from "../../models/post-relation";
import { Option } from "../../models/option";
import { SettingsService } from "../../services/settings.service";

@Component({
  selector: 'os-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {
  private relatedPosts: PostRelation[];
  private settings: Option[];

  constructor(private app: AppComponent,
              private postRelationService: PostRelationService,
              private settingsService: SettingsService) {
  }

  ngOnInit() {
    let params = new URLSearchParams;
    params.set('uri_b', '');
    this.postRelationService.getAll(params)
      .subscribe(response => {
        this.relatedPosts = response.json() as PostRelation[];
      });

    this.getSettings();
  }

  private getSettings() {
    this.settingsService.getAll()
      .subscribe(response => {
        this.settings = response.json() as Option[];
      });
  }

  settingsSave(option: Option) {
    this.getSettings();
  }

}
