<?php

namespace Cmf\Upload\Events\Adapter;

use Cmf\Upload\Helpers\Util;

class Instantiate
{
    /** @var string */
    public $adapter;
    /** @var Util */
    public $util;

    public function __construct(string $adapter, Util $util)
    {
        $this->adapter = $adapter;
        $this->util = $util;
    }
}
