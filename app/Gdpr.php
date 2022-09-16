<?php //phpcs:ignore
namespace Otomaties\Core;

if (! defined('ABSPATH')) {
    exit;
}

class Gdpr
{
    public function replaceYoutubeWithYoutubeNoCookie($cached_html, $url = null)
    {
        if (apply_filters('otomaties_replace_youtube_with_youtube_nocookie', true) && strpos($url, 'youtu')) {
            $cached_html = preg_replace('/youtube\.com\/(v|embed)\//s', 'youtube-nocookie.com/$1/', $cached_html);
        }
        return $cached_html;
    }
}
