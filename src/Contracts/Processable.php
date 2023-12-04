<?php

namespace Cmf\Upload\Contracts;

use Cmf\Upload\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface Processable
{
    public function process(File $file, UploadedFile $upload, string $mime);
}
