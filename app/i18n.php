<?php //phpcs:ignore
namespace Otomaties\Core;

/**
 * Allow mu-plugin strings to be translated
 */
class i18n { //phpcs:ignore

	/**
	 * Load must use plugin textdomain
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {

		load_muplugin_textdomain( 'otomaties-core', plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	}

}
