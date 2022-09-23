<?php
/**
 * @package Jobsity
 * Plugin Name:       Jobsity Challenge
 * Plugin URI:        https://github.com/pablost88/jobcity-challenge
 * Description:       Handle the necessary functionality for the Jobsity challenge.
 * Version:           0.1.0
 * Author:            Pablo Tocho
 * Author URI:        https://github.com/pablost88
 * Text Domain:       jobsity-challenge
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'JOBSITY__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once plugin_dir_path( __FILE__ ) . 'class-jobsity.php';

register_activation_hook( __FILE__, array( 'Jobsity', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Jobsity', 'plugin_deactivation' ) );

add_action( 'init', array( 'Jobsity', 'init' ) );


