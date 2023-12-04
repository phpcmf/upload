import app from 'cmf/site/app';
import { extend } from 'cmf/common/extend';
import Post from 'cmf/site/components/Post';

/* global $ */

export default function () {
  extend(Post.prototype, 'oncreate', function () {
    this.$('[data-cmf-upload-download-uuid]')
      .unbind('click')
      .on('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (!app.site.attribute('cmf-upload.canDownload')) {
          alert(app.translator.trans('cmf-upload.site.states.unauthorized'));
          return;
        }

        let url = app.site.attribute('apiUrl') + '/cmf/download';

        url += '/' + encodeURIComponent(e.currentTarget.dataset.cmfUploadDownloadUuid);
        url += '/' + encodeURIComponent(this.attrs.post.id());
        url += '/' + encodeURIComponent(app.session.csrfToken);

        window.open(url);
      });
  });
}
