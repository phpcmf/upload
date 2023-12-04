<?php

namespace Cmf\Upload\Validators;

use Cmf\Foundation\AbstractValidator;
use Cmf\Settings\SettingsRepositoryInterface;
use Cmf\Upload\Helpers\Util;
use Symfony\Contracts\Translation\TranslatorInterface;

class UploadValidator extends AbstractValidator
{
    protected function getRules(): array
    {
        return [
            'file' => [
                'required',
                'max:'.$this->maxFilesize(),
            ],
        ];
    }

    protected function getMessages()
    {
        /** @var TranslatorInterface $translator */
        $translator = resolve('translator');

        return [
            'max' => $translator->trans('cmf-upload.site.validation.max_size', [
                'max' => $this->maxFilesize(),
            ]),
        ];
    }

    protected function maxFilesize(): int
    {
        /** @var SettingsRepositoryInterface $settings */
        $settings = resolve(SettingsRepositoryInterface::class);

        return $settings->get('cmf-upload.maxFileSize', Util::DEFAULT_MAX_FILE_SIZE);
    }
}
