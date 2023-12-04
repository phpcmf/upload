<?php

namespace Cmf\Upload\Api\Controllers;

use Cmf\Foundation\ValidationException;
use Cmf\User\Exception\PermissionDeniedException;
use Cmf\User\User;
use Cmf\Upload\File;
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HideUploadFromMediaManagerController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var User */
        $actor = $request->getAttribute('actor');
        $actor->assertRegistered();

        $uuid = Arr::get($request->getParsedBody(), 'uuid');

        if (empty($uuid)) {
            throw new ValidationException(['UUID cannot be empty']);
        }

        $fileUpload = File::where('uuid', $uuid)->firstOrFail();

        // If the actor does not own the file and the actor does not have edit uploads of others permission..
        if ($actor->id !== $fileUpload->actor_id && !$actor->hasPermission('cmf-upload.deleteUserUploads')) {
            throw new PermissionDeniedException();
        }

        $fileUpload->hide_from_media_manager = true;
        $fileUpload->save();

        return new EmptyResponse(202);
    }
}
