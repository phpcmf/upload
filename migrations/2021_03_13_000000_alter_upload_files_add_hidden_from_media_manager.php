<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if (!$schema->hasColumn('cmf_upload_files', 'hide_from_media_manager')) {
            $schema->table('cmf_upload_files', function (Blueprint $table) {
                $table->boolean('hide_from_media_manager')->default(false);
            });
        }
    },
    'down' => function (Builder $schema) {
        $schema->table('cmf_upload_files', function (Blueprint $table) {
            $table->dropColumn('hide_from_media_manager');
        });
    },
];
