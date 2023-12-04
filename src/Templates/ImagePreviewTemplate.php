<?php

namespace Cmf\Upload\Templates;

use Illuminate\Contracts\View\View;

class ImagePreviewTemplate extends AbstractTextFormatterTemplate
{
    /**
     * @var string
     */
    protected $tag = 'image-preview';

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->trans('cmf-upload.admin.templates.image-preview');
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return $this->trans('cmf-upload.admin.templates.image-preview_description');
    }

    public function template(): View
    {
        return $this->getView('cmf-upload.templates::image-preview');
    }

    /**
     * {@inheritdoc}
     */
    public function bbcode(): string
    {
        return '[upl-image-preview url={URL}]';
    }
}
