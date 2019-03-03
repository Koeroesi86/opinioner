import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { RouterModule } from '@angular/router';
import { AppComponent } from './components/app/app.component';
import { MenuComponent } from './components/app/menu/menu.component';
import { PipesModule } from './pipes/pipes.module';
import { AdminService } from "./services/admin.service";
import { PostModule } from "./components/post/post.module";
import { HomeModule } from "./components/home/home.module";
import { SettingsModule } from "./components/settings/settings.module";
import { PostService } from "./services/post.service";
import { PostRelationService } from "./services/post-relation.service";
import { SettingsService } from "./services/settings.service";

@NgModule({
  declarations: [
    AppComponent,
    MenuComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    RouterModule,
    PipesModule,
    HomeModule,
    SettingsModule,
    PostModule,
  ],
  providers: [
    AdminService,
    PostService,
    PostRelationService,
    SettingsService,
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
