<?php
/**
 * Plugin Name: Simple Menu Widget
 * Plugin URI:  https://github.com/scheras/SimpleMenuWidget
 * Description: Simple WordPress widget displaying links list wherever you want.
 * Version:     0.1
 * Author:      ScheRas
 * Author URI:  http://www.scheras.eu/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: simple-menu
 */

require_once dirname ( __FILE__ ).'/class-simple-menu.php';

function sm_load_textdomain () {
	load_plugin_textdomain ( 'simple-menu', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action ( 'plugins_loaded', 'sm_load_textdomain' );

add_action ( 'widgets_init', array ( 'ScheRas\Plugins\Widgets\Simple_Menu_Widget', 'register_widget' ) );