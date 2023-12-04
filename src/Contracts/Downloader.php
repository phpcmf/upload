<?php

namespace Cmf\Upload\Contracts;

use Cmf\Upload\Commands\Download;
use Cmf\Upload\File;
use Psr\Http\Message\ResponseInterface;

interface Downloader
{
    /**
     * 上载适配器是否适用于特定的 MIME 类型。
     *
     * @param File $file
     *
     * @return bool
     */
    public function forFile(File $file);

    /**
     * @param File     $file
     * @param Download $command
     *
     * @return ResponseInterface
     */
    public function download(File $file, Download $command);
}
