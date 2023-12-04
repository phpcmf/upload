<?php

namespace Cmf\Upload\Providers;

use enshrined\svgSanitize\Sanitizer;
use Cmf\Foundation\AbstractServiceProvider;
use Cmf\Upload\Sanitizer\SvgAllowedAttrs;
use Cmf\Upload\Sanitizer\SvgAllowedTags;

class SanitizerProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->singleton(Sanitizer::class, function (): Sanitizer {
            $sanitizer = new Sanitizer();
            $sanitizer->setAllowedAttrs(new SvgAllowedAttrs());
            $sanitizer->setAllowedTags(new SvgAllowedTags());
            $sanitizer->removeRemoteReferences(true);

            return $sanitizer;
        });

        $this->container->singleton('cmf.upload.sanitizer.svg_allowed_attrs', function (): array {
            return [];
        });

        $this->container->singleton('cmf.upload.sanitizer.svg_disallowed_attrs', function (): array {
            return [];
        });

        $this->container->singleton('cmf.upload.sanitizer.svg_allowed_tags', function (): array {
            return [];
        });

        $this->container->singleton('cmf.upload.sanitizer.svg_disallowed_tags', function (): array {
            return [];
        });
    }
}
