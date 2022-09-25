<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
		$result_slug      = get_permalink();
		$result_poster    = get_the_post_thumbnail_url();
		$result_post_type = get_post_type();
		$more_info        = 'Full Post';

		$search_filter_value = get_post_meta( $id, 'search_filter', true );
		$title_post = get_the_title();
		error_log( "Search filter for $title_post is" );
		error_log( print_r( $search_filter_value, true ) );

		if ( 'movie' === $result_post_type ) {
			$more_info = 'More info about this movie';
		} elseif ( 'actor' === $result_post_type ) {
			$more_info = 'More info about this actor';
		}
		?>

		<div class="margin-top-50">
			<h1><a href="<?php echo esc_attr( $result_slug ); ?>"><?php the_title(); ?></a></h1>
			<div class="margin-top-10">
				<img src="<?php echo esc_attr( $result_poster ); ?>" />
			</div>
			<a class="margin-top-10" href="<?php echo esc_attr( $result_slug ); ?>"><?php echo esc_attr( $more_info ); ?></a>
		</div>

		<?php
	endwhile;
	echo '</div>';
endif;
?>


<?php
get_footer();
