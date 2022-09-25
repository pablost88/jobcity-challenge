<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package moviesandactors
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */

?>
<form class="margin-top-30" role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<label class="screen-reader-text" for="s">Find: </label>
		<input type="search" id="s" value="<?php echo get_search_query(); ?>" name="s" placeholder="Search for movie or actor" />
		<input type="submit" id="searchsubmit" value="Find" />
	</div>
</form>
