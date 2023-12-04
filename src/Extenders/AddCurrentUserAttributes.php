<?php

namespace Cmf\Upload\Extenders;

use Cmf\Api\Serializer\CurrentUserSerializer;
use Cmf\User\User;

class AddCurrentUserAttributes
{
    public function __invoke(CurrentUserSerializer $serializer, User $user, array $attributes): array
    {
        $actor = $serializer->getActor();

        if ($viewOthers = $actor->hasPermission('cmf-upload.viewUserUploads')) {
            $attributes['cmf-upload-viewOthersMediaLibrary'] = $viewOthers;
        }

        if ($deleteOthers = $actor->hasPermission('cmf-upload.deleteUserUploads')) {
            $attributes['cmf-upload-deleteOthersMediaLibrary'] = $deleteOthers;
        }

        return $attributes;
    }
}
