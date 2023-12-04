<?php

use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $db = $schema->getConnection();

        $db->table('group_permission')
            ->where('permission', 'talk.upload')
            ->update(['permission' => 'cmf-upload.upload']);

        $db->table('group_permission')
            ->where('permission', 'talk.upload.download')
            ->update(['permission' => 'cmf-upload.download']);
    },
    'down' => function (Builder $schema) {
        // 必须定义除了 `down` 之外什么都不做
    },
];
