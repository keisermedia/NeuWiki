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
	
	/**
	 * Sanitizes the wiki content in preparation for heading extraction
	 * 
	 * @since 1.0.0
	 * @param  string $content The wiki content
	 * @return string          The sanitized content
	 */
	private function sanitize_content( $content ) {
		
		$dom = new DOMDocument('1.0');
		libxml_use_internal_errors(true);
		$dom->loadHTML( $content );
		libxml_use_internal_errors(false);
		
		$remove_nodes = array(
			$dom->getElementsByTagName('table'),
			$dom->getElementsByTagName('code'),
			$dom->getElementsByTagName('pre'),
		);
		
		$dom_nodes = array();
		
		foreach( $remove_nodes as $nodes ) {
			
			foreach( $nodes as $node ) {
				
				$dom_nodes[] = $node;
				
			}
			
		}
		
		foreach( $dom_nodes as $node )
			$node->parentNode->removeChild( $node );
		
		return $dom->saveHTML();
		
	}
	
	/**
	 * Extracts headings from wiki content
	 * 
	 * @since 1.0.0
	 * @param  string $content The wiki content
	 * @return array          The extracted strings
	 */
	private function extract_headings( $content, $limit ) {
		
		preg_match_all('/(<h([1-6]{1})[^>]*>)(.*)<\\/h\\2>/msuU', $content, $headings, PREG_SET_ORDER);
		
		return $headings;
		
	}
	
	/**
	 * Generates Table of Content Code
	 * 
	 * @since 1.0.0
	 * @param  array $headings Headings in the content
	 * @param  array $settings TOC settings
	 * @return string           TOC HTML
	 */
	private function generate_toc( $headings, $settings ) {
		
		$current_depth	= 6;
		$numbered		= array();
		
		foreach( $headings as $heading ) {
			
			if( $current_depth > $heading[2])
				$current_depth = $heading[2];
			
		}
		
		$numbered[$current_depth] = 0;
		$numbered_min	= $current_depth;
		
		$toc  = '<div id="neuwiki_toc" class="toc">';
		$toc .= '	<div class="toc-title">' . __( 'Contents', 'neuwiki' ) . '</div>';
		$toc .= '	<ul>';
		
		for( $i = 0; $i < count($headings); $i++ ) {
			
			if( $current_depth == $headings[$i][2] )
				$toc .= '		<li>';
			
			// Start TOC
			if( $current_depth != $headings[$i][2] ) {
				
				for( $current_depth; $current_depth < $headings[$i][2]; $current_depth++ ) {
					
					$numbered[$current_depth + 1] = 0;
					$toc .= '			<ul><li>';
					
				}
				
			}
			
			// List Heading
			$toc .= '			<a href="#' . urlencode($headings[$i][3]) . '"><span class="toc-' . ( $current_depth - $numbered_min + 1 ) . '">';
			
			for( $j = $numbered_min; $j < $current_depth; $j++ ) {
				
				$loc = ( $numbered[$j] ) ? $numbered[$j] : 0;
				
				$toc .= $loc . '.';
				
			}
			
			 $toc .= ( $numbered[$current_depth] + 1 ) . '</span> ';
			 $numbered[$current_depth]++;
			 
			 $toc .= strip_tags($headings[$i][0]) . '</a>';
			
			// End Heading
			if( $i != count($headings) - 1 ) {
				
				if( $current_depth > $headings[$i + 1][2] ) {
					
					for( $current_depth; $current_depth > $headings[$i + 1][2]; $current_depth-- ) {
						
						$toc .= '</li></ul>';
						$numbered[$current_depth] = 0;
						
					}
					
				}
								if( $current_depth == $headings[$i + 1][2])
					$toc .= '</li>';
				
			} else {
				
				for( $current_depth; $current_depth >= $numbered_min; $current_depth-- ) {
					
					$toc .= '</li>';
					
					if( $current_depth != $numbered_min )
						$toc .= '</ul>';
					
				}
				
			}
			
		}
		
		$toc .= '	</ul>';
		$toc .= '</div>';
		
		return $toc;
		
	}
	
	/**
	 * Filters the post content for NeuWiki
	 * 
	 * @since 1.0.0
	 * @param  string $content Contains the post content
	 * @return string          Contains the post content
	 */
	public function the_content( $content ) {
		
		if( 'neuwiki' != get_post_type() || !is_single() )
			return $content;
		
		$defaults = array(
			'toc'	=> 'default',
			'limit'	=> 2,
		);
		
		//Retrieve post TOC settings
		$post_settings = get_post_meta( get_the_ID(), '_neuwiki', true );
		$post_settings = ( is_array($post_settings) ) ? $post_settings : array();
		
		// Retrieve shortcode style TOC attributes
		preg_match( '/\\[\\[toc(.*)\\]\\]/', $content, $shortcode_settings );
		$shortcode_settings = ( isset($shortcode_settings[1]) ) ? shortcode_parse_atts($shortcode_settings[1]) : array();
		
		$settings = array_merge( $defaults, $shortcode_settings, $post_settings );
		
		if( ( isset($settings['toc']) && 'disabled' == $settings['toc'] ) || false !== strpos( '[neuwiki_toc', $content ) )
			return $content;
		
		$s_content	= $this->sanitize_content($content);
		$headings	= $this->extract_headings($s_content, $settings['limit']);
		
		// Adjusts for content with headings starting other than H1
		$min_depth = 6;
		
		foreach( $headings as $heading ) {
			
			if( $min_depth > $heading[2])
				$min_depth = $heading[2];
			
		}
		
		$settings['limit'] = $min_depth + $settings['limit'] - 1;
		
		foreach( $headings as $key => $heading ) {
			
			if( $settings['limit'] < $heading[2] )
				unset($headings[$key]);
			
		}
		
		$headings = array_values($headings);
		
		$link_class	= 'neuwiki-header';
		
		$content = html_entity_decode($content);
		
		foreach( $headings as $heading )
			$content = str_replace( html_entity_decode($heading[0]), '<h' . $heading[2] . ' id="' . urlencode($heading[3]) . '" class="' . $link_class . '">' . $heading[3] . '</h' . $heading[2] . '>', $content);
		
		$toc = $this->generate_toc( $headings, $settings );
		
		if( 'forced' == $settings['toc'] || empty($shortcode_settings) ) {
			
			$content = $toc . $content;
			$toc = '';
			
		}
		
		$content = preg_replace( '/\\[\\[toc(.*)\\]\\]/', $toc, $content );
		
		return $content;
	}
	
}