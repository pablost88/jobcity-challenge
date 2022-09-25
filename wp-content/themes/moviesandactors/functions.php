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
 * Enqueue theme styles and scripts
 */
function moviesandactors_scripts() {
	wp_enqueue_style( 'moviesandactors-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
	// Google Fonts Styles.
	wp_enqueue_style( 'moviesandactors-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap', array(), null );
}

add_action( 'wp_enqueue_scripts', 'moviesandactors_scripts' );


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
function custom_filters( $query ) {
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

		if ( is_search() ) {
			$query->query_vars['meta_key'] = 'search_filter';
			$query->query_vars['type']     = 'numeric';
			$query->query_vars['orderby']  = 'meta_value';
			$query->query_vars['order']    = 'desc';
		}
	}
}

add_action( 'pre_get_posts', 'custom_filters', 1 );


/**
 * Register counter for movies and actors
 */
function totalView( $id ) {
	$count = 1;
	$check = get_post_meta( $id, 'count', true );
	if ( ! $check ) {
		add_post_meta( $id, 'count', $count, true );
	} else {
		$inc = $check + 1;
		update_post_meta( $id, 'count', $inc );
	}
}

/**
 * Register search formula for movies and actors
 */
function custom_search_order_formula( $id ) {
	$views      = get_post_meta( $id, 'count', true );
	$popularity = get_field( 'popularity', $id );
	$post_date  = get_the_time( 'U' );
	$now        = new DateTime( 'now' );
	$now        = $now->getTimestamp();
	$diff       = $now - $post_date;
	$num_days   = round( $diff / 86400 );

	$search_filter       = round( ( $views * $popularity ) / $num_days );
	$search_filter_value = get_post_meta( $id, 'search_filter' );

	if ( ! $search_filter_value ) {
		add_post_meta( $id, 'search_filter', $search_filter );
	} else {
		update_post_meta( $id, 'search_filter', $search_filter );
	}
}

