<?php

use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        // 重用 talk 版本中的表（如果存在）
        if ($schema->hasTable('talk_files') && !$schema->hasTable('cmf_upload_files')) {
            $schema->rename('talk_files', 'cmf_upload_files');
        }

        if ($schema->hasTable('talk_file_downloads') && !$schema->hasTable('cmf_upload_downloads')) {
            $schema->rename('talk_file_downloads', 'cmf_upload_downloads');
        }
    },
    'down' => function (Builder $schema) {
        // 必须定义除了 `down` 之外什么都不做
    },
];
