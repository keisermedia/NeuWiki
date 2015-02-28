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
<input type="radio" name="neuwiki['toc']" id="neuwiki-radio-default" value="default" checked="checked"> <label for="neuwiki-radio-default" class="selectit"><?php _e( 'Default', 'neuwiki' ); ?></label><br>
<input type="radio" name="neuwiki['toc']" id="neuwiki-radio-disable" value="disabled"> <label for="neuwiki-radio-disable" class="selectit"><?php _e( 'Disable', 'neuwiki' ); ?></label><br>
<input type="radio" name="neuwiki['toc']" id="neuwiki-radio-force" value="forced"> <label for="neuwiki-radio-force" class="selectit"><?php _e( 'Force', 'neuwiki' ); ?></label><br>