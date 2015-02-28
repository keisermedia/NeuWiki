<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
* Contains meta box organization.
*
* @link       http://keisermedia.com
* @since      1.0.0
* @package    NeuWiki
* @subpackage NeuWiki/admin
* @author     Keiser Media <support@keisermedia.com>
*/

class NeuWiki_Meta_Boxes {
	
	/**
	 * The wiki meta data
	 * @var array
	 */
	private $settings;
	
	/**
	 * Contains boolean for set nonce
	 * @var boolean
	 */
	private $nonce;
	
	/**
	 * Registers the wiki meta data
	 */
	public function get_settings( $post ) {
		
		if( empty($this->settings) )
			$this->settings = get_post_meta( $post->ID, '_neuwiki', true );
		
		return $this->settings;
	}
	
	private function check_nonce() {
		
		if( empty( $this->nonce ) ) {
			
			$this->nonce = true;
			wp_nonce_field( 'neuwiki_meta_box', 'neuwiki_meta_box_nonce' );
			
		}
		
	}
	
	/**
	 * Displays Table of Contents settings
	 *
	 * @since    1.0.0
	 */
	public function toc( $post ) {
		
		$this->check_nonce();
		include( 'partials/meta-box-toc.php' );
		
	}
	
	/**
	 * Displays searchable taxonomy
	 *
	 * @since    1.0.0
	 */
	public function search_taxonomy( $post, $box ) {
		
		$this->check_nonce();
		
		$defaults = array( 'taxonomy' => 'post_tag' );
		if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) )
			$args = array();
		
		else
			$args = $box['args'];
		
		$r = wp_parse_args( $args, $defaults );
		$tax_name = esc_attr( $r['taxonomy'] );
		$taxonomy = get_taxonomy( $r['taxonomy'] );
		$user_can_assign_terms = current_user_can( $taxonomy->cap->assign_terms );
		$comma = _x( ',', 'tag delimiter' );
		
		include( 'partials/meta-box-taxonomy.php' );
		
	}
	
	/**
	 * Sanitizes meta box fields
	 * @param  int $post_id
	 * @return void
	 */
	public function save_meta_boxes( $post_id ) {

		if( !isset( $_POST['neuwiki_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['neuwiki_meta_box_nonce'], 'neuwiki_meta_box' ) )
			return;

		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		
		if( ! current_user_can( 'edit_post', $post_id ) )
			return;

		if ( !isset( $_POST['neuwiki'] ) )
			return;
		
		if( isset( $_POST['neuwiki']['toc'] ) ) {
			
			if( in_array( $_POST['neuwiki']['toc'], array( 'default', 'disable', 'force' ) ) )
				$this->settings['toc'] = $_POST['neuwiki']['toc'];
			
		}
		
		update_post_meta( $post_id, '_neuwiki', $this->settings );
		
	}
	
}