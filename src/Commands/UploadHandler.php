<?php

namespace Cmf\Upload\Commands;

use enshrined\svgSanitize\Sanitizer;
use Exception;
use Cmf\Foundation\Application;
use Cmf\Foundation\ValidationException;
use Cmf\Locale\Translator;
use Cmf\Upload\Adapters\Manager;
use Cmf\Upload\Contracts\UploadAdapter;
use Cmf\Upload\Events;
use Cmf\Upload\File;
use Cmf\Upload\Helpers\Util;
use Cmf\Upload\Repositories\FileRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\UploadedFileInterface;
use SoftCreatR\MimeDetector\MimeDetector;
use SoftCreatR\MimeDetector\MimeDetectorException;

class UploadHandler
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Util
     */
    protected $util;

    /**
     * @var Dispatcher
     */
    protected $events;
    /**
     * @var FileRepository
     */
    protected $files;

    /**
     * @var MimeDetector
     */
    protected $mimeDetector;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var Sanitizer
     */
    protected $sanitizer;

    public function __construct(
        Application $app,
        Dispatcher $events,
        Util $util,
        FileRepository $files,
        MimeDetector $mimeDetector,
        Translator $translator,
        Sanitizer $sanitizer
    ) {
        $this->app = $app;
        $this->util = $util;
        $this->events = $events;
        $this->files = $files;
        $this->mimeDetector = $mimeDetector;
        $this->translator = $translator;
        $this->sanitizer = $sanitizer;
    }

    /**
     * @param Upload $command
     *
     * @throws \Cmf\User\Exception\PermissionDeniedException
     *
     * @return \Illuminate\Support\Collection
     */
    public function handle(Upload $command)
    {
        $command->actor->assertCan('cmf-upload.upload');

        $savedFiles = $command->files->map(function (UploadedFileInterface $file) use ($command) {
            try {
                $upload = $this->files->moveUploadedFileToTemp($file);

                try {
                    $this->mimeDetector->setFile($upload->getPathname());
                } catch (MimeDetectorException $e) {
                    throw new ValidationException(['upload' => $this->translator->trans('cmf-upload.api.upload_errors.could_not_detect_mime')]);
                }

                $uploadFileData = $this->mimeDetector->getFileType();

                if (Arr::get($uploadFileData, 'mime')) {
                    try {
                        $uploadFileData['mime'] = mime_content_type($upload->getPathname());
                    } catch (Exception $e) {
                        throw new ValidationException(['upload' => $this->translator->trans('cmf-upload.api.upload_errors.could_not_detect_mime')]);
                    }
                }

                // If an SVG has been uploaded, remove any unwanted tags & attrs, if possible, else throw a validation error
                if (Str::startsWith($uploadFileData['mime'], 'image/svg')) {
                    // Will return false if sanitization fails, else will return the clean SVG contents.
                    $cleanSvg = $this->sanitizer->sanitize(file_get_contents($upload->getPathname()));

                    if (!$cleanSvg) {
                        //TODO maybe expose the error list via ValidationException?
                        //$issues = $this->sanitizer->getXmlIssues();
                        throw new ValidationException(['upload' => $this->translator->trans('cmf-upload.api.upload_errors.svg_failure')]);
                    }

                    file_put_contents($upload->getPathname(), $cleanSvg, LOCK_EX);
                }

                $mimeConfiguration = $this->getMimeConfiguration($uploadFileData['mime']);
                $adapter = $this->getAdapter(Arr::get($mimeConfiguration, 'adapter'));
                $template = $this->getTemplate(Arr::get($mimeConfiguration, 'template', 'file'));

                $this->events->dispatch(
                    new Events\Adapter\Identified($command->actor, $upload, $adapter)
                );

                if (!$adapter) {
                    throw new ValidationException(['upload' => $this->translator->trans('cmf-upload.api.upload_errors.forbidden_type')]);
                }

                if (!$adapter->forMime($uploadFileData['mime'])) {
                    throw new ValidationException(['upload' => resolve('translator')->trans('cmf-upload.api.upload_errors.unsupported_type', ['mime' => $uploadFileData['mime']])]);
                }

                $file = $this->files->createFileFromUpload($upload, $command->actor, $uploadFileData['mime']);

                $this->events->dispatch(
                    new Events\File\WillBeUploaded($command->actor, $file, $upload, $uploadFileData['mime'])
                );

                $response = $adapter->upload(
                    $file,
                    $upload,
                    $this->files->readUpload($upload, $adapter)
                );

                $this->files->removeFromTemp($upload);

                if (!($response instanceof File)) {
                    return false;
                }

                $file = $response;

                $file->upload_method = Str::lower(Str::afterLast($adapter::class, '\\'));
                $file->tag = $template;
                $file->actor_id = $command->actor->id;

                $this->events->dispatch(
                    new Events\File\WillBeSaved($command->actor, $file, $upload, $uploadFileData['mime'])
                );

                if ($file->isDirty() || !$file->exists) {
                    $file->save();
                }

                $this->events->dispatch(
                    new Events\File\WasSaved($command->actor, $file, $upload, $uploadFileData['mime'])
                );
            } catch (Exception $e) {
                $this->files->removeFromTemp($upload);

                throw $e;
            }

            return $file;
        });

        return $savedFiles->filter();
    }

    /**
     * @param $adapter
     *
     * @return UploadAdapter|null
     */
    protected function getAdapter($adapter)
    {
        if (!$adapter) {
            return null;
        }

        /** @var Manager $manager */
        $manager = resolve(Manager::class);

        return $manager->instantiate($adapter);
    }

    protected function getTemplate($template)
    {
        return $this->util->getTemplate($template);
    }

    /**
     * @param $mime
     *
     * @return mixed
     */
    protected function getMimeConfiguration($mime)
    {
        return $this->util->getMimeTypesConfiguration()->first(function ($_, $regex) use ($mime) {
            return preg_match("/$regex/", $mime);
        });
    }
}
