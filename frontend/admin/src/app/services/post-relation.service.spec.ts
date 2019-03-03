import { TestBed, inject } from '@angular/core/testing';

import { PostRelationService } from './post-relation.service';

describe('PostRelationService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PostRelationService]
    });
  });

  it('should ...', inject([PostRelationService], (service: PostRelationService) => {
    expect(service).toBeTruthy();
  }));
});
