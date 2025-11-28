<?php

namespace Otomaties\Core;

use Otomaties\Core\Exceptions\ViewNotFoundException;
use OtomatiesCoreVendor\Illuminate\Support\Str;

class View
{
    public function __construct(private string $path)
    {
        //
    }

    private function appendPhpExtension(string $view): string
    {
        $ext = pathinfo($view, PATHINFO_EXTENSION);

        return $ext === '' ? $view . '.php' : $view;
    }

    public function render(string $view, array $context = []): void
    {
        $view = Str::finish($this->path, '/') . Str::chopStart($this->appendPhpExtension($view), '/');

        if (! file_exists($view)) {
            throw new ViewNotFoundException('View not found: ' . $view);
        }

        $context['allowedHtml'] = [
            'code' => [],
            'strong' => [],
            'a' => [],
            'b' => [],
            'br' => [],
            'i' => [],
            'ul' => [],
            'ol' => [],
            'li' => [],
            'em' => [],
        ];

        extract($context, EXTR_SKIP);

        include apply_filters('otomaties_core_view', $view, $context);
    }

    public function return(string $view, array $context = []): string
    {
        ob_start();
        $this->render($view, $context);

        return ob_get_clean();
    }
}
