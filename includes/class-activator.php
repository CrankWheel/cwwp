<?php

namespace CrankWheel\Activate;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 */
class Activator {

	public static function activate() {

		add_option( '_cw_api_first_activation', time() )
	}

}
