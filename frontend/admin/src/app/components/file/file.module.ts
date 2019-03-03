import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { UploadComponent } from "./upload/upload.component";
import { DropzoneModule, DropzoneConfigInterface, DROPZONE_CONFIG } from 'ngx-dropzone-wrapper';

const DEFAULT_DROPZONE_CONFIG: DropzoneConfigInterface = {
  url: '/api/file',
  paramName: 'upload',
  autoReset: 1,
  // maxFilesize: 50,
  // acceptedFiles: 'image/*'
};

@NgModule({
  imports: [
    CommonModule,
    DropzoneModule,
  ],
  declarations: [
    UploadComponent
  ],
  providers: [
    {
      provide: DROPZONE_CONFIG,
      useValue: DEFAULT_DROPZONE_CONFIG
    }
  ],
  exports: [
    UploadComponent
  ]
})
export class FileModule {
}
