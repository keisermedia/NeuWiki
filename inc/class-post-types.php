<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
 * Register post types and taxonomies. Also sets up capabilities.
 *
 * @link       http://keisermedia.com
 * @since      1.0.0
 * @package    NeuWiki
 * @subpackage NeuWiki/inc
 * @author     Keiser Media <support@keisermedia.com>
 */
final class NeuWiki_Post_Types {
	
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
	 * Initilizes wiki post type
	 * 
	 * @since 1.0.0
	 */
	public function __construct( $slug, $version ) {
		
		$this->slug		= $slug;
		$this->version	= $version;
		
	}
	
	public function register_wiki() {
		
		$slug = ( defined('NEUWIKI_SLUG') ) ? NEUWIKI_SLUG : 'wiki';
		
		$labels = array(
			'name'               => _x( 'Wikis', 'post type general name', $this->slug ),
			'singular_name'      => _x( 'Wiki', 'post type singular name', $this->slug ),
			'menu_name'          => _x( 'Wikis', 'admin menu', $this->slug ),
			'name_admin_bar'     => _x( 'Wiki', 'add new on admin bar', $this->slug ),
			'add_new'            => _x( 'Add New', 'Wiki', $this->slug ),
			'add_new_item'       => __( 'Add New Wiki', $this->slug ),
			'new_item'           => __( 'New Wiki', $this->slug ),
			'edit_item'          => __( 'Edit Wiki', $this->slug ),
			'view_item'          => __( 'View Wiki', $this->slug ),
			'all_items'          => __( 'All Wikis', $this->slug ),
			'search_items'       => __( 'Search Wikis', $this->slug ),
			'parent_item_colon'  => __( 'Parent Wikis:', $this->slug ),
			'not_found'          => __( 'No wikis found.', $this->slug ),
			'not_found_in_trash' => __( 'No wikis found in Trash.', $this->slug )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $slug ),
			'capability_type'    => 'wiki',
			'capabilities'		 => array(
				'publish_posts' => 'publish_wikis',
				'edit_posts' => 'edit_wikis',
				'edit_others_posts' => 'edit_others_wikis',
				'delete_posts' => 'delete_wikis',
				'delete_others_posts' => 'delete_others_wikis',
				'read_private_posts' => 'read_private_wikis',
				'edit_post' => 'edit_wiki',
				'delete_post' => 'delete_wiki',
				'read_post' => 'read_wiki',	
			),
			'map_meta_cap'		 => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_icon'			 => 'dashicons-id-alt',
			'menu_position'      => 21,
			'supports'           => array( 'title', 'editor', 'author', 'comments', 'revisions' )
		);

		register_post_type( 'neuwiki', $args );
		
	}
	
	/**
	 * Registers the category taxonomy
	 * 
	 * @since 1.0.0
	 */
	public function register_category_tax() {
		
		$slug = ( defined('NEUWIKI_SLUG') ) ? NEUWIKI_SLUG : 'wiki';
		
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Categories' ),
			'all_items'         => __( 'All Categories' ),
			'parent_item'       => __( 'Parent Category' ),
			'parent_item_colon' => __( 'Parent Category:' ),
			'edit_item'         => __( 'Edit Category' ),
			'update_item'       => __( 'Update Category' ),
			'add_new_item'      => __( 'Add New Category' ),
			'new_item_name'     => __( 'New Category Name' ),
			'menu_name'         => __( 'Category' ),
		);

		$args = array(
			'labels'            => $labels,
			'capabilities'		=> array(
				'manage_terms'	=> 'manage_wiki_terms',
				'edit_terms'	=> 'edit_wiki_terms',
				'delete_terms'	=> 'delete_wiki_terms',
				'assign_terms'	=> 'edit_wikis',	
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $slug . '/category' ),
		);

		register_taxonomy( 'neuwiki_category', array( 'neuwiki' ), $args );
	
	}
	
	/**
	 * Registers the tag taxonomy
	 * 
	 * @since 1.0.0
	 */
	public function register_tag_tax() {
		
		$slug = ( defined('NEUWIKI_SLUG') ) ? NEUWIKI_SLUG : 'wiki';
		
		// Add new taxonomy, NOT hierarchical (like tags)
		$labels = array(
			'name'                       => _x( 'Tags', 'taxonomy general name' ),
			'singular_name'              => _x( 'Tag', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Tags' ),
			'popular_items'              => __( 'Popular Tags' ),
			'all_items'                  => __( 'All Tags' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag' ),
			'update_item'                => __( 'Update Tag' ),
			'add_new_item'               => __( 'Add New Tag' ),
			'new_item_name'              => __( 'New Tag Name' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items'        => __( 'Add or remove tags' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags' ),
			'not_found'                  => __( 'No tags found.' ),
			'menu_name'                  => __( 'Tags' ),
		);

		$args = array(
			'labels'            => $labels,
			'capabilities'		=> array(
				'manage_terms'	=> 'manage_wiki_terms',
				'edit_terms'	=> 'edit_wiki_terms',
				'delete_terms'	=> 'delete_wiki_terms',
				'assign_terms'	=> 'edit_wikis',	
			),
			'meta_box_cb'			=> array( new NeuWiki_Meta_Boxes, 'search_taxonomy' ),
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => $slug . '/tag' ),
		);

		register_taxonomy( 'neuwiki_tag', 'neuwiki', $args );
		
	}
	
	/**
	 * Registers the flag taxonomy
	 * 
	 * @since 1.0.0
	 */
	public function register_flag_tax() {
		
		$slug = ( defined('NEUWIKI_SLUG') ) ? NEUWIKI_SLUG : 'wiki';
		
		// Add new taxonomy, NOT hierarchical (like tags)
		$labels = array(
			'name'                       => _x( 'Flags', 'taxonomy general name' ),
			'singular_name'              => _x( 'Flag', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Flags' ),
			'popular_items'              => __( 'Popular Flags' ),
			'all_items'                  => __( 'All Flags' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Flag' ),
			'update_item'                => __( 'Update Flag' ),
			'add_new_item'               => __( 'Add New Flag' ),
			'new_item_name'              => __( 'New Flag Name' ),
			'separate_items_with_commas' => __( 'Separate flags with commas' ),
			'add_or_remove_items'        => __( 'Add or remove flags' ),
			'choose_from_most_used'      => __( 'Choose from the most used flags' ),
			'not_found'                  => __( 'No flags found.' ),
			'menu_name'                  => __( 'Flags' ),
		);

		$args = array(
			'labels'            => $labels,
			'capabilities'		=> array(
				'manage_terms'	=> 'manage_wiki_terms',
				'edit_terms'	=> 'edit_wiki_terms',
				'delete_terms'	=> 'delete_wiki_terms',
				'assign_terms'	=> 'edit_wikis',	
			),
			'meta_box_cb'			=> array( new NeuWiki_Meta_Boxes, 'search_taxonomy' ),
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => $slug . '/flag' ),
		);

		register_taxonomy( 'neuwiki_flag', 'neuwiki', $args );
		
	}
	
	/**
	 * Registers default flags
	 * 
	 * @since 1.0.0
	 */
	public function register_default_flags() {
		
		$default_flags = array(
			
			array(
				__( 'Poor Attribution', $this->slug ),
				__( 'This page does not properly cite sources.', $this->slug ),
			),
			
			array(
				__( 'Readability', $this->slug ),
				__( 'This page is difficult to read and requires additional editing.', $this->slug ),
			),
			
			array(
				__( 'Spam', $this->slug ),
				__( 'This is page does not contain content relevant to the site.', $this->slug ),
			),

			
		);
		
		foreach( $default_flags as $flag ) {
			
			$slug = strtolower( str_replace(' ', '-', $flag[0]) );
			
			if( term_exists( $slug, 'neuwiki_flag' ) )
				continue;
			
			wp_insert_term(
				$flag[0],
				'neuwiki_flag',
				array(
					'description'	=> $flag[1],
					'slug'			=> $slug,
				) );
			
		}
		
	}

	/**
	 * Register the JavaScript for the custom post type admin.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $page ) {
		global $post_type;
		
		if( 'neuwiki' == $post_type ) {
			
			switch( $page ) {
				
				case 'post.php':
				case 'post-new.php':
					
					wp_enqueue_script( $this->slug . '-taxonomy-auto-complete', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/admin-taxonomy-autocomplete.js', array( 'jquery' ), $this->version, false );
					wp_enqueue_script('jquery-ui-autocomplete');
					wp_enqueue_script('suggest');
					break;
					
			}
			
		}
		
	}
	
}