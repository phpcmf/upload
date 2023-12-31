<?php

namespace Cmf\Upload\Templates;

use Cmf\Upload\File;

class JustUrlTemplate extends AbstractTemplate
{
    /**
     * @var string
     */
    protected $tag = 'just-url';

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->trans('cmf-upload.admin.templates.just-url');
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return $this->trans('cmf-upload.admin.templates.just-url_description');
    }

    /**
     * {@inheritdoc}
     */
    public function preview(File $file): string
    {
        return $file->url;
    }
}
