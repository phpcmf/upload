<?php

namespace Cmf\Upload;

use Blomstra\Gdpr\Extend\UserData;
use Cmf\Api\Controller\ListDiscussionsController;
use Cmf\Api\Controller\ListPostsController;
use Cmf\Api\Controller\ShowSiteController;
use Cmf\Api\Controller\ShowUserController;
use Cmf\Api\Serializer\CurrentUserSerializer;
use Cmf\Api\Serializer\SiteSerializer;
use Cmf\Api\Serializer\UserSerializer;
use Cmf\Extend;
use Cmf\Post\Event\Posted;
use Cmf\Post\Event\Revised;
use Cmf\Settings\Event\Deserializing;
use Cmf\User\User;
use Cmf\Upload\Events\File\WillBeUploaded;
use Cmf\Upload\Exceptions\ExceptionHandler;
use Cmf\Upload\Exceptions\InvalidUploadException;
use Cmf\Upload\Extend\SvgSanitizer;
use Cmf\Upload\Extenders\LoadFilesRelationship;

return [
    (new Extend\Routes('api'))
        ->get('/cmf/uploads', 'cmf-upload.list', Api\Controllers\ListUploadsController::class)
        ->post('/cmf/upload', 'cmf-upload.upload', Api\Controllers\UploadController::class)
        ->post('/cmf/watermark', 'cmf-upload.watermark', Api\Controllers\WatermarkUploadController::class)
        ->get('/cmf/download/{uuid}/{post}/{csrf}', 'cmf-upload.download', Api\Controllers\DownloadController::class)
        ->post('/cmf/upload/inspect-mime', 'cmf-upload.inspect-mime', Api\Controllers\InspectMimeController::class)
        ->patch('/cmf/upload/hide', 'cmf-upload.hide', Api\Controllers\HideUploadFromMediaManagerController::class),

    (new Extend\Console())->command(Console\MapFilesCommand::class),

    (new Extend\Csrf())->exemptRoute('cmf-upload.download'),

    (new Extend\Frontend('admin'))
        ->css(__DIR__.'/less/admin.less')
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Frontend('site'))
        ->css(__DIR__.'/less/site/download.less')
        ->css(__DIR__.'/less/site/upload.less')
        ->css(__DIR__.'/less/site/fileManagerModal.less')
        ->css(__DIR__.'/less/site/fileList.less')
        ->css(__DIR__.'/less/site/textPreview.less')
        ->js(__DIR__.'/js/dist/site.js'),
    new Extend\Locales(__DIR__.'/locale'),

    new Extenders\AddPostDownloadTags(),
    new Extenders\CreateStorageFolder('tmp'),

    (new Extend\Model(User::class))
        ->hasMany('cmffiles', File::class, 'actor_id')
        ->relationship('cmffilesCurrent', function (User $model) {
            return $model->cmffiles()->where('hide_from_media_manager', false);
        }),

    (new Extend\ApiController(ShowUserController::class))
        ->prepareDataForSerialization([LoadFilesRelationship::class, 'countRelations']),
    (new Extend\ApiController(ShowSiteController::class))
        ->prepareDataForSerialization([LoadFilesRelationship::class, 'countRelations']),
    (new Extend\ApiController(ListDiscussionsController::class))
        ->prepareDataForSerialization([LoadFilesRelationship::class, 'countRelations']),
    (new Extend\ApiController(ListPostsController::class))
        ->prepareDataForSerialization([LoadFilesRelationship::class, 'countRelations']),

    (new Extend\ApiSerializer(SiteSerializer::class))
        ->attributes(Extenders\AddSiteAttributes::class),

    (new Extend\Event())
        ->listen(Deserializing::class, Listeners\AddAvailableOptionsInAdmin::class)
        ->listen(Posted::class, Listeners\LinkImageToPostOnSave::class)
        ->listen(Revised::class, Listeners\LinkImageToPostOnSave::class)
        ->listen(WillBeUploaded::class, Listeners\AddImageProcessor::class),

    (new Extend\ServiceProvider())
        ->register(Providers\UtilProvider::class)
        ->register(Providers\StorageServiceProvider::class)
        ->register(Providers\DownloadProvider::class)
        ->register(Providers\SanitizerProvider::class),

    (new Extend\View())
        ->namespace('cmf-upload.templates', __DIR__.'/views'),

    (new Extend\ApiSerializer(CurrentUserSerializer::class))
        ->attributes(Extenders\AddCurrentUserAttributes::class),

    (new Extend\ApiSerializer(UserSerializer::class))
        ->attributes(Extenders\AddUserAttributes::class),

    (new Extend\Formatter())
        ->render(Formatter\TextPreview\FormatTextPreview::class),

    (new SvgSanitizer())
        ->allowTag('animate'),

    (new Extend\ErrorHandling())
        ->handler(InvalidUploadException::class, ExceptionHandler::class),

    (new Extend\Conditional())
        ->whenExtensionEnabled('blomstra-gdpr', fn () => [
            (new UserData())
                ->addType(Data\Uploads::class),
        ]),
];
