
<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
 * Defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://keisermedia.com
 * @since      1.0.0
 * @package    NeuWiki
 * @subpackage NeuWiki/inc
 * @author     Keiser Media <support@keisermedia.com>
 */
class NeuWiki {

	/**
	 * @var NeuWiki_Loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * @var string The string used to identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * @var string The string used to uniquely identify this plugin.
	 */
	protected $plugin_slug;

	/**
	 * @var string The current version of the plugin.
	 */
	protected $version;

	/**
	 * @var string Minimum WordPress version requirement.
	 */
	protected $wp_version;

	/**
	 * Define the core functionality.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version, $wp_version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_slug = strtolower( str_replace( ' ', '-', $plugin_name ) );
		$this->version = $version;
		$this->wp_version = $wp_version;

		$this->load_dependencies();
		$this->set_locale();
		//$this->process_upgrade();
		$this->register_settings();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_post_types();
		$this->register_capabilities();
		$this->activate_modules();

	}

	/**
	 * Load the required dependencies.
	 * 
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-i18n.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-upgrade.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-post-types.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-capabilities.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/functions.php';

		$this->loader = new NeuWiki_Loader();

	}

	/**
	 * Define the locale for internationalization.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$neuwiki_i18n = new NeuWiki_i18n();
		$neuwiki_i18n->set_domain( $this->get_plugin_info( 'slug' ) );

		$this->loader->add_action( 'plugins_loaded', $neuwiki_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Process plugin updates
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function process_upgrade() {

		$neuwiki_update = new NeuWiki_Upgrade( $this->get_plugin_info( 'version' ) );
		
	}

	/**
	 * Register settings
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_settings() {
		
		$neuwiki_settings = new NeuWiki_Settings( $this->get_plugin_info( 'slug' ) );

		$this->loader->add_action( 'admin_init', $neuwiki_settings, 'register_settings' );
		
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$neuwiki_admin = new NeuWiki_Admin( $this->get_plugin_info( 'name' ), $this->get_plugin_info( 'slug' ), $this->get_plugin_info( 'version' ) );

		$this->loader->add_action( 'admin_enqueue_scripts', $neuwiki_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $neuwiki_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $neuwiki_admin, 'setup_menu' );
		$this->loader->add_action( 'admin_init', $neuwiki_admin, 'setup_meta_boxes' );
		$this->loader->add_action( 'wp_insert_post_data', $neuwiki_admin, 'insert_post_data', 10, 2 );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$neuwiki_public = new NeuWiki_Public( $this->get_plugin_info( 'name' ), $this->get_plugin_info( 'slug' ), $this->get_plugin_info( 'version' ) );

		$this->loader->add_action( 'wp_enqueue_scripts', $neuwiki_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $neuwiki_public, 'enqueue_scripts' );
		$this->loader->add_action( 'template_redirect', $neuwiki_public, 'redirect_archive' );

	}

	/**
	 * Process post types
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_post_types() {

		$neuwiki_post_types = new NeuWiki_Post_Types( $this->get_plugin_info( 'slug' ), $this->get_plugin_info( 'version' ) );
		
		$this->loader->add_action( 'init', $neuwiki_post_types, 'register_wiki' );
		$this->loader->add_action( 'init', $neuwiki_post_types, 'register_category_tax' );
		$this->loader->add_action( 'init', $neuwiki_post_types, 'register_tag_tax' );
		$this->loader->add_action( 'init', $neuwiki_post_types, 'register_flag_tax' );
		$this->loader->add_action( 'admin_enqueue_scripts', $neuwiki_post_types, 'enqueue_scripts' );
		
	}

	/**
	 * Process roles and capabilities
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_capabilities() {

		$neuwiki_capabilities = new NeuWiki_Capabilities( $this->get_plugin_info( 'slug' ) );
		
		$this->loader->add_action( 'map_meta_cap', $neuwiki_capabilities, 'map_meta_cap', 10, 4 );
		$this->loader->add_action( 'admin_init', $neuwiki_capabilities, 'add_capabilities' );
		
		
	}
	
	/**
	 * Process roles and capabilities
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function activate_modules() {
		
		require_once plugin_dir_path( dirname(__FILE__) ) . 'modules/toc/class-toc.php';
		$neuwiki_table_of_contents = new NeuWiki_Table_of_Contents();
		$this->loader->add_filter( 'the_content', $neuwiki_table_of_contents, 'the_content' );
		
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
	 * The plugin slug used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_info( $key ) {
		
		switch ( $key ) {
			
			case 'name':
				$value = $this->plugin_name;
				break;
			
			case 'slug':
				$value = $this->plugin_slug;
				break;
				
			case 'version':
				$value = $this->version;
				break;
			
			default:
				$value = null;
				break;
		}
		
		return $value;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    NeuWiki_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		
		return $this->loader;
		
	}

}