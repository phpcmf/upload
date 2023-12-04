<?php

namespace Cmf\Upload\Extend;

use Cmf\Extend\ExtenderInterface;
use Cmf\Extension\Extension;
use Cmf\Upload\Events\Adapter\Collecting;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;

class Adapters implements ExtenderInterface
{
    protected $disabling = [];
    protected $force;

    public function disable(string $adapter)
    {
        $this->disabling[] = $adapter;

        return $this;
    }

    public function force(string $adapter)
    {
        $this->force = $adapter;

        return $this;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        /** @var Dispatcher $events */
        $events = $container->make(Dispatcher::class);

        $events->listen(Collecting::class, function (Collecting $event) {
            if ($force = $this->force) {
                $event->adapters = $event->adapters->only($force);
            } else {
                $event->adapters->forget($this->disabling);
            }
        });
    }
}
