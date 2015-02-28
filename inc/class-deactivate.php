<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
 * Fired during plugin deactivation
 *
 * @link       http://keisermedia.com
 * @since      1.0.0
 * @package    NeuWiki
 * @subpackage NeuWiki/inc
 * @author     Keiser Media <support@keisermedia.com>
 */
class NeuWiki_Deactivate {

	/**
	 * Run during plugin deactivation
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		flush_rewrite_rules();
		
	}

}