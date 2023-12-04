import app from 'cmf/admin/app';
import Modal from 'cmf/common/components/Modal';
import LoadingIndicator from 'cmf/common/components/LoadingIndicator';

export default class InspectMimeModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);

    this.uploading = false;
    this.inspection = {};
  }

  className() {
    return 'Modal--small cmf-upload-inspect-mime-modal';
  }

  title() {
    return app.translator.trans('cmf-upload.admin.inspect-mime.title');
  }

  content() {
    return (
      <div className="Modal-body">
        <p>
          {app.translator.trans('cmf-upload.admin.inspect-mime.description', {
            a: <a href="https://github.com/SoftCreatR/php-mime-detector"></a>,
          })}
        </p>
        <p>{app.translator.trans('cmf-upload.admin.inspect-mime.select')}</p>
        <div>
          <input type="file" onchange={this.onupload.bind(this)} disabled={this.uploading} />
          {this.uploading ? LoadingIndicator.component() : null}
        </div>
        <dl>
          <dt>{app.translator.trans('cmf-upload.admin.inspect-mime.laravel-validation')}</dt>
          <dd>
            {typeof this.inspection.laravel_validation === 'undefined' ? (
              <em>{app.translator.trans('cmf-upload.admin.inspect-mime.no-file-selected')}</em>
            ) : this.inspection.laravel_validation ? (
              app.translator.trans('cmf-upload.admin.inspect-mime.validation-passed')
            ) : (
              app.translator.trans('cmf-upload.admin.inspect-mime.validation-failed', {
                error: this.inspection.laravel_validation_error || '?',
              })
            )}
          </dd>
        </dl>
        <dl>
          <dt>{app.translator.trans('cmf-upload.admin.inspect-mime.mime-detector')}</dt>
          <dd>
            {this.inspection.mime_detector ? (
              <code>{this.inspection.mime_detector}</code>
            ) : (
              <em>{app.translator.trans('cmf-upload.admin.inspect-mime.not-available')}</em>
            )}
          </dd>
        </dl>
        <dl>
          <dt>{app.translator.trans('cmf-upload.admin.inspect-mime.mime-fileinfo')}</dt>
          <dd>
            {this.inspection.php_mime ? (
              <code>{this.inspection.php_mime}</code>
            ) : (
              <em>{app.translator.trans('cmf-upload.admin.inspect-mime.not-available')}</em>
            )}
          </dd>
        </dl>
        <dl>
          <dt>{app.translator.trans('cmf-upload.admin.inspect-mime.guessed-extension')}</dt>
          <dd>
            {this.inspection.guessed_extension ? (
              <code>{this.inspection.guessed_extension}</code>
            ) : (
              <em>{app.translator.trans('cmf-upload.admin.inspect-mime.not-available')}</em>
            )}
          </dd>
        </dl>
      </div>
    );
  }

  onupload(event) {
    const body = new FormData();

    for (let i = 0; i < event.target.files.length; i++) {
      body.append('files[]', event.target.files[i]);
    }

    this.uploading = true;

    return app
      .request({
        method: 'POST',
        url: app.site.attribute('apiUrl') + '/cmf/upload/inspect-mime',
        serialize: (raw) => raw,
        body,
      })
      .then((result) => {
        this.uploading = false;
        this.inspection = result;
        m.redraw();
      })
      .catch((error) => {
        this.uploading = false;
        this.inspection = {};
        m.redraw();

        throw error;
      });
  }
}
