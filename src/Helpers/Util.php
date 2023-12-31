<?php

namespace Cmf\Upload\Helpers;

use Cmf\Settings\SettingsRepositoryInterface;
use Cmf\Upload\Adapters\Manager;
use Cmf\Upload\Contracts\Template;
use Cmf\Upload\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Util
{
    const DEFAULT_MAX_FILE_SIZE = 2048;
    const DEFAULT_MAX_IMAGE_WIDTH = 100;

    /**
     * The templates used to render files.
     *
     * @var array
     */
    protected $renderTemplates = [];

    /**
     * @return Collection
     */
    public function getAvailableUploadMethods()
    {
        /** @var Manager $manager */
        $manager = resolve(Manager::class);

        return $manager->adapters()
            ->filter(function ($available) {
                return $available;
            })
            ->map(function ($available, $item) {
                return resolve('translator')->trans('cmf-upload.admin.upload_methods.'.$item);
            });
    }

    public function getJsonValue($json, $default = null, $attribute = null)
    {
        if (empty($json)) {
            return $default;
        }

        $collect = collect(json_decode($json, true));

        if ($attribute) {
            return $collect->get($attribute, $default);
        }

        return $collect;
    }

    /**
     * @return Collection
     */
    public function getMimeTypesConfiguration()
    {
        $settings = resolve(SettingsRepositoryInterface::class);
        $mimeTypes = $settings->get('cmf-upload.mimeTypes');

        $adapters = $this->getAvailableUploadMethods();

        return $this->getJsonValue(
            $mimeTypes,
            collect(['^image\/.*' => ['adapter' => $adapters->flip()->last(), 'template' => 'image-preview']])
        )->filter();
    }

    /**
     * @param Template $template
     */
    public function addRenderTemplate(Template $template)
    {
        $this->renderTemplates[$template->tag()] = $template;
    }

    /**
     * @return Template[]
     */
    public function getRenderTemplates()
    {
        return $this->renderTemplates;
    }

    /**
     * @param Template[] $templates
     */
    public function setRenderTemplates(array $templates)
    {
        $this->renderTemplates = $templates;
    }

    /**
     * @return Collection
     */
    public function getAvailableTemplates()
    {
        $collect = [];

        /**
         * @var Template $template
         */
        foreach ($this->renderTemplates as $tag => $template) {
            $collect[$tag] = [
                'name'        => $template->name(),
                'description' => $template->description(),
            ];
        }

        return collect($collect);
    }

    /**
     * @param string $template
     *
     * @return Template|null
     */
    public function getTemplate($template)
    {
        return Arr::get($this->renderTemplates, $template);
    }

    /**
     * @param File $file
     *
     * @return string|null
     */
    public function getBbcodeForFile(File $file): ?string
    {
        $template = $this->getTemplate($file->tag);

        return $template ? $template->preview($file) : null;
    }
}
