<?php

namespace Cmf\Upload\Extenders;

use Cmf\Api\Serializer\UserSerializer;
use Cmf\User\User;

class AddUserAttributes
{
    public function __invoke(UserSerializer $serializer, User $user, array $attributes): array
    {
        /** @phpstan-ignore-next-line */
        $attributes['cmf-upload-uploadCountCurrent'] = $user->cmffiles_current_count;
        /** @phpstan-ignore-next-line */
        $attributes['cmf-upload-uploadCountAll'] = $user->cmffiles_count;

        return $attributes;
    }
}
