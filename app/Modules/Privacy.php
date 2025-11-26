<?php

namespace Otomaties\Core\Modules;

class Privacy
{
    public function init(): void
    {
        add_filter('embed_oembed_html', [$this, 'replaceYoutubeWithYoutubeNoCookie'], 10, 2);
    }

    public function replaceYoutubeWithYoutubeNoCookie(string $cachedHtml, string $url): string
    {
        if (! apply_filters('otomaties_replace_youtube_with_youtube_nocookie', true) || ! strpos($url, 'youtu')) {
            return $cachedHtml;
        }

        $replacedCachedHtml = preg_replace('/youtube\.com\/(v|embed)\//s', 'youtube-nocookie.com/$1/', $cachedHtml);

        if ($replacedCachedHtml) {
            $cachedHtml = $replacedCachedHtml;
        }

        return $cachedHtml;
    }
}
