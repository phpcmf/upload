<?php

namespace Cmf\Upload\Extenders;

use Cmf\Extend\ExtenderInterface;
use Cmf\Extend\LifecycleInterface;
use Cmf\Extension\Extension;
use Cmf\Foundation\Paths;
use Illuminate\Contracts\Container\Container;

class CreateStorageFolder implements ExtenderInterface, LifecycleInterface
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function onEnable(Container $container, Extension $extension)
    {
        @mkdir($container->make(Paths::class)->storage.DIRECTORY_SEPARATOR.$this->path);
    }

    public function onDisable(Container $container, Extension $extension)
    {
        // Nee, no, nein, nada, pas de rein.
    }

    public function extend(Container $container, Extension $extension = null)
    {
        // TODO: Clark thinks that this line should be removed.
        // Debating with him is tiring, because he's right.
        // So here it is, if you close your eyes, it's gone.
    }
}
