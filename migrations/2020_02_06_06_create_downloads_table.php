<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if ($schema->hasTable('cmf_upload_downloads')) {
            return;
        }

        $schema->create('cmf_upload_downloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('file_id');
            $table->unsignedInteger('discussion_id')->nullable();
            $table->unsignedInteger('post_id')->nullable();
            $table->unsignedInteger('actor_id')->nullable();
            $table->timestamp('downloaded_at');

            $table->foreign('file_id')
                ->references('id')
                ->on('cmf_upload_files')
                ->onDelete('cascade');

            $table->foreign('discussion_id')
                ->references('id')
                ->on('discussions')
                ->onDelete('set null');

            $table->foreign('actor_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    },
    'down' => function (Builder $schema) {
        $schema->dropIfExists('cmf_upload_downloads');
    },
];
