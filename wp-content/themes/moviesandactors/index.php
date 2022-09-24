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

	<div>

	<?php
	while ( $movies_query->have_posts() ) :
		$movies_query->the_post();

		$release_date        = get_field( 'release_date', false, false );
		$time_input          = strtotime( $release_date );
		$date_input          = getDate( $time_input );
		$release_date_format = date( 'Y-m-d', $date_input[0] );

		$movie_terms = get_the_terms( $post->ID, 'genre' );

		/*
		error_log( get_the_title() );
		error_log( $release_date );
		error_log( $release_date_format );
		error_log( print_r( $date_input, true ) );
		error_log( 'Terms' );
		error_log( print_r( $terms, true ) );
		*/

		if ( $movie_year !== $date_input['year'] ) {
			if ( -1 === $movie_year ) {
				echo '<div>';
			} else {
				echo '</div></div><div>';
			}

			$movie_year = $date_input['year'];
			echo '<h1>Movies from ' . esc_attr( $movie_year ) . '</h1>';
			$movie_month = -1;
		};

		if ( $movie_month !== $date_input['mon'] ) {
			if ( -1 === $movie_month ) {
				echo '<div>';
			} else {
				echo '</div><div>';
			}

			$movie_month = $date_input['mon'];

			// Create date object to store the DateTime format.
			$month_obj = DateTime::createFromFormat( '!m', $movie_month );

			// Store the month name to variable.
			$movie_month_name = $month_obj->format( 'F' );
			echo '<h2>' . esc_attr( $movie_month_name ) . '</h2>';
		}

		?>

		<div>
			<h2><?php the_title(); ?></h2>
			<h4><?php echo esc_attr( $release_date_format ); ?></h4>
			<ul>
			<?php
			foreach ( $movie_terms as $movie_term ) {
				echo '<li>' . esc_attr( $movie_term->name ) . '</li>';
			}
			?>
			</ul>
		</div>

	<?php endwhile; ?>

	</div>

<?php endif; ?>

<?php
	wp_reset_postdata();
	$actors_query = get_popular_actors();

	//error_log( 'Popular actors' );
	//error_log( print_r( $actor_query, true ) );

if ( $actors_query->have_posts() ) :
	echo '<div>';
	while ( $actors_query->have_posts() ) :
		$actors_query->the_post();
		$actor_name = get_field( 'actor_name' );
		?>

		<div>
			<h2><?php echo esc_attr( $actor_name ); ?></h2>
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
