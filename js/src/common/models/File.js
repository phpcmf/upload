import Model from 'cmf/common/Model';
import mixin from 'cmf/common/utils/mixin';

export default class File extends mixin(Model, {
  baseName: Model.attribute('baseName'),
  path: Model.attribute('path'),
  url: Model.attribute('url'),
  type: Model.attribute('type'),
  size: Model.attribute('size'),
  humanSize: Model.attribute('humanSize'),
  createdAt: Model.attribute('createdAt'),
  uuid: Model.attribute('uuid'),
  tag: Model.attribute('tag'),
  hidden: Model.attribute('hidden'),
  bbcode: Model.attribute('bbcode'),
}) {
  /**
   * Use Cmf Uploads endpoint
   */
  apiEndpoint() {
    return '/cmf/uploads' + (this.exists ? '/' + this.data.id : '');
  }
}
