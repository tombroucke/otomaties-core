<?php

namespace Otomaties\Core\Helpers;

/**
 * String helper
 */
class View
{
    /**
     * Render a view file
     *
     * @param  array<string, mixed>  $data
     */
    public static function render(string $viewFile, array $data = []): string
    {
        $path = plugin_dir_path(dirname(__FILE__, 2)) . 'templates/';

        $viewFile = $path . ltrim($viewFile, '/');

        if (! file_exists($viewFile)) {
            return '';
        }

        extract($data, EXTR_SKIP);

        $allowedHtml = [
            'code' => [], 
			'strong' => [], 
			'a' => [], 
			'b' => [], 
			'br' => [], 
			'i' => [],
			'ul' => [],
			'ol' => [],
			'li' => [],
			'em' => []
		];

        ob_start();
        include $viewFile;

        return ob_get_clean();
    }
}
