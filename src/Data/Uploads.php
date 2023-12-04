<?php

namespace Cmf\Upload\Data;

use Blomstra\Gdpr\Data\Type;
use Cmf\Upload\Adapters\Manager;
use Cmf\Upload\Downloader\DefaultDownloader;
use Cmf\Upload\File;
use Psr\Log\LoggerInterface;

class Uploads extends Type
{
    public function export(): ?array
    {
        /** @var DefaultDownloader $downloader */
        $downloader = resolve(DefaultDownloader::class);

        $dataExport = [];

        File::query()
            ->where('actor_id', $this->user->id)
            ->orderBy('id', 'asc')
            ->each(function (File $file) use ($downloader, &$dataExport) {
                $fileContent = $downloader->download($file)->getBody()->getContents();
                $dataExport[] = ["uploads/{$file->path}" => $fileContent];
            });

        return $dataExport;
    }

    public function anonymize(): void
    {
        File::query()
            ->where('actor_id', $this->user->id)
            ->update([
                'actor_id' => null,
            ]);
    }

    public function delete(): void
    {
        /** @var Manager $manager */
        $manager = resolve(Manager::class);

        /** @var LoggerInterface $logger */
        $logger = resolve(LoggerInterface::class);

        File::query()
            ->where('actor_id', $this->user->id)
            ->each(function (File $file) use ($manager, $logger) {
                $adaptor = $manager->instantiate($file->upload_method);

                if ($adaptor->delete($file)) {
                    $file->delete();
                } else {
                    $logger->error("[GDPR][Cmf Upload] Could not delete file {$file->id} from disk.");
                }
            });
    }
}
