<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package moviesandactors
 */

register_nav_menus(
	array(
		'primary' => 'Primary Menu',
	)
);


/**
 * Get all movies from the database
 */
function get_movies() {
	$query = new WP_Query(
		array(
			'posts_per_page' => 10,
			'post_type'      => 'movie',
			'post_status'    => 'publish',
		)
	);

	error_log( 'The movies for the homepage are: ' );
	error_log( print_r( $query, true ) );
}
