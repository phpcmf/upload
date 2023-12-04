<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->table('cmf_upload_files', function (Blueprint $table) {
            $table->dateTime('created_at')->default('CURRENT_TIMESTAMP')->change();
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('cmf_upload_files', function (Blueprint $table) {
            $table->dateTime('created_at')->change();
        });
    },
];
