<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
 * Registers settings.
 *
 * @link       http://keisermedia.com
 * @since      1.0.0
 * @package    NeuWiki
 * @subpackage NeuWiki/inc
 * @author     Keiser Media <support@keisermedia.com>
 */
final class NeuWiki_Settings {
	
	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string The slug of this plugin.
	 */
	private $slug;
	
	/**
	 * The settings of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string The settings of this plugin.
	 */
	private $settings;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $slug       The slug of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $slug ) {
		
		$this->slug = $slug;
		
		$this->settings = get_option( 'neuwiki_settings', array() );
		
	}
	/**
	 * Registers sections and settings
	 * 
	 * @since 1.0.0
	 */
	public function register_settings() {
		
		foreach( $this->get_settings() as $section => $settings ) {
			
			add_settings_section(
				'neuwiki_settings_' . $section,
				__return_null(),
				'__return_false',
				'neuwiki_settings_' . $section
			);
			
			foreach( $settings as $option ) {
				
				add_settings_field(
					'neuwiki_settings[' . $option['id']	. ']',
					isset( $option['name'] ) ? $option['name'] : '',
					method_exists( $this, $option['type'] . '_callback' ) ? array( $this, $option['type'] . '_callback' ) : array( $this, 'missing_callback' ),
					'neuwiki_settings_' . $section,
					'neuwiki_settings_' . $section,
					array(
						'section'	=> $section,
						'id'		=> isset( $option['id'] )		? $option['id']			: null,
						'desc'		=> !empty( $option['desc'] )	? $option['desc']		: '',
						'name'		=> isset( $option['name'] )		? $option['name']		: null,
						'size'		=> isset( $option['size'] )		? $option['size']		: null,
						'options'	=> isset( $option['options'] )	? $option['options']	: '',
						'std'		=> isset( $option['std'] )		? $option['std']		: '',
						'min'		=> isset( $option['min'] )		? $option['min']		: null,
						'max'		=> isset( $option['max'] )		? $option['max']		: null,
					)
				);
				
			}
			
		}
		
		register_setting( 'neuwiki_settings', 'neuwiki_settings', array( $this, 'sanitize' ) );
		
	}
	
	/**
	 * Gets NeuWiki Settings
	 * 
	 * @since 1.0.0
	 * 
	 * @return array Returns sections and settings
	 */
	public function get_settings() {
		
		$pages = array( 'default' => __( 'Default', $this->slug ) ); 
		
		foreach ( get_pages() as $page )
			$pages[$page->ID] = $page->post_title;
		
		$settings = array(
			
			'general' => apply_filters( 'neuwiki_settings_general', array(
				
				
				'wiki_front_page'	=> array(
					'id'		=> 'wiki_front_page',
					'name'		=> __( 'Wiki Front Page', $this->slug ),
					'desc'		=> __( 'Select which page to display as the wiki front page. <strong>Note: The default settings will display the Wiki archive.</strong>', $this->slug ),
					'type'		=> 'select',
					'options'	=> $pages,
					'std'		=> 'default',
				),
				
				'publish_pages'	=> array(
					'id'		=> 'publish_pages',
					'name'		=> __( 'Publish Wiki Pages', $this->slug ),
					'desc'		=> __( 'Select which groups can publish wiki pages.', $this->slug ),
					'type'		=> 'select',
					'options'	=> array(
						'editors'		=> __( 'Editors', $this->slug ),
						'site_users'	=> __( 'Site Users', $this->slug ),
						'anyone'		=> __( 'Anyone', $this->slug ),
					),
					'std'		=> 'editors',
				),
				
				'edit_pages'	=> array(
					'id'		=> 'edit_pages',
					'name'		=> __( 'Edit Wiki Pages', $this->slug ),
					'desc'		=> __( 'Select which groups can edit wiki pages.', $this->slug ),
					'type'		=> 'select',
					'options'	=> array(
						'editors'		=> __( 'Editors', $this->slug ),
						'site_users'	=> __( 'Site Users', $this->slug ),
						'anyone'		=> __( 'Anyone', $this->slug ),
					),
					'std'		=> 'editors',
				),
				
				'edit_privs'	=> array(
					'id'		=> 'edit_privs',
					'name'		=> __( 'Edit Wiki Privileges', $this->slug ),
					'desc'		=> __( 'Select which groups can edit wiki page privileges.', $this->slug ),
					'type'		=> 'select',
					'options'	=> array(
						'editors'		=> __( 'Editors', $this->slug ),
						'site_users'	=> __( 'Site Users', $this->slug ),
						'anyone'		=> __( 'Anyone', $this->slug ),
					),
					'std'		=> 'editors',
				),
				
			) ),
			
		);
		
		return apply_filters( 'neuwiki_settings', $settings );
		
	}
	
	/**
	 * Renders setting for missing callback
	 * 
	 * @since 1.0.0
	 */
	public function missing_callback( $args ) {
		
		printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', $this->slug ), $args['id'] );
		
	}
	
	/**
	 * Renders multicheck boxes
	 * 
	 * @since 1.0.0
	 * @param  array $args Arguments passed by the setting
	 * @return void
	 */
	public function multicheck_callback( $args ) {
		
		if ( ! empty( $args['options'] ) ) {
			
			foreach( $args['options'] as $key => $option ):
				
				$enabled = ( isset( $this->settings[$args['id']][$key] ) || $args['std'] == $key ) ? $option : NULL;
				
				echo '<input name="neuwiki_settings[' . $args['id'] . '][' . $key . ']" id="neuwiki_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
				echo '<label for="neuwiki_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
			
			endforeach;
			
			echo '<p class="description">' . $args['desc'] . '</p>';
		}
			
	}
	
	public function select_callback( $args ) {

		if( isset( $this->settings[ $args['id'] ] ) )
			$value = $this->settings[ $args['id'] ];
		
		else
			$value = isset( $args['std'] ) ? $args['std'] : '';
		
		if( isset( $args['placeholder'] ) )
		    $placeholder = $args['placeholder'];
		
		else
		    $placeholder = '';
		
		echo '<select id="neuwiki_settings[' . $args['id'] . ']" name="neuwiki_settings[' . $args['id'] . ']" data-placeholder="' . $placeholder . '" />';
		
		foreach ( $args['options'] as $option => $name ) {
			
			$selected = selected( $option, $value, false );
			echo '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
			
		}
		
		echo '</select>';
		echo '<label for="neuwiki_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';
			
	}
	
	public function sanitize( $input = array() ) {
		
		$output = $input;
		
		add_settings_error( 'neuwiki', '', __( 'Settings updated.', $this->slug ), 'updated' );
		
		return $output;
		
	}
	
}