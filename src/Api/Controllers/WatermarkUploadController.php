<?php

namespace Cmf\Upload\Api\Controllers;

use Cmf\Api\Controller\ShowSiteController;
use Cmf\Foundation\Paths;
use Cmf\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WatermarkUploadController extends ShowSiteController
{
    protected $settings;
    protected $paths;

    public function __construct(SettingsRepositoryInterface $settings, Paths $paths)
    {
        $this->settings = $settings;
        $this->paths = $paths;
    }

    public function data(ServerRequestInterface $request, Document $document)
    {
        $request->getAttribute('actor')->assertAdmin();

        $file = Arr::get($request->getUploadedFiles(), 'cmf/watermark');

        $tmpFile = tempnam($this->paths->storage.'/tmp', 'cmf-watermark');

        $file->moveTo($tmpFile);

        $mount = new MountManager([
            'source' => new Filesystem(new Local(pathinfo($tmpFile, PATHINFO_DIRNAME))),
            'target' => new Filesystem(new Local($this->paths->storage)),
        ]);

        if (($path = $this->settings->get('cmf-upload.watermark')) && $mount->has($file = "target://$path")) {
            $mount->delete($file);
        }

        $uploadName = 'cmf-upload-watermark-'.Str::lower(Str::random(8));

        $mount->move('source://'.pathinfo($tmpFile, PATHINFO_BASENAME), "target://$uploadName");

        $this->settings->set('cmf-upload.watermark', $uploadName);

        return parent::data($request, $document);
    }
}
