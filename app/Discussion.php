<?php //phpcs:ignore
namespace Otomaties\Core;

class Discussion
{
    public function setDefaults()
    {
        update_option('default_comment_status', '0');
        update_option('default_ping_status', '0');
        update_option('moderation_notify', '0');
        update_option('comments_notify', '0');
    }

    /**
     * Close comments
     */
    public function closeComments()
    {
        return apply_filters('otomaties_open_comments', false);
    }
}
