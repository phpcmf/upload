<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->table('cmf_upload_files', function (Blueprint $table) {
            $table->index(['actor_id', 'hide_from_media_manager']);
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('cmf_upload_files', function (Blueprint $table) {
            $table->dropIndex(['actor_id', 'hide_from_media_manager']);
        });
    },
];
