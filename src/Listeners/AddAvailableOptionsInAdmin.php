<?php

namespace Cmf\Upload\Listeners;

use Cmf\Settings\Event\Deserializing;
use Cmf\Upload\Helpers\Util;

class AddAvailableOptionsInAdmin
{
    /**
     * @var Util
     */
    protected $util;

    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function handle(Deserializing $event)
    {
        $event->settings['cmf-upload.availableUploadMethods'] = $this->util->getAvailableUploadMethods()->toArray();
        $event->settings['cmf-upload.availableTemplates'] = $this->util->getAvailableTemplates()->toArray();
        $event->settings['cmf-upload.php_ini.post_max_size'] = ini_get('post_max_size');
        $event->settings['cmf-upload.php_ini.upload_max_filesize'] = ini_get('upload_max_filesize');
    }
}
