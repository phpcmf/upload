<?php

namespace Cmf\Upload\Extenders;

use Cmf\Api\Controller\ListDiscussionsController;
use Cmf\Api\Controller\ListPostsController;
use Cmf\User\User;

class LoadFilesRelationship
{
    public static function countRelations($controller, $data): void
    {
        $loadable = null;

        if ($data instanceof User) {
            $loadable = $data;
        } elseif (is_array($data) && isset($data['actor'])) {
            $loadable = $data['actor'];
        } elseif ($controller instanceof ListPostsController || $controller instanceof ListDiscussionsController) {
            $loadable = (new User())->newCollection($data->pluck('user'))->filter();
        }

        if ($loadable) {
            $loadable->loadCount('cmffiles');
            $loadable->loadCount('cmffilesCurrent');
        }
    }
}
