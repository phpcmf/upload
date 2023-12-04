<?php

namespace Cmf\Upload\Adapters;

use Cmf\Settings\SettingsRepositoryInterface;
use Cmf\Upload\Contracts\UploadAdapter;
use Cmf\Upload\File;
use Illuminate\Support\Arr;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Config;

class AwsS3 extends Flysystem implements UploadAdapter
{
    /**
     * @var AwsS3Adapter
     */
    protected $adapter;

    protected function getConfig()
    {
        /** @var SettingsRepositoryInterface $settings */
        $settings = resolve(SettingsRepositoryInterface::class);

        $config = new Config();
        if ($acl = $settings->get('cmf-upload.awsS3ACL')) {
            $config->set('ACL', $acl);
        }

        return $config;
    }

    protected function generateUrl(File $file)
    {
        /** @var SettingsRepositoryInterface $settings */
        $settings = resolve(SettingsRepositoryInterface::class);

        $cdnUrl = $settings->get('cmf-upload.cdnUrl');

        if (!$cdnUrl) {
            $region = $this->adapter->getClient()->getRegion();
            $bucket = $this->adapter->getBucket();

            $cdnUrl = sprintf('https://%s.s3.%s.amazonaws.com', $bucket, $region ?: 'us-east-1');
        }

        $file->url = sprintf('%s/%s', $cdnUrl, Arr::get($this->meta, 'path', $file->path));
    }
}
