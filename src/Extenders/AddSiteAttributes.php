<?php

namespace Cmf\Upload\Extenders;

use Cmf\Api\Serializer\SiteSerializer;
use Cmf\Settings\SettingsRepositoryInterface;

class AddSiteAttributes
{
    private $settings;

    /**
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param SiteSerializer $serializer
     */
    public function __invoke(SiteSerializer $serializer)
    {
        $attributes['cmf-upload.canUpload'] = $serializer->getActor()->can('cmf-upload.upload');
        $attributes['cmf-upload.canDownload'] = $serializer->getActor()->can('cmf-upload.download');
        $attributes['cmf-upload.composerButtonVisiblity'] = $this->settings->get('cmf-upload.composerButtonVisiblity', 'both');

        $serializer->getActor()->load('cmffiles');
        $serializer->getActor()->load('cmffilesCurrent');

        return $attributes;
    }
}
