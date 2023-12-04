<?php

namespace Cmf\Upload\Events\File;

use Cmf\User\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class IsSlugged
{
    public function __construct(
        public UploadedFile $file,
        public User $user,
        public string $mime,
        public string $slug
    ) {
    }
}
