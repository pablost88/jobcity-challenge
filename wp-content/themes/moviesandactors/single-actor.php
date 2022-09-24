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
	$actor_name       = get_field( 'actor_name' );
	$actor_photo      = get_the_post_thumbnail_url();
	$actor_popularity = get_field( 'popularity' );
	$movies           = get_field( 'actor_movies' );
	$api_actor_id     = get_field( 'themoviedb_id' );
	$extra_data       = Jobsity::get_actor_extra_data( $api_actor_id );
	?>

	<div>
		<figure>

		</figure>

		<div>
			<h1><?php echo esc_attr( $actor_name ); ?></h1>
			<p><?php echo esc_attr( $extra_data['bio'] ); ?></p>
			<div>
				<h2>Popularity: <?php echo esc_attr( $actor_popularity ); ?></h2>
				<h2>Birthday: <?php echo esc_attr( $extra_data['birthday'] ); ?></h2>
				<h2>Place of Birth: <?php echo esc_attr( $extra_data['place_of_birth'] ); ?></h2>
				<h2>Day of death: <?php echo esc_attr( $extra_data['day_of_death'] ); ?></h2>
				<h2>Website: <?php echo esc_attr( $extra_data['website'] ); ?></h2>
			</div>

			<div>
				<h2>Movies</h2>
				<ul>
				<?php
				foreach ( $movies as $movie ) {
					$movie_slug = get_permalink( $movie->ID );
					echo '<li><a href="' . esc_attr( $movie_slug ) . '">' . esc_attr( $movie->post_title ) . '</a></li>';
				}
				?>
				</ul>
			</div>

			<div>
				<h2>Gallery</h2>
				<?php
				$actor_images = $extra_data['gallery'];
				if ( ! empty( $actor_images ) ) {
					echo '<div>';
					foreach ( $actor_images as $key => $actor_image ) {
						if ( 10 === $key ) { // Allow only 10 reviews.
							break;
						}
						echo '<figure></figure>';
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
