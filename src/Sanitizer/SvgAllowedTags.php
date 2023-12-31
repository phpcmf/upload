<?php

namespace Cmf\Upload\Sanitizer;

use enshrined\svgSanitize\data\AllowedTags;

class SvgAllowedTags extends AllowedTags
{
    public static function getTags(): array
    {
        return array_diff(
            array_merge(parent::getTags(), resolve('cmf.upload.sanitizer.svg_allowed_tags')),
            resolve('cmf.upload.sanitizer.svg_disallowed_tags')
        );
    }
}
