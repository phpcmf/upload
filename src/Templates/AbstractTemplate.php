<?php

namespace Cmf\Upload\Templates;

use Cmf\Upload\Contracts\Template;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

abstract class AbstractTemplate implements Template
{
    /**
     * @var string
     */
    protected $tag;

    public function tag(): string
    {
        return $this->tag;
    }

    protected function getView(string $view, array $arguments = []): View
    {
        return resolve(Factory::class)->make($view, $arguments);
    }

    /**
     * @param       $key
     * @param array $params
     *
     * @return mixed
     */
    protected function trans($key, array $params = [])
    {
        return resolve('translator')->trans($key, $params);
    }
}
