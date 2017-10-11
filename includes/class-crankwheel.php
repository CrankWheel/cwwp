<?php

namespace CrankWheel\Init;


use CrankWheel\Loader;
use CrankWheel\Lang;
use CrankWheel\Assets;
use CrankWheel\Admin;
use CrankWheel\API;


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
class CrankWheel {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;



	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;



	/**
	 * Plugin Options
	 * @var [type]
	 */
	public $options;

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

		$this->plugin_name = 'crankwheel';
		$this->version     = CW_VER;

		$this->load_dependencies();
		$this->set_locale();
		$this->admin_hooks();


		/**
		 * Register Callback URL
		 */
		add_action( 'rest_api_init', array($this, 'register_rest_route') );


		/**
		 * Append JS snippet to wp_head
		 * If you want to append it to footer instead use: 'wp_footer' in place of 'wp_head'
		 */
		add_action( 'wp_head', array($this, 'append_js_snippet'));


		/**
		 * Hook for disconnecting
		 */
		add_action( 'wp_ajax_do_cw_disconnect', array($this, 'disconnect_async') );
		add_action( 'wp_ajax_nopriv_do_cw_disconnect', array($this, 'disconnect_async') );


		/**
		 * Save nonce for manual request validation
		 */
		add_action( 'wp_ajax_do_cw_save_nonce', array($this, 'save_nonce') );
		add_action( 'wp_ajax_nopriv_do_cw_save_nonce', array($this, 'save_nonce') );
	}



	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - i18n. Defines internationalization functionality.
	 * - Admin. Defines all hooks for the admin area.
	 * - Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once CW_PATH . 'includes/class-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once CW_PATH . 'includes/class-i18n.php';


		/**
		 * Backend things
		 */
		require_once CW_PATH . 'admin/class-admin.php';


		$this->loader = new Loader\Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Lang\Lang_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}





	/**
	 * Enqueue scripts and style to wp-admin
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function admin_hooks() {

		$plugin_admin = new Admin\Admin_Setup( $this->get_plugin_name(), $this->get_version() );



		add_action( 'admin_enqueue_scripts', function() {

			wp_enqueue_script('cw/js', Assets\asset_path('scripts/admin.js'), array('jquery'), null, true);
			wp_enqueue_style('cw/css', Assets\asset_path('styles/admin.css'), false, null);

			/**
			 * Data passed from PHP to JS
			 */
			wp_localize_script( 'cw/js', 'cw', array(
				'nonce'    => wp_create_nonce('crankwheel_api'),
				'ajax_url' => admin_url('admin-ajax.php'),
				'cw_api'   => CW_API,
				'json_api' => home_url('/wp-json/crankwheel/v1/api'),
				'plugin'   => esc_attr(CW_NAME),
			));

		} , 100);

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
	}







	/**
	 * Register API listener
	 *
	 * Registers callback URL: example.com/wp-json/crankwheel/v1/api
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function register_rest_route() {

		register_rest_route( 'crankwheel/v1', '/api', [
			'methods'  => array('POST', 'GET'),
			'callback' => array($this, 'authenticate'),
		]);
	}




	/**
	 * Auth on POST wp-json
	 *
	 * @param  $data $_POST from CrankWheel API
	 */
	public function authenticate( $data )  {

		$cw   = new API\CW_API();
		$auth = $cw->authenticate( $data->get_body() );

		if ( ! $auth ) {
			add_action( 'admin_notices', function () {

				$class   = 'notice notice-error';
				$message = __( 'Account not connected, invalid token.', 'crankwheel' );

				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
			});
		}
	}






	/**
	 * Disconnect via settings page button
	 */
	public function disconnect_async() {

		$response = array(
			'code' => 500,
			'msg'  => 'Error, invalid request'
		);

		/**
		 * Validate request
		 */
		if ( ! wp_verify_nonce( $_POST['nonce'], 'crankwheel_api' ) ) {
			wp_die( json_encode($response) );
		}

		// Disconnect
		$cw = new API\CW_API();
		$cw->disconnect();


		$response = array(
			'code' => 200
		);

		wp_die( json_encode($response) );
	}


	/**
	 * Sve nonce for validation after API returns $_POST
	 */
	public function save_nonce() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'crankwheel_api' ) ) {
			wp_die();
		}


		update_option( '_cw_token', sanitize_text_field( $_POST['nonce'] ) );
	}




	/**
	 * Append JS snippet to HEAD tag
	 */
	public function append_js_snippet() {

		$cw = new API\CW_API();

		if ( ! $cw->is_connected() )
			return; ?>

		<script type="text/javascript">
			<?php echo $cw->get_option('snippet'); ?>
		</script>

		<?php
	}




	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}




	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}




	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}



	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
