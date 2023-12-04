<?php

use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->getConnection()->table('migrations')->whereExtension('talk-upload')->delete();
    },
    'down' => function (Builder $schema) {
        // 必须定义除了 `down` 之外什么都不做
    },
];
