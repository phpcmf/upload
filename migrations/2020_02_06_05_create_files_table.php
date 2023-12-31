<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if ($schema->hasTable('cmf_upload_files')) {
            return;
        }

        $schema->create('cmf_upload_files', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('actor_id')->nullable();
            $table->unsignedInteger('discussion_id')->nullable();
            $table->unsignedInteger('post_id')->nullable();

            $table->string('base_name');
            $table->string('path')->nullable();
            $table->string('url');
            $table->string('type');
            $table->integer('size');

            $table->string('upload_method')->nullable();

            $table->timestamp('created_at');

            $table->string('remote_id')->nullable();
            $table->string('uuid')->nullable();
            $table->string('tag')->nullable();
        });
    },
    'down' => function (Builder $schema) {
        $schema->dropIfExists('cmf_upload_files');
    },
];
