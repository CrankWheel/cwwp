<?php

namespace CrankWheel\Admin;


if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The admin-specific functionality of the plugin.
 *
 */

class Admin_Setup {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;




	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}



	/**
	 * Adds admin menu page
	 */
	public function admin_menu() {

		add_menu_page(
        	__( 'CrankWheel', 'crankwheel' ),
        	__( 'CrankWheel', 'crankwheel' ),
        	'manage_options',
        	'crankwheel',
        	array( $this, 'render_settings_page'),
        	'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjEzLjcgMjEzLjYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDIxMy43IDIxMy42OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggY2xhc3M9InN0MCIgZD0iTTE5NC41LDgxTDE5NC41LDgxYy0xLjgtNi4zLTQuMy0xMi4yLTcuMy0xNy44bDMuNS0yMy43bC0xNi40LTE2LjRsLTIzLjcsMy41Yy01LjYtMy0xMS41LTUuNS0xNy44LTcuM0wxMTguNCwwSDk1LjNMODAuOSwxOS4yYy02LjIsMS44LTEyLjEsNC4yLTE3LjcsNy4zbC0yMy43LTMuNUwyMy4xLDM5LjVsMy41LDIzLjdjLTMsNS42LTUuNSwxMS41LTcuMywxNy43TDAsOTUuM3YyMy4ybDE5LjIsMTQuM2MxLjgsNi4yLDQuMiwxMi4yLDcuMywxNy43bC0zLjUsMjMuN2wxNi40LDE2LjRsMjMuNy0zLjVjNS42LDMsMTEuNSw1LjUsMTcuNyw3LjNsMTQuMywxOS4yaDIzLjJsMTQuMy0xOS4yYzYuMi0xLjgsMTIuMi00LjMsMTcuOC03LjNsMjMuNywzLjVsMTYuNC0xNi40bC0zLjUtMjMuN2MzLTUuNiw1LjUtMTEuNSw3LjMtMTcuN2wxOS4yLTE0LjNWOTUuM0wxOTQuNSw4MXogTTE2Ny42LDExMC41YzAsMjYuNS0yMS41LDQ4LTQ4LDQ4SDg3LjFjLTAuNywwLTEuNC0wLjEtMi4xLTAuMmMtMjUuNS0yLjItNDUuNS0yMy43LTQ1LjUtNDkuN2MwLTI3LjUsMjIuNC00OS45LDQ5LjktNDkuOWM2LjMsMCwxMS41LDUuMiwxMS41LDExLjVjMCw2LjMtNS4yLDExLjUtMTEuNSwxMS41Yy0xNC44LDAtMjYuOSwxMi0yNi45LDI2LjlzMTIsMjYuOSwyNi45LDI2LjloNHYtMTkuNGMwLTYuMyw1LjItMTEuNSwxMS41LTExLjVzMTEuNSw1LjIsMTEuNSwxMS41djE5LjRoMy4yYzEzLjgsMCwyNS0xMS4yLDI1LTI1VjcwLjNjMC02LjMsNS4yLTExLjUsMTEuNS0xMS41YzYuMywwLDExLjUsNS4yLDExLjUsMTEuNVYxMTAuNXoiLz48L3N2Zz4='
        );
	}



	public function render_settings_page() {

		require_once CW_PATH . 'admin/views/settings.php';
	}
}
