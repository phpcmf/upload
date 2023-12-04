<?php

namespace Cmf\Upload\Events\File;

use Cmf\Upload\File;

class WasLoaded
{
    public function __construct(public File $file)
    {
    }
}
