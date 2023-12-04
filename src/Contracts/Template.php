<?php

namespace Cmf\Upload\Contracts;

use Cmf\Upload\File;

/**
 * 文件模板的基本接口。
 */
interface Template
{
    /**
     * 此模板的唯一标记。
     *
     * @return string
     */
    public function tag(): string;

    /**
     * 模板的人类可读名称。
     *
     * @return string
     */
    public function name(): string;

    /**
     * 澄清此模板的工作原理。
     *
     * @return string
     */
    public function description(): string;

    /**
     * 生成预览 bbcode 字符串。
     *
     * @param File $file
     *
     * @return string
     */
    public function preview(File $file): string;
}
