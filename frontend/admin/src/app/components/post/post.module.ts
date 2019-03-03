import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { RouterModule } from "@angular/router";
import { AddComponent } from "./add/add.component";
import { EditComponent } from "./edit/edit.component";
import { ReactiveFormsModule } from "@angular/forms";
import { RelationModule } from "./relation/relation.module";
import { ListComponent } from "./list/list.component";
import { LoadingModule } from "../app/loading/loading.module";
import { Ng2PaginationModule } from "ng2-pagination";
import { NgxPaginationModule } from 'ngx-pagination';
import { FileModule } from "../file/file.module";

@NgModule({
  imports: [
    CommonModule,
    ReactiveFormsModule,
    NgxPaginationModule,
    LoadingModule,
    FileModule,
    RelationModule,
    RouterModule.forRoot([
      {
        path: 'post',
        data: {
          title: "Posts",
          styleClass: "post-menu",
          icon: '<i class="fa fa-newspaper-o" aria-hidden="true"></i>'
        },
        children: [
          {
            path: 'list',
            component: ListComponent,
            data: {
              title: "List",
              styleClass: "post-list",
              icon: '<i class="fa fa-list" aria-hidden="true"></i>'
            }
          },
          {
            path: 'edit',
            component: EditComponent,
            data: {
              title: "Edit post",
              styleClass: "edit-post",
              icon: '<i class="fa fa-pencil" aria-hidden="true"></i>'
            }
          },
          {
            path: 'add',
            component: AddComponent,
            data: {
              title: "New post",
              styleClass: "new-post",
              icon: '<i class="fa fa-plus" aria-hidden="true"></i>'
            }
          },
          {
            path: '',
            // component: ListComponent,
            redirectTo: 'list',
            pathMatch: 'full'
          }
        ]
      }
    ])
  ],
  declarations: [
    AddComponent,
    EditComponent,
    ListComponent,
  ],
  exports: [
    RouterModule
  ]
})
export class PostModule {
}
