<?php

namespace Cmf\Upload\Templates;

use Cmf\Upload\File;

class MarkdownImageTemplate extends AbstractTemplate
{
    /**
     * @var string
     */
    protected $tag = 'markdown-image';

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->trans('cmf-upload.admin.templates.markdown-image');
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return $this->trans('cmf-upload.admin.templates.markdown-image_description');
    }

    /**
     * {@inheritdoc}
     */
    public function preview(File $file): string
    {
        return '![Image description]('.$file->url.')';
    }
}
