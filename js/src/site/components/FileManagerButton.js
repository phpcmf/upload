import app from 'cmf/site/app';
import Component from 'cmf/common/Component';
import Button from 'cmf/common/components/Button';
import FileManagerModal from './FileManagerModal';
import Tooltip from 'cmf/common/components/Tooltip';

export default class FileManagerButton extends Component {
  view() {
    return (
      <Tooltip text={app.translator.trans('cmf-upload.site.buttons.media')}>
        {Button.component({
          className: 'Button cmf-upload-button Button--icon',
          onclick: this.fileManagerButtonClicked.bind(this),
          icon: 'fas fa-photo-video',
        })}
      </Tooltip>
    );
  }

  /**
   * 单击上传按钮的事件处理程序
   *
   * @param {PointerEvent} e
   */
  fileManagerButtonClicked(e) {
    e.preventDefault();

    // 打开对话框
    app.modal.show(FileManagerModal, {
      uploader: this.attrs.uploader,
    });
  }
}
