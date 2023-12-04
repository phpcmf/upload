import Modal from 'cmf/common/components/Modal';
import Button from 'cmf/common/components/Button';
import UploadButton from './UploadButton';
import UserFileList from './UserFileList';
import DragAndDrop from './DragAndDrop';

export default class FileManagerModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);

    // 初始化上传管理器
    this.uploader = vnode.attrs.uploader;

    // 当前选定的文件
    this.selectedFiles = [];

    // 允许多选
    this.multiSelect = vnode.attrs.multiSelect || true;

    // 将文件选择限制为特定类型
    this.restrictFileType = vnode.attrs.restrictFileType || null;

    // 拖放
    this.dragDrop = null;

    // 初始化上传
    this.onUpload();
  }

  className() {
    return 'Modal--large cmf-file-manager-modal';
  }

  /**
   * 初始化拖放
   */
  oncreate(vnode) {
    super.oncreate(vnode);

    this.dragDrop = new DragAndDrop((files) => this.uploader.upload(files, false), this.$().find('.Modal-content')[0]);
  }

  /**
   * 从模态内容中删除事件
   */
  onremove() {
    if (this.dragDrop) {
      this.dragDrop.unload();
    }
  }

  view() {
    const fileCount = this.selectedFiles.length;

    return (
      <div className={`Modal modal-dialog ${this.className()}`}>
        <div className="Modal-content">
          <div className="cmf-modal-buttons App-backControl">
            <UploadButton uploader={this.uploader} disabled={app.fileListState.isLoading()} isMediaUploadButton />
          </div>

          <div className="cmf-drag-and-drop">
            <div className="cmf-drag-and-drop-release">
              <i className="fas fa-cloud-upload-alt" />

              {app.translator.trans('cmf-upload.site.file_list.release_to_upload')}
            </div>
          </div>

          <div className="Modal-header">
            <h3 className="App-titleControl App-titleControl--text">{app.translator.trans('cmf-upload.site.media_manager')}</h3>
          </div>

          {this.alertAttrs && (
            <div className="Modal-alert">
              <Alert {...this.alertAttrs} />
            </div>
          )}

          <div className="Modal-body">
            <UserFileList
              user={this.attrs.user}
              selectable
              onFileSelect={this.onFileSelect.bind(this)}
              selectedFiles={this.selectedFiles}
              restrictFileType={this.restrictFileType}
            />
          </div>

          <div className="Modal-footer">
            <Button onclick={this.hide.bind(this)} className="Button">
              {app.translator.trans('cmf-upload.site.buttons.cancel')}
            </Button>

            <Button
              onclick={this.onSelect.bind(this)}
              disabled={this.selectedFiles.length === 0 || (!this.multiSelect && this.selectedFiles.length > 1)}
              className="Button Button--primary"
            >
              {app.translator.trans('cmf-upload.site.file_list.confirm_selection_btn', { fileCount })}
            </Button>
          </div>
        </div>
      </div>
    );
  }

  /**
   * 在所选文件中添加或删除文件
   *
   * @param {File} file
   */
  onFileSelect(file) {
    const itemPosition = this.selectedFiles.indexOf(file.id());

    if (itemPosition >= 0) {
      this.selectedFiles.splice(itemPosition, 1);
    } else {
      if (this.multiSelect) {
        this.selectedFiles.push(file.id());
      } else {
        this.selectedFiles = [file.id()];
      }
    }
  }

  /**
   * 上传后将文件添加到文件列表
   */
  onUpload() {
    this.uploader.on('success', ({ file }) => {
      if (this.multiSelect) {
        this.selectedFiles.push(file.id());
      } else {
        this.selectedFiles = [file.id()];
      }
    });
  }

  /**
   * 将所选文件添加到编辑器
   */
  onSelect() {
    this.hide();

    // 自定义回调
    if (this.attrs.onSelect) {
      this.attrs.onSelect(this.selectedFiles);

      return;
    }

    // 将所选文件添加到编辑器
    this.selectedFiles.map((fileId) => {
      const file = app.store.getById('files', fileId);

      app.composer.editor.insertAtCursor(file.bbcode() + '\n', false);
    });
  }
}
