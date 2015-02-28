<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
 * Contains misc NeuWiki functions.
 *
 * @link       http://keisermedia.com
 * @since      1.0.0
 * @package    NeuWiki
 * @subpackage NeuWiki/inc
 * @author     Keiser Media <support@keisermedia.com>
 */

/**
 * Gets option for NeuWiki
 * 
 * @since 1.0.0
 * 
 * @param  string  	$option  	Option name
 * @param  mixed 	$default 	Default varible if none is found
 * @return mixed
 */
function neuwiki_get_option( $option, $default = null ) {
	global $neuwiki_settings;
	
	if( !isset($neuwiki_settings) )
		$neuwiki_settings = get_option( 'neuwiki_settings', array() );
	
	if( isset($neuwiki_settings[$option]) )
		return $neuwiki_settings[$option];
	
	return $default;
	
}