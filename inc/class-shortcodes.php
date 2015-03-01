<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
 * Registers wiki shortcodes.
 *
 * @link       http://keisermedia.com
 * @since      1.0.0
 * @package    NeuWiki
 * @subpackage NeuWiki/inc
 * @author     Keiser Media <support@keisermedia.com>
 */
final class NeuWiki_Shortcodes {
	
	/**
	 * Contains the plugin slug
	 * @var string
	 */
	private $slug;
	
	/**
	 * Contains the plugin version
	 * @var string
	 */
	private $version;
	
	/**
	 * Initilizes wiki shortcodes
	 * 
	 * @since 1.0.0
	 */
	public function __construct( $slug, $version ) {
		
		$this->slug		= $slug;
		$this->version	= $version;
		
	}
	
	/**
	 *  Shortcode to create internal links
	 *  
	 * @since 1.0.0
	 * @param  array $atts    Shortcode attributes
	 * @param  string $content Content
	 * @return string          Altered content
	 */
	public function internal_link( $atts, $content = null ) {
		
		if( null == $content)
			return $content;
		
		$link		= explode( '|', $content );
		$section	= explode( '#' , html_entity_decode($link[0]) );
		$page		= get_page_by_title( $section[0], 'OBJECT', 'neuwiki' );
		$title		= ( isset($link[1]) ) ? $link[1] : $link[0];
		$href		= (  null === $page ) ? apply_filters( 'neuwiki_new_wiki_link', get_bloginfo('url') . '/wp-admin/post-new.php?post_type=neuwiki', $title ) : get_permalink( $page->ID );
		
		if( ( null !== $page ) && isset($section[1]) )
			$href = rtrim( $href, '/' ) . '#' . urlencode( htmlentities($section[1]) );
		//return '<pre>' . print_r($section, true) . '</pre>';
		return sprintf( '<a href="%s" target="_blank">%s</a>', $href, $title );
		
	}
	
}