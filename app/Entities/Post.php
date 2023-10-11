<?php

namespace App\Entities;

use App\Concerns\RendersContent;
use CodeIgniter\Entity\Entity;

class Post extends Entity
{
    use RendersContent;

    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at', 'edited_at'];
    protected $casts   = [
        'id'          => 'integer',
        'category_id' => 'integer',
        'thread_id'   => 'integer',
        'reply_to'    => '?integer',
        'author_id'   => 'integer',
        'editor_id'   => 'integer',
        'include_sig' => 'boolean',
        'visible'     => 'boolean',
    ];

    /**
     * Returns a link to this post's page for use in views.
     */
    public function link(?Category $category = null, ?Thread $thread = null)
    {
        $categorySlug = $category === null ?
            model('CategoryModel')->find($this->category_id)->slug :
            $category->slug;

        $threadSlug = $thread === null ?
            model('ThreadModel')->find($this->thread_id)->slug :
            $thread->slug;

        return url_to('post', $categorySlug, $threadSlug, $this->id) . '#post-' . $this->id;
    }

    public function setReplyTo(?string $value = null)
    {
        $this->attributes['reply_to'] = $value ?: null;

        return $this;
    }
}
