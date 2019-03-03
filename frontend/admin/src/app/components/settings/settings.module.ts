import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { RouterModule } from "@angular/router";
import { SettingsComponent } from "./settings.component";
import { ReactiveFormsModule } from "@angular/forms";
import { RelationModule } from "../post/relation/relation.module";
import { LoadingModule } from "../app/loading/loading.module";
import { OptionComponent } from './option/option.component';

@NgModule({
  imports: [
    CommonModule,
    ReactiveFormsModule,
    RelationModule,
    LoadingModule,
    RouterModule.forRoot([
      {
        path: 'settings',
        component: SettingsComponent,
        data: {
          title: "Settings",
          styleClass: "edit-post",
          icon: '<i class="fa fa-pencil" aria-hidden="true"></i>'
        }
      }
    ])
  ],
  declarations: [
    SettingsComponent,
    OptionComponent
  ],
  exports: [
    RouterModule
  ]
})
export class SettingsModule {
}
