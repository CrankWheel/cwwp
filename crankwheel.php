<?php

namespace CrankWheel;

use CrankWheel\Activate;
use CrankWheel\Deactivate;
use CrankWheel\Init;
use CrankWheel\API;

/**
 *
 * @link              http://crankwheel.com
 * @since             1.0.0
 * @package           CrankWheel
 *
 * @wordpress-plugin
 * Plugin Name:       CrankWheel
 * Plugin URI:        https://github.com/CrankWheel/cwwp
 * Description:       The CrankWheel WP plug-in helps users add their custom JavaScript snippet for CrankWheel into their WordPress site, and gives them an easy way to copy a URL they should apply to any buttons or clickable elements that are intended to launch the CrankWheel Instant Demos lead capture process.
 * Version:           1.0.1
 * Author:            CrankWheel
 * Author URI:        http://crankwheel.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       crankwheel
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'CW_URI', trailingslashit( plugin_dir_url(__FILE__) ) );
define( 'CW_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );
define( 'CW_VER', '1.0.1' );
define( 'CW_API', 'http://localhost:3000/ss/instant_demo/request_snippet');
define( 'CW_NAME', 'CrankWheel WordPress Plug-in');



/**
 * Custom Includes
 */
$includes = [
	'includes/utils',
	'includes/helpers',
];


foreach ( $includes as $file ) {

	require_once CW_PATH . $file . '.php';
}




/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
function activate() {

	require_once CW_PATH . 'includes/class-activator.php';
	Activate\Activator::activate();
}




/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function deactivate() {

	require_once CW_PATH . 'includes/class-deactivator.php';
	Deactivate\Deactivator::deactivate();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );




/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once CW_PATH . 'includes/class-crankwheel.php';
require_once CW_PATH . 'includes/class-crankwheel-api.php';





/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run() {

	$plugin = new Init\CrankWheel();
	$plugin->run();

}
run();
