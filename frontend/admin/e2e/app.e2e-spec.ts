import { OsAdminPage } from './app.po';

describe('os-admin App', () => {
  let page: OsAdminPage;

  beforeEach(() => {
    page = new OsAdminPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
