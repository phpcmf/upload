<?php

namespace Cmf\Upload\Api\Serializers;

use Cmf\Api\Serializer\AbstractSerializer;
use Cmf\Upload\File;
use Cmf\Upload\Helpers\Util;

class FileSerializer extends AbstractSerializer
{
    protected $type = 'files';

    public function __construct(protected Util $util)
    {
    }

    /**
     * 获取模型的默认序列化属性集。
     *
     * @param File $model
     *
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'baseName'  => $model->base_name,
            'path'      => $model->path,
            'url'       => $model->url,
            'type'      => $model->type,
            'size'      => $model->size,
            'humanSize' => $model->humanSize,
            'createdAt' => $model->created_at,
            'uuid'      => $model->uuid,
            'tag'       => $model->tag,
            'hidden'    => $model->hide_from_media_manager,
            'bbcode'    => $this->util->getBbcodeForFile($model),
        ];
    }
}
