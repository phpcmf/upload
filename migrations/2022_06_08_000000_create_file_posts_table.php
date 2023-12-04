<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->create('cmf_upload_file_posts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('file_id')->nullable();
            $table->unsignedInteger('post_id')->nullable();

            $table
                ->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->cascadeOnDelete();
            $table
                ->foreign('file_id')
                ->references('id')
                ->on('cmf_upload_files')
                ->cascadeOnDelete();
        });
    },
    'down' => function (Builder $schema) {
        $schema->dropIfExists('cmf_upload_file_posts');
    },
];
