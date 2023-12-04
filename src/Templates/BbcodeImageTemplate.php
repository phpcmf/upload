<?php

namespace Cmf\Upload\Templates;

use Cmf\Upload\File;

class BbcodeImageTemplate extends AbstractTemplate
{
    /**
     * @var string
     */
    protected $tag = 'bbcode-image';

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->trans('cmf-upload.admin.templates.bbcode-image');
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return $this->trans('cmf-upload.admin.templates.bbcode-image_description');
    }

    /**
     * {@inheritdoc}
     */
    public function preview(File $file): string
    {
        return '[URL='.$file->url.'][IMG]'.$file->url.'[/IMG][/URL]';
    }
}
