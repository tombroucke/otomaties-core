<?php
namespace Otomaties\Core;

class Emojis
{

    /**
     * Remove emojis
     *
     * @return void
     */
    public function init() : void
    {
        if (! $this->disableEmojis()) {
            return;
        }

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('emoji_svg_url', '__return_false');
    }


    public function disableEmojisTinymce(array $plugins) : array
    {
        if (! $this->disableEmojis()) {
            return $plugins;
        }

        return array_diff($plugins, ['wpemoji']);
    }

    public function disableEmojisRemoveDnsPrefetch(array $urls, string $relation_type) : array
    {
        if (! $this->disableEmojis()) {
            return $urls;
        }

        if ('dns-prefetch' == $relation_type) {
            $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
            $urls = array_diff($urls, [$emoji_svg_url]);
        }
        return $urls;
    }

    /**
     * Allow for theme to enable emojis
     *
     * @return boolean
     */
    private function disableEmojis() : bool
    {
        return apply_filters('otomaties_disable_emojis', true);
    }
}
