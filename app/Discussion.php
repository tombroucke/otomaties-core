<?php

namespace Otomaties\Core;

class Discussion
{
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
    public function closeComments(): bool
    {
        return apply_filters('otomaties_open_comments', false);
    }
}
