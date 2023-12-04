<?php

namespace Cmf\Upload\Listeners;

use Cmf\Post\Event\Posted;
use Cmf\Post\Event\Revised;
use Cmf\Upload\Repositories\FileRepository;

class LinkImageToPostOnSave
{
    private FileRepository $files;

    public function __construct(FileRepository $files)
    {
        $this->files = $files;
    }

    public function handle(Posted|Revised $event)
    {
        $this->files->matchFilesForPost($event->post);
    }
}
