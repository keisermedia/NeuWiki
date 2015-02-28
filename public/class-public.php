<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
* Regulates the admin settings.
*
* @link       http://keisermedia.com
* @since      1.0.0
* @package    NeuWiki
* @subpackage NeuWiki/public
* @author     Keiser Media <support@keisermedia.com>
*/

class NeuWiki_Public {

	/**
	 * The readable name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string The ID of this plugin.
	 */
	private $name;
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string The ID of this plugin.
	 */
	private $slug;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string  The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $slug       The slug of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $slug, $version ) {
		
		$this->name = $name;
		$this->slug = $slug;
		$this->version = $version;
		
		$this->load_dependencies();
		
	}
	
	/**
	 * Load the required dependencies.
	 * 
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		

	}


	/**
	 * Register the stylesheets for the admin dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->slug, plugin_dir_url( dirname( __FILE__ ) ) . '/assets/css/neuwiki.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->slug, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/neuwiki.js', array( 'jquery' ), $this->version, false );

	}
	
	public function redirect_archive() {
		global $wp_query;
		
		$settings = get_option( 'neuwiki_settings', array() );
		
		if( isset($settings['wiki_front_page']) && 'default' !== $settings['wiki_front_page'] && is_post_type_archive( 'neuwiki' ) )
			$wp_query = new WP_Query( array( 'page_id' => $settings['wiki_front_page'] ) );
			
		
	}
	
}