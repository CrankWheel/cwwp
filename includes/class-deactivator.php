<?php

namespace CrankWheel\Deactivate;

use CrankWheel\API;

if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Fired during plugin deactivation.
 *
 */
class Deactivator {

	public static function deactivate() {

		$cw = new API\CW_API;
		$cw->disconnect();
	}

}
