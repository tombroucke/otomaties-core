<?php

namespace Otomaties\Core;

/**
 * Allow mu-plugin strings to be translated
 */
class I18n
{
    /**
     * Load must use plugin textdomain
     */
    public function loadTextdomain(): void
    {
        load_muplugin_textdomain('otomaties-core', plugin_basename(dirname(__FILE__, 2)) . '/lang');
    }
}
