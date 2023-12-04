import app from 'cmf/admin/app';
import UploadPage from './components/UploadPage';

export * from './components';

app.initializers.add('cmf-upload', () => {
  app.extensionData
    .for('cmf-upload')
    .registerPage(UploadPage)
    .registerPermission(
      {
        icon: 'far fa-file',
        label: app.translator.trans('cmf-upload.admin.permissions.upload_label'),
        permission: 'cmf-upload.upload',
      },
      'start',
      50
    )
    .registerPermission(
      {
        icon: 'fas fa-download',
        label: app.translator.trans('cmf-upload.admin.permissions.download_label'),
        permission: 'cmf-upload.download',
        allowGuest: true,
      },
      'view',
      50
    )
    .registerPermission(
      {
        icon: 'fas fa-eye',
        label: app.translator.trans('cmf-upload.admin.permissions.view_user_uploads_label'),
        permission: 'cmf-upload.viewUserUploads',
      },
      'moderate',
      50
    )
    .registerPermission(
      {
        icon: 'fas fa-trash',
        label: app.translator.trans('cmf-upload.admin.permissions.delete_uploads_of_others_label'),
        permission: 'cmf-upload.deleteUserUploads',
      },
      'moderate',
      50
    );
});
