<?php
/**
 * The archive template file for movies
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
?>

<?php
if ( have_posts() ) :
	echo '<h1 class="margin-top-50 text-center h1-big">All Movies</h1>';
	echo '<div class="responsive-grid-1">';
	while ( have_posts() ) :
		the_post();
		$movie_slug   = get_permalink();
		$movie_poster = get_the_post_thumbnail_url();
		?>

		<div class="margin-top-50">
			<h1><a href="<?php echo esc_attr( $movie_slug ); ?>"><?php the_title(); ?></a></h1>
			<div class="margin-top-10">
				<img src="<?php echo esc_attr( $movie_poster ); ?>" />
			</div>
		</div>

		<?php
	endwhile;
	echo '</div>';
endif;
?>


<?php
get_footer();
