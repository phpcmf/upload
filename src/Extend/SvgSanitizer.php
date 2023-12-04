<?php

namespace Cmf\Upload\Extend;

use Cmf\Extend\ExtenderInterface;
use Cmf\Extension\Extension;
use Illuminate\Contracts\Container\Container;

class SvgSanitizer implements ExtenderInterface
{
    protected $allowedAttrs = [];

    protected $allowedTags = [];

    protected $removeAttrs = [];

    protected $removeTags = [];

    public function allowAttr(string $attr): self
    {
        $this->allowedAttrs[] = $attr;

        return $this;
    }

    public function allowTag(string $tag): self
    {
        $this->allowedTags[] = $tag;

        return $this;
    }

    public function removeAttr($attr): self
    {
        $this->removeAttrs[] = $attr;

        return $this;
    }

    public function removeTag($tag): self
    {
        $this->removeTags[] = $tag;

        return $this;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        $container->extend('cmf.upload.sanitizer.svg_allowed_attrs', function ($items): array {
            return array_merge($items, $this->allowedAttrs);
        });

        $container->extend('cmf.upload.sanitizer.svg_disallowed_attrs', function ($items): array {
            return array_merge($items, $this->removeAttrs);
        });

        $container->extend('cmf.upload.sanitizer.svg_allowed_tags', function ($items): array {
            return array_merge($items, $this->allowedTags);
        });

        $container->extend('cmf.upload.sanitizer.svg_disallowed_tags', function ($items): array {
            return array_merge($items, $this->removeTags);
        });
    }
}
