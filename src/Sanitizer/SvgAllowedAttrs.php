<?php

namespace Cmf\Upload\Sanitizer;

use enshrined\svgSanitize\data\AllowedAttributes;

class SvgAllowedAttrs extends AllowedAttributes
{
    public static function getAttributes(): array
    {
        return array_diff(
            array_merge(parent::getAttributes(), resolve('cmf.upload.sanitizer.svg_allowed_attrs')),
            resolve('cmf.upload.sanitizer.svg_disallowed_attrs')
        );
    }
}
