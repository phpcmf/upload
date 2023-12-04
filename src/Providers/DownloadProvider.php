<?php

namespace Cmf\Upload\Providers;

use Cmf\Foundation\AbstractServiceProvider;
use Cmf\Upload\Commands\DownloadHandler;
use Cmf\Upload\Downloader\DefaultDownloader;
use Cmf\Upload\Helpers\Util;
use Cmf\Upload\Templates\BbcodeImageTemplate;
use Cmf\Upload\Templates\FileTemplate;
use Cmf\Upload\Templates\ImagePreviewTemplate;
use Cmf\Upload\Templates\ImageTemplate;
use Cmf\Upload\Templates\JustUrlTemplate;
use Cmf\Upload\Templates\MarkdownImageTemplate;
use Cmf\Upload\Templates\TextPreviewTemplate;

class DownloadProvider extends AbstractServiceProvider
{
    public function register()
    {
        DownloadHandler::addDownloader(
            $this->container->make(DefaultDownloader::class)
        );

        /** @var Util $util */
        $util = $this->container->make(Util::class);

        $util->addRenderTemplate($this->container->make(FileTemplate::class));
        $util->addRenderTemplate($this->container->make(ImageTemplate::class));
        $util->addRenderTemplate($this->container->make(ImagePreviewTemplate::class));
        $util->addRenderTemplate($this->container->make(JustUrlTemplate::class));
        $util->addRenderTemplate($this->container->make(MarkdownImageTemplate::class));
        $util->addRenderTemplate($this->container->make(BbcodeImageTemplate::class));
        $util->addRenderTemplate($this->container->make(TextPreviewTemplate::class));
    }
}
