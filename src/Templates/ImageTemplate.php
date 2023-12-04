<?php

namespace Cmf\Upload\Templates;

use Illuminate\Contracts\View\View;

class ImageTemplate extends AbstractTextFormatterTemplate
{
    /**
     * @var string
     */
    protected $tag = 'image';

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->trans('cmf-upload.admin.templates.image');
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return $this->trans('cmf-upload.admin.templates.image_description');
    }

    public function template(): View
    {
        return $this->getView('cmf-upload.templates::image');
    }

    /**
     * {@inheritdoc}
     */
    public function bbcode(): string
    {
        return '[upl-image uuid={IDENTIFIER} size={SIMPLETEXT2} url={URL}]{SIMPLETEXT1}[/upl-image]';
    }
}
