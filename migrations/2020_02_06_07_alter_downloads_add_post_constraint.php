<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        // Set post_id null if the post does not exist.
        // Will prevent issues when applying foreign key constraint.
        $connection = $schema->getConnection();
        $connection->table('cmf_upload_downloads')
            ->whereNotExists(function ($query) {
                $query->selectRaw(1)->from('posts')->whereColumn('post_id', 'id');
            })
            ->update(['post_id' => null]);

        $schema->table('cmf_upload_downloads', function (Blueprint $table) {
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('set null');
        });
    },
    'down' => function (Builder $schema) {
        $schema->table('cmf_upload_downloads', function (Blueprint $table) {
            $table->dropForeign(['post_id']); // Using array syntax will make Laravel "guess" the index name
        });
    },
];
