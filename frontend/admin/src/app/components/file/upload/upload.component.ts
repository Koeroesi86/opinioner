import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { AdminService } from "../../../services/admin.service";

@Component({
  selector: 'os-upload',
  templateUrl: './upload.component.html',
  styleUrls: ['./upload.component.scss']
})
export class UploadComponent implements OnInit {
  @Output('onUploadSuccess') public onUploadSuccess: EventEmitter<any> = new EventEmitter();
  @Output('onUploadError') public onUploadError: EventEmitter<any> = new EventEmitter();
  @Output('onSending') public onSending: EventEmitter<any> = new EventEmitter();
  @Input('message') public message = "Click or drag files here to upload";

  constructor() {
  }

  ngOnInit() {
  }

  private uploadError($event) {
    this.onUploadError.emit($event);
  }

  private uploadSuccess($event) {
    // [File, Object, ProgressEvent]
    let response: any = $event[1];

    this.onUploadSuccess.emit(response);
  }

  private sending($event) {
    // [File, XMLHttpRequest, FormData]
    let request: XMLHttpRequest = $event[1];
    let formData: FormData = $event[2];
    formData.append('_token', AdminService.csrf);
    request.setRequestHeader('X-XSRF-TOKEN', AdminService.csrf);
    this.onSending.emit($event);
  }
}
