<?php if( ! defined('ABSPATH') ) { header('Status: 403 Forbidden'); header('HTTP/1.1 403 Forbidden'); exit(); }

/**
* @link             http://keisermedia.com
* @since            1.0.0
* @package          NeuWiki
*
* @wordpress-plugin
* Plugin Name:      NeuWiki
* Plugin URI:       http://keisermedia.com/
* Description:      NeuWiki is a wiki plugin for WordPress which offers intuitive functionality.
* Version:          0.0.1-alpha
* Author:           Keiser Media Group
* Author URI:       http://keisermedia.com/
* License:          GPLv2 or later
* License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:      neuwiki
* Domain Path:      /lang
*
*	Copyright 2015  keisermedia.com  (email: support@keisermedia.com)
*
*	This program is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
**/

require_once plugin_dir_path( __FILE__ ) . 'inc/class-activate.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-deactivate.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/class-neuwiki.php';

register_activation_hook( __FILE__, array( 'NeuWiki_Activate', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'NeuWiki_Deactivate', 'deactivate' ) );

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_neuwiki () {

	$neuwiki = new NeuWiki( 'NeuWiki', '0.0.1-alpha', '3.4' );
	$neuwiki->run();

}
run_neuwiki();