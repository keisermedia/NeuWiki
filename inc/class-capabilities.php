<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
 * Registers wiki roles and capabilities.
 *
 * @link       http://keisermedia.com
 * @since      1.0.0
 * @package    NeuWiki
 * @subpackage NeuWiki/inc
 * @author     Keiser Media <support@keisermedia.com>
 */
final class NeuWiki_Capabilities {
	
	/**
	 * Maps custom capabilities for wiki post type
	 * 
	 * @since 1.0.0
	 * 
	 * @param array  $caps    The user's actual capabilities.
	 * @param string $cap     Capability name.
	 * @param int    $user_id The user ID.
	 * @param array  $args    Adds the context to the cap. Typically the object ID.
	 * @return array  Returns the user's actual capabilities.
	 */
	public function map_meta_cap( $caps, $cap, $user_id, $args ) {
		
		if ( 'edit_wiki' == $cap || 'delete_wiki' == $cap || 'read_wiki' == $cap ) {
			
			$post = get_post( $args[0] );
			$post_type = get_post_type_object( $post->post_type );

			$caps = array();
			
		}
		
		if ( 'edit_wiki' == $cap ) {
			
			if ( $user_id == $post->post_author )
				$caps[] = $post_type->cap->edit_posts;
			
			else
				$caps[] = $post_type->cap->edit_others_posts;
			
		} elseif ( 'delete_wiki' == $cap ) {
			
			if ( $user_id == $post->post_author )
				$caps[] = $post_type->cap->delete_posts;
			
			else
				$caps[] = $post_type->cap->delete_others_posts;
			
		} elseif ( 'read_wiki' == $cap ) {

			if ( 'private' != $post->post_status )
				$caps[] = 'read';
			
			elseif ( $user_id == $post->post_author )
				$caps[] = 'read';
			
			else
				$caps[] = $post_type->cap->read_private_posts;
			
		}

		return $caps;
		
	}
	
	/**
	 * Adds capabilities for wiki post type
	 * 
	 * @since 1.0.0
	 */
	public function add_capabilities() {
		global $wp_roles;
		
		if( class_exists('WP_ROLES') ) {
			
			if( !isset($wp_roles) )
				$wp_roles = new WP_Roles();
			
		}
		
		if( !is_object( $wp_roles ) )
			return;
		
		$capabilities 	= $this->get_core_capabilities();
		$all_roles 		= get_editable_roles();
		$edit_roles		= array();
		$publish_roles	= array();
		$publish_limit	= neuwiki_get_option( 'publish_pages', 'editors' );
		$edit_limit		= neuwiki_get_option( 'edit_pages', 'editors' );
		
		
		
		if( 'anyone' == $publish_limit )
			$edit_limit = $publish_limit;
		
		elseif( 'site_users' == $publish_limit && 'editors' == $edit_limit )
			$edit_limit == $publish_limit;
		
		foreach( $all_roles as $role_name => $role_info) {
		
			foreach( $capabilities as $limit => $limit_caps) {
				
				foreach( $limit_caps as $capability ) {
					
					$wp_roles->remove_cap( $role_name, $capability );
					
				}
				
				if( ( 'edit' == $limit && 'site_users' == $edit_limit )
				|| ( 'publish' == $limit && 'site_users' == $publish_limit )
				|| ( in_array( $role_name, array( 'administrator', 'editor' ) ) ) ) {
					
					foreach( $limit_caps as $capability ) {
						
						$wp_roles->add_cap( $role_name, $capability );
						
					}
					
				}
				
			}
			
		}
		
		
	}
	
	public function get_core_capabilities() {
		
		$capabilities			= array();
		$type 					= 'wiki';
		$capabilities['edit']	= array(
			
			"edit_{$type}s",
			"delete_{$type}s",
			"edit_others_{$type}s",
			"edit_published_{$type}s",
			
			"assign_{$type}_terms",
			
		);
		
		$capabilities['publish'] = array(
			
			"publish_{$type}s",
			
		);
		
		$capabilities['editors'] = array(
			
			"read_private_{$type}s",
			"edit_private_{$type}s",
			"delete_others_{$type}s",
			"delete_private_{$type}s",
			"delete_published_{$type}s",
			
			"manage_{$type}_terms",
			"edit_{$type}_terms",
			"delete_{$type}_terms",
		);
		
		return $capabilities;
		
	}
	
	
}