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

	$movie_id = get_the_ID();
	totalView( $movie_id );

	custom_search_order_formula( $movie_id );

	$movie_title  = get_the_title();
	$movie_poster = get_the_post_thumbnail_url();
	$movie_terms  = get_the_terms( $post->ID, 'genre' );
	$release_date = get_field( 'release_date' );
	$actors       = get_field( 'actor_movies' );
	$api_movie_id = get_field( 'themoviedb_id' );
	$extra_data   = Jobsity::get_movie_extra_data( $api_movie_id );
	?>

	<div class="max-width-2 center single-movie-block margin-top-50">
		<h1 class="text-center h1-big"><?php echo esc_attr( $movie_title ); ?></h1>
		<div class="align-self-center margin-top-15">
			<img src="<?php echo esc_attr( $movie_poster ); ?>" />
		</div>

		<div class="margin-top-30">
			<div>
				<h1>Release Date: <span><?php echo esc_attr( $release_date ); ?></span></h1>
				<h1 class="margin-top-15">Original Language: <span><?php echo esc_attr( $extra_data['original_language'] ); ?></span></h1>
				<h1 class="margin-top-15">Popularity: <span><?php echo esc_attr( $extra_data['popularity'] ); ?></span></h1>
			</div>

			<div class="margin-top-15">
				<h1>Overview</h1>
				<p>
				<?php echo esc_attr( $extra_data['overview'] ); ?>
				</p>
			</div>

			<div class="margin-top-15">
				<h1>Cast</h1>
				<ul class="clean-list">
				<?php
				foreach ( $actors as $actor ) {
					$actor_slug = get_permalink( $actor->ID );
					echo '<li><a href="' . esc_attr( $actor_slug ) . '">' . esc_attr( $actor->post_title ) . '</a></li>';
				}
				?>
				</ul>
			</div>

			<div class="margin-top-15">
				<h1>Trailer</h1>
				<div class="video-container margin-top-10">
				<iframe src="<?php echo esc_attr( $extra_data['trailer'] ); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen">
				</iframe>
				</div>
			</div>

			<div class="margin-top-15">
				<h1>Genres</h1>
				<ul class="clean-list">
				<?php
				foreach ( $movie_terms as $movie_term ) {
					echo '<li>' . esc_attr( $movie_term->name ) . '</li>';
				}
				?>
				</ul>
			</div>

			<div class="margin-top-15">
				<h1>Production Companies</h1>
				<?php
				$companies      = $extra_data['production_companies'];
				$companies_text = '';
				foreach ( $companies as $company ) {
					$companies_text .= "$company->name, ";
				}
				?>
				<p><?php echo esc_attr( rtrim( $companies_text, ', ' ) ); ?></p>
			</div>

			<div class="margin-top-15">
				<h1>Alternative Titles</h1>
				<?php
				$titles      = $extra_data['alternatives_titles'];
				$titles_text = '';
				foreach ( $titles as $title ) {
					$titles_text .= "$title->title, ";
				}
				?>
				<p><?php echo esc_attr( rtrim( $titles_text, ', ' ) ); ?></p>
			</div>

			<div class="margin-top-15">
				<h1>Reviews</h1>
				<?php
				$reviews = $extra_data['reviews'];
				foreach ( $reviews as $key => $review ) :
					if ( 10 === $key ) { // Allow only 10 reviews.
						break;
					}
					$review_author  = $review->author;
					$review_content = $review->content;
					?>
				<div class="margin-top-10">
					<h4>Author: <span><?php echo esc_attr( $review_author ); ?></span></h4>
					<p><?php echo esc_attr( $review_content ); ?></p>
				</div>
					<?php
				endforeach;
				?>
			</div>

			<div class="margin-top-15">
				<h1>Similar Movies</h1>
				<?php
				$similar_movies      = $extra_data['similar_movies'];
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
