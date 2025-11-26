<?php

namespace Otomaties\Core\Modules;

class Discussion
{
    public function init(): void
    {
        add_filter('comments_open', [$this, 'closeComments'], 50, 2);
        add_action('updated_option', [$this, 'setDefaults'], 999);
    }

    public function setDefaults(): void
    {
        $options = [
            'default_comment_status' => '0',
            'default_ping_status' => '0',
            'moderation_notify' => '0',
            'comments_notify' => '0',
        ];
        foreach ($options as $key => $value) {
            if (apply_filters('otomaties_set_default_' . $key, true)) {
                update_option($key, $value);
            }
        }
    }

    /**
     * Close comments
     */
    public function closeComments(bool $open, int $postId): bool
    {
        return apply_filters('otomaties_open_comments', false);
    }
}
