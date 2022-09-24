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
	$actors       = get_field( 'actor_movies' );
	$api_movie_id = get_field( 'themoviedb_id' );
	$extra_data   = Jobsity::get_movie_extra_data( $api_movie_id );
	?>

	<div>
		<figure>

		</figure>

		<div>
			<h1><?php esc_attr( $movie_title ); ?></h1>
			<div>
				<h2>Release Date: <?php esc_attr( $release_date ); ?></h2>
				<h2>Original Language: <?php esc_attr( $extra_data['original_language'] ); ?></h2>
				<h2>Popularity: <?php esc_attr( $extra_data['popularity'] ); ?></h2>
			</div>
			<p>
			<?php esc_attr( $extra_data['overview'] ); ?>
			</p>

			<div>
				<h2>Cast</h2>
				<ul>
				<?php
				foreach ( $actors as $actor ) {
					echo '<li><a href="' . esc_attr( $actor->guid ) . '">' . esc_attr( $actor->post_title ) . '</a></li>';
				}
				?>
				</ul>
			</div>

			<div>
				<h2>Trailer</h2>
				<video>

				</video>
			</div>

			<div>
				<h2>Genres</h2>
				<ul>
				<?php
				foreach ( $movie_terms as $movie_term ) {
					echo '<li>' . esc_attr( $movie_term->name ) . '</li>';
				}
				?>
				</ul>
			</div>

			<div>
				<h2>Production Companies</h2>
				<?php
				$companies      = $extra_data['production_companies'];
				$companies_text = '';
				foreach ( $companies as $company ) {
					$companies_text .= "$company->name, ";
				}
				?>
				<p><?php echo esc_attr( rtrim( $companies_text, ', ' ) ); ?></p>
			</div>

			<div>
				<h2>Alternative Titles</h2>
				<?php
				$titles      = $extra_data['alternatives_titles'];
				$titles_text = '';
				foreach ( $titles as $title ) {
					$titles_text .= "$title->title, ";
				}
				?>
				<p><?php echo esc_attr( rtrim( $titles_text, ', ' ) ); ?></p>
			</div>

			<div>
				<h2>Reviews</h2>
				<?php
				$reviews = $extra_data['reviews'];
				error_log( 'Reviews' );
				error_log( print_r( $reviews, true ) );
				foreach ( $reviews as $key => $review ) :
					if ( 10 === $key ) { // Allow only 10 reviews.
						break;
					}
					$review_author  = $review->author;
					$review_content = $review->content;
					?>
				<div>
					<h4><?php echo esc_attr( $review_author ); ?></h4>
					<p><?php echo esc_attr( $review_content ); ?></p>
				<div>
					<?php
				endforeach;
				?>
			</div>

			<div>
				<h2>Similar Movies</h2>
				<?php
				$similar_movies = $extra_data['similar_movies'];
				$similar_movies_text = '';
				foreach ( $similar_movies as $similar_movie ) {
					$similar_movies_text .= "$similar_movie->title, ";
				}
				?>
				<p><?php echo esc_attr( rtrim( $similar_movies_text, ', ' ) ); ?></p>
			</div>
		</div>
	</div>

	<?php
endwhile;

get_footer();
?>
