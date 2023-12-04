<?php

namespace Cmf\Upload\Contracts;

use Illuminate\Contracts\View\View;

/**
 * 需要在 TextFormatter 中注册为 bbcode 的模板的附加接口。
 */
interface TextFormatterTemplate
{
    /**
     * bbcode 定义。
     *
     * @return string
     */
    public function bbcode(): string;

    /**
     * 要与此标记一起使用的 xsl 模板。
     */
    public function template(): View;
}
