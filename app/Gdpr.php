<?php
namespace Otomaties\Core;

class Gdpr
{
    public function replaceYoutubeWithYoutubeNoCookie(string $cachedHtml, string $url) : string
    {
        if (!apply_filters('otomaties_replace_youtube_with_youtube_nocookie', true) || !strpos($url, 'youtu')) {
            return $cachedHtml;
        }

        $replacedCachedHtml = preg_replace('/youtube\.com\/(v|embed)\//s', 'youtube-nocookie.com/$1/', $cachedHtml);

        if ($replacedCachedHtml) {
            $cachedHtml = $replacedCachedHtml;
        }

        return $cachedHtml;
    }
}
