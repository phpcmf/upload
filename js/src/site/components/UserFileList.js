import app from 'cmf/site/app';
import Component from 'cmf/common/Component';

import Button from 'cmf/common/components/Button';
import Alert from 'cmf/common/components/Alert';
import LoadingIndicator from 'cmf/common/components/LoadingIndicator';

import classList from 'cmf/common/utils/classList';
import extractText from 'cmf/common/utils/extractText';

import mimeToIcon from '../../common/mimeToIcon';

export default class UserFileList extends Component {
  oninit(vnode) {
    super.oninit(vnode);

    // Load file list
    app.fileListState.setUser(vnode.attrs.user || app.session.user);

    this.inModal = vnode.attrs.selectable;
    this.restrictFileType = vnode.attrs.restrictFileType || null;
    this.downloadOnClick = this.attrs.downloadOnClick || false;
    /**
     * @type {string[]} List of file UUIDs currently being hidden.
     */
    this.filesBeingHidden = [];

    /**
     * The user who's media we are dealing with
     */
    this.user = app.fileListState.user;
  }

  view() {
    /**
     * @type {{empty(): boolean, files: import('../../common/models/File').default[]}}
     */
    const state = app.fileListState;

    return (
      <div className="cmf-upload-file-list" aria-live="polite">
        {/* Loading */}
        {state.isLoading() && state.files.length === 0 && (
          <div className={'cmf-upload-loading'}>
            {app.translator.trans('cmf-upload.site.file_list.loading')}

            <LoadingIndicator />
          </div>
        )}

        {/* Empty personal file list */}
        {this.inModal && state.empty() && (
          <p className="cmf-upload-empty">
            <i className="fas fa-cloud-upload-alt cmf-upload-empty-icon" />
            {app.translator.trans(`cmf-upload.site.file_list.modal_empty_${app.screen() !== 'phone' ? 'desktop' : 'phone'}`)}
          </p>
        )}

        {/* Empty file list */}
        {!this.inModal && state.empty() && (
          <div className="Placeholder">
            <p className="cmf-upload-empty">{app.translator.trans('cmf-upload.site.file_list.empty')}</p>
          </div>
        )}

        {/* File list */}
        <ul>
          {state.files.map((file) => {
            const fileIcon = mimeToIcon(file.type());
            const fileSelectable = this.restrictFileType ? this.isSelectable(file) : true;

            const fileClassNames = classList([
              'cmf-file',
              // File is image
              fileIcon === 'image' && 'cmf-file-type-image',
              // File is selected
              this.attrs.selectedFiles && this.attrs.selectedFiles.indexOf(file.id()) >= 0 && 'cmf-file-selected',
            ]);

            /**
             * File's baseName (file name + extension)
             * @type {string}
             */
            const fileName = file.baseName();

            const isFileHiding = this.filesBeingHidden.includes(file.uuid());

            return (
              <li aria-busy={isFileHiding}>
                {app.session.user && (this.user === app.session.user || app.session.user.deleteOthersMediaLibrary()) && (
                  <Button
                    className="Button Button--icon cmf-file-delete"
                    icon="far fa-trash-alt"
                    aria-label={app.translator.trans('cmf-upload.site.file_list.delete_file_a11y_label', { fileName })}
                    disabled={isFileHiding}
                    onclick={this.hideFile.bind(this, file)}
                  />
                )}

                <button
                  className={fileClassNames}
                  onclick={() => this.onFileClick(file)}
                  disabled={!fileSelectable || isFileHiding}
                  aria-label={extractText(app.translator.trans('cmf-upload.site.file_list.select_file_a11y_label', { fileName }))}
                >
                  <figure>
                    {fileIcon === 'image' ? (
                      <img
                        src={file.url()}
                        className="cmf-file-image-preview"
                        draggable={false}
                        // Images should always have an `alt`, even if empty!
                        //
                        // As we already state the file name as part of the
                        // button alt label, there's no point in restating it.
                        //
                        // See: https://www.w3.org/WAI/tutorials/images/decorative#decorative-image-as-part-of-a-text-link
                        alt=""
                      />
                    ) : (
                      <span
                        className="cmf-file-icon"
                        // Prevents a screen-reader from traversing this node.
                        //
                        // This is a placeholder for when no preview is available,
                        // and a preview won't benefit a user using a screen
                        // reader anyway, so there is no benefit to making them
                        // aware of a lack of a preview.
                        role="presentation"
                      >
                        <i className={`fa-fw ${fileIcon}`} />
                      </span>
                    )}

                    <figcaption className="cmf-file-name">{fileName}</figcaption>

                    {isFileHiding && (
                      <span class="cmf-file-loading" role="status" aria-label={app.translator.trans('cmf-upload.site.file_list.hide_file.loading')}>
                        <LoadingIndicator />
                      </span>
                    )}
                  </figure>
                </button>
              </li>
            );
          })}
        </ul>

        {/* Load more files */}
        {state.hasMoreResults() && (
          <div className={'cmf-load-more-files'}>
            <Button className={'Button Button--primary'} disabled={state.isLoading()} loading={state.isLoading()} onclick={() => state.loadMore()}>
              {app.translator.trans('cmf-upload.site.file_list.load_more_files_btn')}
            </Button>
          </div>
        )}
      </div>
    );
  }

  /**
   * Execute function on file click
   *
   * @param {import('../../common/models/File').default} file
   */
  onFileClick(file) {
    // Custom functionality
    if (this.attrs.onFileSelect) {
      this.attrs.onFileSelect(file);
      return;
    }

    // Download on click
    if (this.attrs.downloadOnClick) {
      window.open(file.url());
      return;
    }
  }

  /**
   * Check if a file is selectable
   *
   * @param {import('../../common/models/File').default} file
   */
  isSelectable(file) {
    const fileType = file.type();

    // Custom defined file types
    if (Array.isArray(this.restrictFileType)) {
      return this.restrictFileType.indexOf(fileType) >= 0;
    }

    // Image
    else if (this.restrictFileType === 'image') {
      return fileType.includes('image/');
    }

    // Audio
    else if (this.restrictFileType === 'audio') {
      return fileType.includes('audio/');
    }

    // Video
    else if (this.restrictFileType === 'video') {
      return fileType.includes('video/');
    }

    return false;
  }

  /**
   * Begins the hiding process for a file.
   *
   * - Shows a native confirmation dialog
   * - If confirmed, sends AJAX request to the hide file API
   *
   * @param {import('../../common/models/File').default} file File to hide
   */
  hideFile(file) {
    /**
     * @type {string} File UUID
     */
    const uuid = file.uuid();

    if (this.filesBeingHidden.includes(uuid)) return;

    this.filesBeingHidden.push(uuid);

    const confirmHide = confirm(
      extractText(app.translator.trans('cmf-upload.site.file_list.hide_file.hide_confirmation', { fileName: file.baseName() }))
    );

    if (confirmHide) {
      app
        .request({
          method: 'PATCH',
          url: `${app.site.attribute('apiUrl')}/cmf/upload/hide`,
          body: { uuid },
        })
        .then(() => {
          app.alerts.show(Alert, { type: 'success' }, app.translator.trans('cmf-upload.site.file_list.hide_file.hide_success'));
        })
        .catch(() => {
          app.alerts.show(
            Alert,
            { type: 'error' },
            app.translator.trans('cmf-upload.site.file_list.hide_file.hide_fail', { fileName: file.fileName() })
          );
        })
        .then(() => {
          // Remove hidden file from state
          /**
           * @type {{ files: import('../../common/models/File').default[] }}
           */
          const state = app.fileListState;

          const index = state.files.findIndex((file) => uuid === file.uuid());
          state.files.splice(index, 1);

          // Remove file from hiding list
          const i = this.filesBeingHidden.indexOf(uuid);
          this.filesBeingHidden.splice(i, 1);
        });
    } else {
      // Remove file from hiding list
      const i = this.filesBeingHidden.indexOf(uuid);
      this.filesBeingHidden.splice(i, 1);
    }
  }
}
