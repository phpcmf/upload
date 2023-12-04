<?php

namespace Cmf\Upload\Adapters;

use Cmf\Foundation\ValidationException;
use Cmf\Settings\SettingsRepositoryInterface;
use Cmf\Upload\Contracts\UploadAdapter;
use Cmf\Upload\File;

class Qiniu extends Flysystem implements UploadAdapter
{
    protected function generateUrl(File $file)
    {
        /** @var SettingsRepositoryInterface $settings */
        $settings = resolve(SettingsRepositoryInterface::class);
        $path = $file->getAttribute('path');
        if ($cdnUrl = $settings->get('cmf-upload.cdnUrl')) {
            $file->url = sprintf('%s/%s', $cdnUrl, $path);
        } else {
            throw new ValidationException(['upload' => 'QiNiu cloud CDN address is not configured.']);
        }
    }
}
