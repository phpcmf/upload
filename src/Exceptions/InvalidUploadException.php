<?php

namespace Cmf\Upload\Exceptions;

use Symfony\Contracts\Translation\TranslatorInterface;

class InvalidUploadException extends \Exception
{
    public $type = null;
    public $status = null;
    public $params = [];

    public function __construct(string $type, int $status, array $params = [])
    {
        $this->type = $type;
        $this->status = $status;
        $this->params = $params;

        parent::__construct(
            $this->constructMessage()
        );
    }

    public function errors(): array
    {
        return [
            'upload' => [$this->getMessage()],
        ];
    }

    protected function constructMessage()
    {
        return resolve(TranslatorInterface::class)->trans('cmf-upload.api.upload_errors.'.$this->type, $this->params);
    }
}
