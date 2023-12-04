import app from 'cmf/site/app';
import Component from 'cmf/common/Component';
import Button from 'cmf/common/components/Button';
import LoadingIndicator from 'cmf/common/components/LoadingIndicator';
import classList from 'cmf/common/utils/classList';
import Tooltip from 'cmf/common/components/Tooltip';

export default class UploadButton extends Component {
  oninit(vnode) {
    super.oninit(vnode);

    this.attrs.uploader.on('uploaded', () => {
      // 重置新上传的按钮
      this.$('form')[0].reset();

      // 重新绘制以反映 DOM 中的 uploader.loading
      m.redraw();
    });

    this.isMediaUploadButton = vnode.attrs.isMediaUploadButton || false;
  }

  view() {
    const buttonText = this.attrs.uploader.uploading
      ? app.translator.trans('cmf-upload.site.states.loading')
      : app.translator.trans('cmf-upload.site.buttons.upload');

    return (
      <Tooltip text={buttonText}>
        <Button
          className={classList([
            'Button',
            'hasIcon',
            'cmf-upload-button',
            !this.isMediaUploadButton && !this.attrs.uploader.uploading && 'Button--icon',
            !this.isMediaUploadButton && !this.attrs.uploader.uploading && 'Button--link',
            this.attrs.uploader.uploading && 'uploading',
          ])}
          icon={!this.attrs.uploader.uploading && 'fas fa-file-upload'}
          onclick={this.uploadButtonClicked.bind(this)}
          disabled={this.attrs.disabled}
        >
          {this.attrs.uploader.uploading && <LoadingIndicator size="small" display="inline" className="Button-icon" />}
          {(this.isMediaUploadButton || this.attrs.uploader.uploading) && <span className="Button-label">{buttonText}</span>}
          <form>
            <input type="file" multiple={true} onchange={this.process.bind(this)} />
          </form>
        </Button>
      </Tooltip>
    );
  }

  /**
   * 处理上传事件。
   *
   * @param e
   */
  process(e) {
    // 从输入字段中获取文件
    const files = this.$('input').prop('files');

    if (files.length === 0) {
      // 我们没有要上传的文件，因此尝试开始上传将向用户显示错误。
      return;
    }

    this.attrs.uploader.upload(files, !this.isMediaUploadButton);
  }

  /**
   * 单击上载按钮的事件处理程序
   *
   * @param {PointerEvent} e
   */
  uploadButtonClicked(e) {
    // 触发对隐藏输入元素的单击（打开文件对话框）
    this.$('input').click();
  }
}
