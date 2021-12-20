<?php //phpcs:ignore
namespace Otomaties\Core;

class Shortcodes {
	/**
     * Obfuscate email address with [email address="tom@tombroucke.be" class="btn btn-primary"]
     *
     * @param string\array $atts
     * @param string $content Content between start & end tag
     * @return string
     */
    public function obfuscateEmail($atts = [], string $content = null) : ?string
    {
        $a = shortcode_atts(
            [
                'class' => null,
                'address' => $content
            ],
            $atts
        );

        if (! is_email($a['address'])) {
            return null;
        }
        $class = $a['class'] ? sprintf(' class="%s"', $a['class']) : '';
        return sprintf('<a href="%s"%s>%s</a>', esc_url('mailto:' . antispambot($a['address'])), $class, esc_html(antispambot($a['address'])));
    }
}
