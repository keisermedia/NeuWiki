<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }
/**
* Displays NeuWiki Settings
*
* @link       http://keisermedia.com
* @since      1.0.0
* @package    NeuWiki
* @subpackage NeuWiki/admin
*/

$settings_tabs	= apply_filters( 'neuwiki_settings_tabs', array(
	'general'	=> __( 'General', $this->slug ),
) );
$active_tab		= isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], $settings_tabs ) ? $_GET[ 'tab' ] : 'general';
?>
<div class="wrap">
	<h2><?php _e( 'NeuWiki Settings', $this->slug ); ?></h2>
	<h2 class="nav-tab-wrapper">
		<?php
		foreach( $settings_tabs as $tab_id => $tab_name ) {
			$tab_url = add_query_arg( array(
				'settings-updated' => false,
				'tab' => $tab_id
			) );
			$active = $active_tab == $tab_id ? ' nav-tab-active' : '';
			echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
				echo esc_html( $tab_name );
			echo '</a>';
		}
		?>
	</h2>
	<div id="tab_container">
		<form method="post" action="options.php">
			<table class="form-table">
			<?php
			settings_fields( 'neuwiki_settings' );
			do_settings_fields( 'neuwiki_settings_' . $active_tab, 'neuwiki_settings_' . $active_tab );
			?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div><!-- #tab_container-->
</div><!-- .wrap -->