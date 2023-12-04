<?php

namespace Cmf\Upload\Events\File;

use Cmf\Upload\Download;
use Cmf\Upload\File;

class WillBeDownloaded
{
    public function __construct(
        public File $file,
        public &$response,
        public ?Download $download = null
    ) {
    }
}
