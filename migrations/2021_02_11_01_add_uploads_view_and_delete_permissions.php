<?php

use Cmf\Database\Migration;
use Cmf\Group\Group;

return Migration::addPermissions([
    'cmf-upload.viewUserUploads'   => Group::MODERATOR_ID,
    'cmf-upload.deleteUserUploads' => Group::MODERATOR_ID,
]);
