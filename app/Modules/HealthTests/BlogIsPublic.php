<?php

namespace Otomaties\Core\Modules\HealthTests;

use Otomaties\Core\Modules\HealthTests\Dtos\HealthTestResponseDto;
use Otomaties\Core\Modules\HealthTests\Enums\HealthCheckCategory;

class BlogIsPublic extends Abstracts\HealthTest
{
    protected string $category = HealthCheckCategory::SEO;

    public function passes(): bool
    {
        if (otomatiesCore()->environment() !== 'production') {
            return (string) get_option('blog_public') !== '1';
        }

        return (string) get_option('blog_public') !== '0';
    }

    public function passedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        $isProduction = otomatiesCore()->environment() === 'production';

        return $response
            ->withLabel(
                $isProduction ?
                __('Search engines can crawl the blog', 'otomaties-core') :
                __('Search engines are discouraged from crawling the blog', 'otomaties-core')
            )
            ->withDescription(sprintf(
                '<p>%s</p>',
                $isProduction ?
                    __('Search engines can crawl the blog', 'otomaties-core') :
                    __('Search engines are discouraged from crawling the blog', 'otomaties-core')
            ));
    }

    public function failedResponse(HealthTestResponseDto $response): HealthTestResponseDto
    {
        $isProduction = otomatiesCore()->environment() === 'production';

        return $response
            ->withStatus('critical')
            ->withLabel(
                $isProduction ?
                    __('Search engines are discouraged from crawling the blog', 'otomaties-core') :
                    __('Search engines can crawl the blog', 'otomaties-core')
            )
            ->withDescription(sprintf(
                '<p>%s</p>',
                sprintf(
                    $isProduction ?
                        __('Search engines are discouraged from crawling the blog. Visit %s to change this setting', 'otomaties-core') : // phpcs:ignore Generic.Files.LineLength
                        __('Search engines can crawl the blog. Visit %s to change this setting', 'otomaties-core'),
                    sprintf(
                        '<a href="%s" target="_blank">%s</a>',
                        admin_url('options-reading.php'),
                        __('Reading Settings', 'otomaties-core')
                    )
                )
            ));
    }
}
