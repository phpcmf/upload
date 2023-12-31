<?php

namespace Cmf\Upload\Commands;

use Cmf\User\User;

class Download
{
    /**
     * @var string
     */
    public $uuid;
    /**
     * @var User
     */
    public $actor;
    /**
     * @var null|int
     */
    public $discussionId;
    /**
     * @var null|int
     */
    public $postId;

    public function __construct($uuid, User $actor, $discussionId = null, $postId = null)
    {
        $this->uuid = $uuid;
        $this->actor = $actor;
        $this->discussionId = $discussionId;
        $this->postId = $postId;
    }
}
