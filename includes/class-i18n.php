<?php

namespace CrankWheel\Lang;


if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Define the internationalization functionality.
 */
class Lang_i18n {


	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'crankwheel',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
