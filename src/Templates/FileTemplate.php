<?php

namespace Cmf\Upload\Templates;

use Illuminate\Contracts\View\View;

class FileTemplate extends AbstractTextFormatterTemplate
{
    /**
     * @var string
     */
    protected $tag = 'file';

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->trans('cmf-upload.admin.templates.file');
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return $this->trans('cmf-upload.admin.templates.file_description');
    }

    public function template(): View
    {
        return $this->getView('cmf-upload.templates::file');
    }

    /**
     * {@inheritdoc}
     */
    public function bbcode(): string
    {
        return '[upl-file uuid={IDENTIFIER} size={SIMPLETEXT2}]{SIMPLETEXT1}[/upl-file]';
    }
}
