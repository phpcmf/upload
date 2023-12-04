<?php

namespace Cmf\Upload\Commands;

use Cmf\Settings\SettingsRepositoryInterface;
use Cmf\Upload\Contracts\Downloader;
use Cmf\Upload\Events\File\WasLoaded;
use Cmf\Upload\Events\File\WillBeDownloaded;
use Cmf\Upload\Exceptions\InvalidDownloadException;
use Cmf\Upload\Repositories\FileRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;

class DownloadHandler
{
    protected static $downloader = [];

    /**
     * @var FileRepository
     */
    private $files;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @var SettingsRepositoryInterface
     */
    private $settings;

    public function __construct(FileRepository $files, Dispatcher $events, SettingsRepositoryInterface $settings)
    {
        $this->files = $files;
        $this->events = $events;
        $this->settings = $settings;
    }

    /**
     * @param Download $command
     *
     * @throws InvalidDownloadException
     * @throws \Cmf\User\Exception\PermissionDeniedException
     * @throws InvalidDownloadException
     *
     * @return mixed
     */
    public function handle(Download $command)
    {
        $command->actor->assertCan('cmf-upload.download');

        $file = $this->files->findByUuid($command->uuid);

        if (!$file) {
            throw new ModelNotFoundException();
        }

        $this->events->dispatch(
            new WasLoaded($file)
        );

        foreach (static::downloader() as $downloader) {
            if ($downloader->forFile($file)) {
                $response = $downloader->download($file, $command);

                if (!$response) {
                    continue;
                }

                $download = null;

                if ($this->settings->get('cmf-upload.disableDownloadLogging') != 1) {
                    $download = $this->files->downloadedEntry($file, $command);
                }

                $this->events->dispatch(
                    new WillBeDownloaded($file, $response, $download)
                );

                return $response;
            }
        }

        throw new InvalidDownloadException();
    }

    public static function prependDownloader(Downloader $downloader)
    {
        static::$downloader = Arr::prepend(static::$downloader, $downloader);
    }

    public static function addDownloader(Downloader $downloader)
    {
        static::$downloader[] = $downloader;
    }

    public static function downloader()
    {
        return static::$downloader;
    }
}
