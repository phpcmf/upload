<?php

use Illuminate\Database\Schema\Builder;

function setAwsUploadedFilesAdapterDelimiter(Builder $schema, string $old = '_', string $new = '-')
{
    $schema
        ->getConnection()
        ->table('cmf_upload_files')
        ->where('upload_method', "aws{$old}s3")
        ->update(['upload_method' => "aws{$new}s3"]);
}

return [
    'up' => function (Builder $schema) {
        setAwsUploadedFilesAdapterDelimiter($schema, '_', '-');
    },
    'down' => function (Builder $schema) {
        setAwsUploadedFilesAdapterDelimiter($schema, '-', '_');
    },
];
