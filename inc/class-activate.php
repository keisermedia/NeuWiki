<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
 * Fired during plugin activation
 *
 * @link       http://keisermedia.com
 * @since      1.0.0
 * @package    NeuWiki
 * @subpackage NeuWiki/inc
 * @author     Keiser Media <support@keisermedia.com>
 */
class NeuWiki_Activate {

	/**
	 * Run during plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		require_once plugin_dir_path( __FILE__ ) . 'class-post-types.php';
		require_once dirname( plugin_dir_path( __FILE__ ) ) . '/admin/class-meta-boxes.php';
		
		$plugin_slug = strtolower( str_replace( ' ', '-', 'NeuWiki' ) );
		
		$neuwiki_post_types = new NeuWiki_Post_Types( $plugin_slug, false );
		$neuwiki_post_types->register_wiki();
		$neuwiki_post_types->register_category_tax();
		$neuwiki_post_types->register_tag_tax();
		$neuwiki_post_types->register_flag_tax();
		$neuwiki_post_types->register_default_flags();
		
		flush_rewrite_rules();
		
	}

}