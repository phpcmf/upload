<?php

namespace Cmf\Upload\Events\File;

use Cmf\User\User;
use Cmf\Upload\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class Event
{
    public function __construct(
        public User $actor,
        public File $file,
        public UploadedFile $uploadedFile,
        public string $mime
    ) {
    }
}
