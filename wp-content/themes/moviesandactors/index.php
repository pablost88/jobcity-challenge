<?php
/**
 * The main template file
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

<!-- Show Movies -->
<?php
$movies_query = get_upcoming_movies();

if ( $movies_query->have_posts() ) :
	$movie_year  = -1;
	$movie_month = -1;

	?>

	<div class="margin-top-50">
		<h1 class="text-center h1-big">Upcoming Movies</h1>

	<?php
	while ( $movies_query->have_posts() ) :
		$movies_query->the_post();

		$movie_slug          = get_permalink();
		$release_date        = get_field( 'release_date', false, false );
		$time_input          = strtotime( $release_date );
		$date_input          = getDate( $time_input );
		$release_date_format = gmdate( 'Y-m-d', $date_input[0] );
		$movie_poster        = get_the_post_thumbnail_url();

		$movie_terms = get_the_terms( $post->ID, 'genre' );

		if ( $movie_year !== $date_input['year'] ) {
			if ( -1 === $movie_year ) {
				echo '<div class="margin-top-50">';
			} else {
				echo '</div><div class="margin-top-50">';
			}

			$movie_year = $date_input['year'];
			echo '<h1 class="h1-big">Movies from ' . esc_attr( $movie_year ) . '</h1>';
			$movie_month = -1;
		};

		if ( $movie_month !== $date_input['mon'] ) {
			$movie_month = $date_input['mon'];

			// Create date object to store the DateTime format.
			$month_obj = DateTime::createFromFormat( '!m', $movie_month );

			// Store the month name to variable.
			$movie_month_name = $month_obj->format( 'F' );

			if ( -1 === $movie_month ) {
				echo '<div class="margin-top-30">';
			} else {
				echo '</div></div><div class="margin-top-30">';
			}

			echo '<h1 class="margin-top-20">' . esc_attr( $movie_month_name ) . '</h1>';
			echo '<div class="responsive-grid-1">';
		}

		?>

		<div class="margin-top-20">
			<h1><a href="<?php echo esc_attr( $movie_slug ); ?>"><?php the_title(); ?></a></h1>
			<div class="margin-top-10">
				<img src="<?php echo esc_attr( $movie_poster ); ?>" />
			</div>
			<h4 class="margin-top-10">Release Date: <span class="font-weight-400"><?php echo esc_attr( $release_date_format ); ?><span></h4>
			<div class="margin-top-10">
				<h4>Genre: </h4>
				<ul class="clean-list">
				<?php
				foreach ( $movie_terms as $movie_term ) {
					echo '<li>' . esc_attr( $movie_term->name ) . '</li>';
				}
				?>
				</ul>
			</div>
		</div>

	<?php endwhile; ?>

	</div>

<?php endif; ?>


<!-- Show Actors -->

<?php
	wp_reset_postdata();
	$actors_query = get_popular_actors();

if ( $actors_query->have_posts() ) :
	echo '<h1 class="margin-top-50 text-center h1-big">Popular Actors</h1>';
	echo '<div class="responsive-grid-1">';
	while ( $actors_query->have_posts() ) :
		$actors_query->the_post();
		$actor_name   = get_field( 'actor_name' );
		$actor_poster = get_the_post_thumbnail_url();
		$actor_slug   = get_permalink();
		?>

		<div class="margin-top-50">
			<h1><a href="<?php echo esc_attr( $actor_slug ); ?>"><?php echo esc_attr( $actor_name ); ?></a></h1>
			<div class="margin-top-10">
				<img src="<?php echo esc_attr( $actor_poster ); ?>" />
			</div>
		</div>

		<?php
	endwhile;
	echo '</div>';
	endif;

	wp_reset_postdata();
?>



<!-- Show Actors -->
<div>

</div>

<?php
get_footer();
