<?php

namespace Cmf\Upload\Contracts;

use Cmf\Upload\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadAdapter
{
    /**
     * 上载适配器是否适用于特定的 MIME 类型。
     *
     * @param string $mime
     *
     * @return bool
     */
    public function forMime($mime);

    /**
     * 上传是否支持流。
     *
     * @return bool
     */
    public function supportsStreams();

    /**
     * 尝试上传到（远程）文件系统。
     *
     * @param File         $file
     * @param UploadedFile $upload
     * @param string       $contents
     *
     * @return File|bool
     */
    public function upload(File $file, UploadedFile $upload, $contents);

    /**
     * 如果无法删除，则返回 false。
     *
     * @param File $file
     *
     * @return File|bool
     */
    public function delete(File $file);
}
