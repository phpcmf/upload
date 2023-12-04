<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->table('cmf_upload_files', function (Blueprint $table) {
            $table->dropColumn('post_id', 'discussion_id');
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('cmf_upload_files', function (Blueprint $table) {
            $table->unsignedInteger('discussion_id')->nullable();
            $table->unsignedInteger('post_id')->nullable();
        });
    },
];
