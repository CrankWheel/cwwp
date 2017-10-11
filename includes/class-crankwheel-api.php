<?php

namespace CrankWheel\API;


if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 */
class CW_API {

	/**
	 * Option name
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $option_name;



	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;




	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->option_name = '_crankwheel_api';
		$this->version     = CW_VER;
	}





	/**
	 * Validate nonce and save response data
	 *
	 */
	public function authenticate( $response )  {

		$data          = json_decode($response);
		$nonce_request = sanitize_text_field( get_key('token', $data) );
		$nonce_saved   = get_option( '_cw_token' );

		if ( $nonce_request != $nonce_saved )
			return;

		delete_option('_cw_token');
		update_option( $this->option_name, $data );

		if ( is_multisite() ) {
			delete_site_option('_cw_token');
		}
	}




	/**
	 * Check if account is connected
	 */
	public function is_connected() {

		$active = $this->get_option('snippet');

		if ( $active ) {
			return true;
		}
		else {
			return false;
		}
	}



	/**
	 * Delete data
	 */
	public function disconnect() {

		delete_option('_cw_token');
		delete_option( $this->option_name );

		if ( is_multisite() ) :

			delete_site_option('_cw_token');
			delete_site_option($this->option_name);
		endif;
	}





	/**
	 * Get CrankWheel API URL
	 */
	public function get_link() {

		return $this->get_option('campaign_url');
	}





	/**
	 * Get options / Specific option
	 */
	public function get_option( $option = false ) {

		$options = get_option( $this->option_name );


		if ( $option && get_key($option, $options) ) {

			return get_key($option, $options);
		}
		elseif ( $option && ! get_key($option, $options) ) {

			return false;
		}
		else {

			return $options;
		}
	}





	public function set_option( $option, $value = false ) {

		if ( ! $value )
			return;

		$options = $this->get_option();
		$update  = $options[$option] = $value;

		update_option( $option_name, $update );

	}
}