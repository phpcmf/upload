<?php

namespace Cmf\Upload;

use Carbon\Carbon;
use Cmf\Database\AbstractModel;
use Cmf\Discussion\Discussion;
use Cmf\Post\Post;
use Cmf\User\User;

/**
 * @property int        $id
 * @property int        $file_id
 * @property File       $file
 * @property int        $actor_id
 * @property User       $actor
 * @property int        $discussion_id
 * @property Discussion $discussion
 * @property int        $post_id
 * @property Post       $post
 * @property Carbon     $downloaded_at
 */
class Download extends AbstractModel
{
    protected $table = 'cmf_upload_downloads';

    public $timestamps = false;

    protected $dates = ['downloaded_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
