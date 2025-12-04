<?php

namespace Otomaties\Core\Modules;

class Discussion
{
    /**
     * Add actions and filters
     */
    public function init(): void
    {
        add_filter('comments_open', [$this, 'closeComments'], 50, 2);
    }

    /**
     * Close comments
     *
     * @param  bool  $open  Whether the comments are open
     * @param  int  $postId  The post ID
     */
    public function closeComments(bool $open, int $postId): bool
    {
        return apply_filters('otomaties_open_comments', false);
    }
}
