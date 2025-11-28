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

    /**
     * Append .php extension to string if no extension is found
     *
     * @param  string  $view  The view name
     */
    private function appendPhpExtension(string $view): string
    {
        $ext = pathinfo($view, PATHINFO_EXTENSION);

        return $ext === '' ? $view . '.php' : $view;
    }

    /**
     * Render a view
     *
     * @param  string  $view  The view name
     * @param  array  $context  The context to pass to the view
     */
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

    /**
     * Return a view as string
     *
     * @param  string  $view  The view name
     * @param  array  $context  The context to pass to the view
     */
    public function return(string $view, array $context = []): string
    {
        ob_start();
        $this->render($view, $context);

        return ob_get_clean();
    }
}
