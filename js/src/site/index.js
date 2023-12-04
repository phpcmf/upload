import { extend } from 'cmf/common/extend';
import app from 'cmf/site/app';
import UserPage from 'cmf/site/components/UserPage';
import LinkButton from 'cmf/common/components/LinkButton';

import File from '../common/models/File';
import FileListState from './states/FileListState';
import downloadButtonInteraction from './downloadButtonInteraction';
import addUploadButton from './addUploadButton';
import UploadsUserPage from './components/UploadsUserPage';
import User from 'cmf/common/models/User';
import Model from 'cmf/common/Model';

export * from './components';

app.initializers.add('cmf-upload', () => {
  User.prototype.viewOthersMediaLibrary = Model.attribute('cmf-upload-viewOthersMediaLibrary');
  User.prototype.deleteOthersMediaLibrary = Model.attribute('cmf-upload-deleteOthersMediaLibrary');
  User.prototype.uploadCountCurrent = Model.attribute('cmf-upload-uploadCountCurrent');
  User.prototype.uploadCountAll = Model.attribute('cmf-upload-uploadCountAll');

  addUploadButton();
  downloadButtonInteraction();

  // 文件模型
  app.store.models.files = File;

  // 文件列表状态
  app.fileListState = new FileListState();

  // 将用户上传添加到用户配置文件
  app.routes['user.uploads'] = {
    path: '/user/:username/files',
    component: UploadsUserPage,
  };

  // 将上传添加到用户页面菜单项
  extend(UserPage.prototype, 'navItems', function (items) {
    const canUpload = !!app.site.attribute('cmf-upload.canUpload');
    const hasUploads = !!this.user.uploadCountCurrent();

    if (app.session.user && (app.session.user.viewOthersMediaLibrary() || (this.user === app.session.user && (canUpload || hasUploads)))) {
      const uploadCount = this.user.uploadCountCurrent();

      items.add(
        'uploads',
        LinkButton.component(
          {
            href: app.route('user.uploads', {
              username: this.user.username(),
            }),
            name: 'uploads',
            icon: 'fas fa-file-upload',
          },
          [
            this.user === app.session.user
              ? app.translator.trans('cmf-upload.site.buttons.media')
              : app.translator.trans('cmf-upload.site.buttons.user_uploads'),
            ' ',
            uploadCount > 0 ? <span className="Button-badge">{uploadCount}</span> : '',
          ]
        ),
        80
      );
    }
  });
});
