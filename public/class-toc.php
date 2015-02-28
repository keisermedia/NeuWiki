<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
* Functions for the Table of Contents.
*
* @link       http://keisermedia.com
* @since      1.0.0
* @package    NeuWiki
* @subpackage NeuWiki/public
* @author     Keiser Media <support@keisermedia.com>
*/

class NeuWiki_Table_of_Contents {
	
	public function extract_headings() {
		
		if( 'neuwiki' !== get_post_type() )
			return $content;
		
		return 'This is a neuwiki article.';
		
	}
	
	/**
	 * Filters the post content for NeuWiki
	 * 
	 * @since 1.0.0
	 * @param  string $content Contains the post content
	 * @return string          Contains the post content
	 */
	public function add_header_links( $content ) {
		
		if( 'neuwiki' != get_post_type() )
			return $content;
		
		$headings = preg_match_all('|<h[^>]+>(.*)</h[^>]+>|iU', $content, $headings);
		
		$h_original	= $headings;
		$h_replace	= array();
		
		for( $i = 0; $i <= count($headings[0]); $i++ ) {
			
			$h_replace[] = str_replace( $headings[1][$i], '<a name="' . md5($headings[1][$i]) . '">' . $headings[1][$i] . '</a>', $headings[0][$i]);
			
		}
		
		return $content;
	}
	
}