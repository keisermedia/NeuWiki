<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
* Regulates the admin settings.
*
* @link       http://keisermedia.com
* @since      1.0.0
* @package    NeuWiki
* @subpackage NeuWiki/admin
* @author     Keiser Media <support@keisermedia.com>
*/

class NeuWiki_Admin {

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
		
		require_once plugin_dir_path( __FILE__ ) . 'class-meta-boxes.php';

	}

	/**
	 * Register the stylesheets for the admin dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( $this->slug, plugin_dir_url( dirname( __FILE__ ) ) . '/assets/css/k-wiki-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->slug, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/kvp-wiki-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Registers top-level page and all sub pages.
	 *
	 * @since    1.0.0
	 */
	public function setup_menu() {
		
		add_submenu_page( 'edit.php?post_type=neuwiki', __( 'Settings', $this->slug ), __( 'Settings', $this->slug ), 'manage_options', 'neuwiki-settings', array( $this, 'settings_page' ) );
		
	}
	
	/**
	 * Registers admin meta boxes.
	 *
	 * @since    1.0.0
	 */
	public function setup_meta_boxes() {
		
		$default_meta_boxes = array(
			array( 'neuwiki-toc', __( 'Table of Contents', 'kvp' ), array( new NeuWiki_Meta_Boxes, 'toc' ), 'neuwiki', 'side', 'default', null ),
		);
		
		$meta_boxes = apply_filters( 'neuwiki_meta_boxes', $default_meta_boxes );
		
		foreach ( $meta_boxes as $meta_box )
			add_meta_box( $meta_box[0], $meta_box[1], $meta_box[2], $meta_box[3], $meta_box[4], $meta_box[5], $meta_box[6] );
		
		remove_meta_box( 'commentstatusdiv', 'neuwiki', 'normal' );
		
		add_action( 'save_post', array( new NeuWiki_Meta_Boxes, 'save_meta_boxes' ) );
		
	}
	
	/**
	 * Displays wiki settings
	 * 
	 * @since 1.0.0
	 */
	public function settings_page() {
		
		include( 'settings-page.php' );
		
	}
	
	public function insert_post_data( $data, $postarr ) {
		
		if( 'neuwiki' !== $data['post_type'] )
			return $data;
		
		$data['comment_status']	= 'open';
		$data['ping_status']	= 'closed';
		
		return $data;
		
	}
	
}