import { Component, OnInit } from '@angular/core';
import {AppComponent} from "../app/app.component";

@Component({
  selector: 'os-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  constructor(private app: AppComponent) { }

  ngOnInit() {
    this.app.pageTitle = "Home";
  }

}
