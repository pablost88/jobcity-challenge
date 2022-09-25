<?php
/**
 * The main template file to show a single actor
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

	$actor_id = get_the_ID();
	totalView( $actor_id );

	custom_search_order_formula( $actor_id );

	$actor_name       = get_field( 'actor_name' );
	$actor_photo      = get_the_post_thumbnail_url();
	$actor_popularity = get_field( 'popularity' );
	$movies           = get_field( 'actor_movies' );
	$api_actor_id     = get_field( 'themoviedb_id' );
	$extra_data       = Jobsity::get_actor_extra_data( $api_actor_id );
	?>

	<div class="max-width-2 center single-actor-block margin-top-50">
		<h1 class="text-center h1-big"><?php echo esc_attr( $actor_name ); ?></h1>
		<div class="align-self-center margin-top-15">
			<img src="<?php echo esc_attr( $actor_photo ); ?>" />
		</div>

		<div>
			<div class="margin-top-15">
				<h1>Overview</h1>
				<p>
				<?php echo esc_attr( $extra_data['bio'] ); ?>
				</p>
			</div>

			<div class="margin-top-30">
				<h1>Popularity: <span><?php echo esc_attr( $actor_popularity ); ?></span></h1>
				<h1 class="margin-top-15">Birthday: <span><?php echo esc_attr( $extra_data['birthday'] ); ?></span></h1>
				<h1 class="margin-top-15">Place of Birth: <span><?php echo esc_attr( $extra_data['place_of_birth'] ); ?></span></h1>
				<h1 class="margin-top-15">Day of death: <span><?php echo esc_attr( $extra_data['day_of_death'] ); ?></span></h1>
				<h1 class="margin-top-15">Website: <span><?php echo esc_attr( $extra_data['website'] ); ?></span></h1>
			</div>

			<div class="margin-top-30">
				<h1>Movies</h1>
				<ul class="clean-list">
				<?php
				if ( $movies ) {
					foreach ( $movies as $movie ) {
						$movie_slug = get_permalink( $movie->ID );
						echo '<li><a href="' . esc_attr( $movie_slug ) . '">' . esc_attr( $movie->post_title ) . '</a></li>';
					}
				}
				?>
				</ul>
			</div>

			<div class="margin-top-30">
				<h1>Gallery</h1>
				<?php
				$actor_images = $extra_data['gallery'];
				if ( ! empty( $actor_images ) ) {
					echo '<div class="responsive-grid-1">';
					foreach ( $actor_images as $key => $actor_image ) {
						if ( 10 === $key ) { // Allow only 10 reviews.
							break;
						}
						echo '
						<div class="margin-top-10">
							<img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2/' . esc_attr( $actor_image->file_path ) . '" />
						</div>';
					}
					echo '</div>';
				}
				?>
			</div>
		</div>
	</div>

	<?php
endwhile;

get_footer();
?>
