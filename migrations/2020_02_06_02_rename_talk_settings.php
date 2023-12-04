<?php

use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $db = $schema->getConnection();

        foreach ([
            'maxFileSize',
            'mimeTypes',
            'templates',
            'mustResize',
            'resizeMaxWidth',
            'cdnUrl',
            'addsWatermarks',
            'watermarkPosition',
            'watermark',
            'overrideAvatarUpload',
            'imgurClientId',
            'awsS3Key',
            'awsS3Secret',
            'awsS3Bucket',
            'awsS3Region',
            'disableHotlinkProtection',
            'disableDownloadLogging',
            'qiniuKey',
            'qiniuSecret',
            'qiniuBucket',
        ] as $key) {
            $db->table('settings')
                ->where('key', 'talk.upload.'.$key)
                ->update(['key' => 'cmf-upload.'.$key]);
        }
    },
    'down' => function (Builder $schema) {
        // 必须定义除了 `down` 之外什么都不做
    },
];
