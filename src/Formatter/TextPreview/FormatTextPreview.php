<?php

namespace Cmf\Upload\Formatter\TextPreview;

use Cmf\Foundation\Paths;
use Cmf\Upload\Repositories\FileRepository;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

class FormatTextPreview
{
    /**
     * @var FileRepository
     */
    private $files;

    /**
     * @var Paths
     */
    private $paths;

    public function __construct(FileRepository $files, Paths $paths)
    {
        $this->files = $files;
        $this->paths = $paths;
    }

    /**
     * Configure rendering for text preview uploads.
     *
     * @param Renderer $renderer
     * @param mixed    $context
     * @param string   $xml
     *
     * @return string $xml to be rendered
     */
    public function __invoke(Renderer $renderer, $context, string $xml)
    {
        return Utils::replaceAttributes($xml, 'UPL-TEXT-PREVIEW', function ($attributes) {
            $file = $this->files->findByUuid($attributes['uuid']);

            $attributes['has_snippet'] = 'true';
            $snippet = '';

            if ($file) {
                $file_contents = file_get_contents($this->paths->public.'/assets/files/'.$file->path);

                $file_contents_normalised = str_replace(["\r\n", "\r", "\n"], "\n", $file_contents);

                // automatically normalises line endings
                $lines = explode("\n", $file_contents_normalised);
                $first_five_lines = array_slice($lines, 0, 5);
                $snippet = implode("\n", $first_five_lines);

                if ($snippet !== $file_contents_normalised) {
                    $attributes['has_snippet'] = 'false';
                }
            }

            $attributes['snippet'] = $snippet;

            return $attributes;
        });
    }
}
