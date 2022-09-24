<?php
/**
 * The main template file to show a single movie
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package moviesandactors
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();
	$movie_title  = get_the_title();
	$movie_poster = get_the_post_thumbnail_url();
	$movie_terms  = get_the_terms( $post->ID, 'genre' );
	$release_date = get_field( 'release_date' );
	$cast         = get_field( 'actor_movies' );
	$api_movie_id = get_field( 'themoviedb_id' );
	$extra_data   = Jobsity::get_movie_extra_data( $api_movie_id );

	//error_log( $movie_title );
	//error_log( $movie_poster );
	//error_log( print_r( $movie_terms, true ) );
	//error_log( $release_date );
	//error_log( 'The cast is: ' );
	//error_log( print_r( $cast, true ) );

endwhile;

get_footer();
?>