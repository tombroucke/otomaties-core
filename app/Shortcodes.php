<?php

namespace Otomaties\Core;

class Shortcodes
{
    /**
     * Obfuscate email address with [email address="tom@tombroucke.be" class="btn btn-primary"]
     *
     * @param  array<string, string>|string  $atts
     * @param  string  $content  Content between start & end tag
     */
    public function obfuscateEmail(array|string $atts = [], ?string $content = null): ?string
    {
        $a = shortcode_atts(
            [
                'class' => null,
                'address' => $content,
            ],
            $atts
        );

        $address = $a['address'] ?: '';

        if (! is_email($address)) {
            return null;
        }

        $class = $a['class'] ? sprintf(' class="%s"', $a['class']) : '';

        return sprintf(
            '<a href="%s"%s>%s</a>',
            esc_url('mailto:' . antispambot($address)),
            $class,
            esc_html(antispambot($address))
        );
    }

    /**
     * Obfuscate telephone number with [tel number="tom@tombroucke.be" class="btn btn-primary"]
     *
     * @param  array<string, string>|string  $atts
     * @param  string  $content  Content between start & end tag
     */
    public function obfuscateTel(array|string $atts = [], ?string $content = null): ?string
    {
        $a = shortcode_atts(
            [
                'class' => null,
                'number' => $content,
            ],
            $atts
        );

        $number = $a['number'] ?: '';

        $class = $a['class'] ? sprintf(' class="%s"', $a['class']) : '';

        return sprintf(
            '<a href="%s"%s>%s</a>',
            esc_url('tel:' . antispambot($number)),
            $class,
            esc_html(antispambot($number))
        );
    }
}
