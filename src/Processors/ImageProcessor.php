<?php

namespace Cmf\Upload\Processors;

use Cmf\Foundation\Paths;
use Cmf\Foundation\ValidationException;
use Cmf\Settings\SettingsRepositoryInterface;
use Cmf\Upload\Contracts\Processable;
use Cmf\Upload\File;
use Cmf\Upload\Helpers\Util;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageProcessor implements Processable
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var Paths
     */
    protected $paths;

    public function __construct(SettingsRepositoryInterface $settings, Paths $paths)
    {
        $this->settings = $settings;
        $this->paths = $paths;
    }

    public function process(File $file, UploadedFile $upload, string $mimeType)
    {
        if ($mimeType == 'image/jpeg' || $mimeType == 'image/png') {
            try {
                $image = (new ImageManager())->make($upload->getRealPath());
            } catch (NotReadableException $e) {
                throw new ValidationException(['upload' => 'Corrupted image']);
            }

            if ($this->settings->get('cmf-upload.mustResize')) {
                $this->resize($image);
            }

            if ($this->settings->get('cmf-upload.addsWatermarks')) {
                $this->watermark($image);
            }

            $image->orientate();

            @file_put_contents(
                $upload->getRealPath(),
                $image->encode($mimeType)
            );
        }
    }

    /**
     * @param Image $manager
     */
    protected function resize(Image $manager)
    {
        $maxSize = $this->settings->get('cmf-upload.resizeMaxWidth', Util::DEFAULT_MAX_IMAGE_WIDTH);
        $manager->resize(
            $maxSize,
            $maxSize,
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            }
        );
    }

    /**
     * @param Image $image
     */
    protected function watermark(Image $image)
    {
        if ($this->settings->get('cmf-upload.watermark')) {
            $image->insert(
                $this->paths->storage.DIRECTORY_SEPARATOR.$this->settings->get('cmf-upload.watermark'),
                $this->settings->get('cmf-upload.watermarkPosition', 'bottom-right')
            );
        }
    }
}
