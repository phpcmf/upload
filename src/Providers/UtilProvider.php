<?php

namespace Cmf\Upload\Providers;

use Cmf\Foundation\AbstractServiceProvider;
use Cmf\Upload\Helpers\Util;

class UtilProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->singleton(Util::class);
    }
}
