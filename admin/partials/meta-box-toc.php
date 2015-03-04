<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }
/**
* Displays the Table of Contents settings
*
* @link       http://keisermedia.com
* @since      1.0.0
* @package    NeuWiki
* @subpackage NeuWiki/admin/partials
*/
?>
<input type="radio" name="neuwiki[toc]" id="neuwiki-radio-default" value="default" <?php checked( $neuwiki_meta['toc'], 'default' ); ?>> <label for="neuwiki-radio-default" class="selectit"><?php _e( 'Default', 'neuwiki' ); ?></label><br>
<input type="radio" name="neuwiki[toc]" id="neuwiki-radio-disable" value="disabled" <?php checked( $neuwiki_meta['toc'], 'disabled' ); ?>> <label for="neuwiki-radio-disable" class="selectit"><?php _e( 'Disable', 'neuwiki' ); ?></label><br>
<input type="radio" name="neuwiki[toc]" id="neuwiki-radio-force" value="forced" <?php checked( $neuwiki_meta['toc'], 'forced' ); ?>> <label for="neuwiki-radio-force" class="selectit"><?php _e( 'Force', 'neuwiki' ); ?></label><br>