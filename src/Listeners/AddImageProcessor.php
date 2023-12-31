<?php

namespace Cmf\Upload\Listeners;

use Cmf\Upload\Events\File\WillBeUploaded;
use Cmf\Upload\Processors\ImageProcessor;

class AddImageProcessor
{
    /**
     * @var ImageProcessor
     */
    public $processor;

    public function __construct(ImageProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function handle(WillBeUploaded $event)
    {
        if ($this->validateMime($event->mime)) {
            $this->processor->process($event->file, $event->uploadedFile, $event->mime);
        }
    }

    protected function validateMime($mime): bool
    {
        if ($mime == 'image/jpeg' || $mime == 'image/png' || $mime == 'image/gif' || $mime == 'image/svg+xml') {
            return true;
        }

        return false;
    }
}
