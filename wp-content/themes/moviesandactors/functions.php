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

add_theme_support( 'post-thumbnails' );


/**
 * Get upcoming movies from the database
 *
 * @return $movies_query Movies with a release date greater than the actual date
 */
function get_upcoming_movies() {

	// Find current date time.
	$date_now = date( 'Y-m-d H:i:s' );

	$movies_query = new WP_Query(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'movie',
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => 'release_date',
					'compare' => '>=',
					'value'   => $date_now,
					'type'    => 'DATETIME',
				),
			),
			'meta_key'       => 'release_date',
			'meta_type'      => 'DATETIME',
			'orderby'        => 'meta_value',
			'order'          => 'asc',
		)
	);

	return $movies_query;
}


/**
 * Get populñar actors from the database.
 */
function get_popular_actors() {
	$actor_query = new WP_Query(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'actor',
			'post_status'    => 'publish',
			'meta_key'       => 'popularity',
			'meta_type'      => 'number',
			'orderby'        => 'meta_value',
			'order'          => 'desc',
		)
	);

	return $actor_query;
}


/**
 * Filter archive pages
 */
function archive_movies( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( is_post_type_archive( 'actor' ) ) {
			$query->query_vars['meta_key'] = 'actor_name';
			$query->query_vars['orderby']  = 'meta_value';
			$query->query_vars['order']    = 'asc';
		}

		if ( is_post_type_archive( 'movie' ) ) {
			$query->query_vars['orderby']  = 'title';
			$query->query_vars['order']    = 'asc';
		}
	}
}

add_action( 'pre_get_posts', 'archive_movies', 1 );
