<?php

class Jobsity {
	const TRANSIENT_EXPIRATION = 60 * 60 * 168; // 7 days until the transient gets updated.
	const API_URL              = 'https://api.themoviedb.org/3/';
	const API_KEY              = 'd6aee8db7cd6a522157abaf6c8fa7491';
	const API_URL_SINGLE_MOVIE = self::API_URL . '/movie/';
	const API_URL_SINGLE_ACTOR = self::API_URL . '/person/';


	/**
	 * Fired when the plugin is activated by the activation hook
	 */
	public static function plugin_activation() {
		self::create_custom_post_type();
		self::create_custom_taxonomy();

		// Flush permalinks.
		flush_rewrite_rules();
	}


	/**
	 * Fired when the plugin is activated by the deactivation hook
	 */
	public static function plugin_deactivation() {

		flush_rewrite_rules();
	}


	/**
	 * Fired by the init hook of the plugin.
	 */
	public static function init() {
		self::create_custom_post_type();
		self::create_custom_taxonomy();
		register_taxonomy_for_object_type( 'genre', 'movie' );
		add_filter( 'manage_movie_posts_columns', array( 'Jobsity', 'movie_admin_columns' ) );
		add_action( 'manage_movie_posts_custom_column', array( 'Jobsity', 'movie_admin_columns_content' ), 10, 2 );
		add_filter( 'manage_edit-movie_sortable_columns', array( 'Jobsity', 'movie_admin_sortable_columns' ) );
		add_filter( 'manage_actor_posts_columns', array( 'Jobsity', 'actor_admin_columns' ) );
		add_action( 'manage_actor_posts_custom_column', array( 'Jobsity', 'actor_admin_columns_content' ), 10, 2 );
		add_filter( 'manage_edit-actor_sortable_columns', array( 'Jobsity', 'actor_admin_sortable_columns' ) );
		add_action( 'pre_get_posts', array( 'Jobsity', 'admin_custom_orders' ) );
		add_filter( 'acf/update_value/name=actor_movies', array( 'Jobsity', 'bidirectional_acf_update_value' ), 10, 3 );
	}


	/**
	 * Adds new  admin columns to the 'movie' custom post type.
	 */
	public static function movie_admin_columns( $columns ) {

		$columns['release_date'] = 'Release Date';
		$columns['release_year'] = 'Release Year';
		return $columns;
	}


	/**
	 * Adds content to the admin columns created for the 'movie' CPT.
	 */
	public static function movie_admin_columns_content( $column, $post_id ) {
		switch ( $column ) {
			case 'release_year':
				$year = get_field( 'release_year', $post_id );
				echo esc_attr( $year );
				break;
			case 'release_date':
				$year = get_field( 'release_date', $post_id );
				echo esc_attr( $year );
				break;
		}
	}


	/**
	 * Make sortable the new columns created for the 'movie' CPT
	 */
	public static function movie_admin_sortable_columns( $columns ) {
		$columns['release_date'] = 'release_date';
		$columns['release_year'] = 'release_year';
		return $columns;
	}


	/**
	 * Adds new  admin columns to the 'actor' custom post type.
	 */
	public static function actor_admin_columns( $columns ) {
		$columns['actor_name']   = 'Name';
		$columns['actor_movies'] = 'Movies';
		return $columns;
	}


	/**
	 * Adds content to the admin columns created for the 'actor' CPT.
	 */
	public static function actor_admin_columns_content( $column, $post_id ) {
		switch ( $column ) {
			case 'actor_name':
				$name = get_field( 'actor_name', $post_id );
				echo esc_attr( $name );
				break;
			case 'actor_movies':
				$movies = get_field( 'actor_movies' );
				if ( $movies ) {
					$html = '';
					foreach ( $movies as $movie ) {
						$html .= '<a href="' . $movie->guid . '">' . $movie->post_title . '</a><br>';
					}
					echo $html;
				}
				break;
		}
	}


	/**
	 * Make sortable the new columns created for the 'actor' CPT
	 */
	public static function actor_admin_sortable_columns( $columns ) {
		$columns['actor_name']   = 'actor_name';
		$columns['actor_movies'] = 'actor_movies';
		return $columns;
	}


	/**
	 * Create the custom orders for the aplication
	 */
	public static function admin_custom_orders( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		switch ( $query->get( 'orderby' ) ) {
			case 'release_date':
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'meta_key', 'release_date' );
				$query->set( 'meta_type', 'date' );
				break;
			case 'release_year':
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'meta_key', 'release_year' );
				$query->set( 'meta_type', 'text' );
				break;
			case 'actor_name':
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'meta_key', 'actor_name' );
				$query->set( 'meta_type', 'text' );
				break;
		}
	}


	/**
	 * Creates the custom post types
	 */
	public static function create_custom_post_type() {
		$labels = array(
			'name'               => 'Movies', // General name for the post type.
			'menu_name'          => 'Movies',
			'singular_name'      => 'Movie',
			'all_items'          => 'All Movies',
			'search_items'       => 'Search Movies',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Movie',
			'new_item'           => 'New Movie',
			'view_item'          => 'View Movie',
			'edit_item'          => 'Edit Movie',
			'not_found'          => 'No Movies Found.',
			'not_found_in_trash' => 'Movie not found in Trash.',
			'parent_item_colon'  => 'Parent Movie',
		);

		$args = array(
			'labels'              => $labels,
			'description'         => 'Movie',
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-media-video',
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'has_archive'         => true,
			'exclude_from_search' => false,
			'rewrite'             => array( 'slug' => 'movies' ),
			'hierarchical'        => false,
			'taxonomies'          => array( 'genre' ),
			'supports'            => array( 'title', 'thumbnail', 'editor' ),
		);

		register_post_type( 'movie', $args );

		$labels = array(
			'name'               => 'Actors', // General name for the post type.
			'menu_name'          => 'Actors',
			'singular_name'      => 'Actor',
			'all_items'          => 'All Actors',
			'search_items'       => 'Search Actors',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Actor',
			'new_item'           => 'New Actor',
			'view_item'          => 'View Actor',
			'edit_item'          => 'Edit Actor',
			'not_found'          => 'No Actor Found.',
			'not_found_in_trash' => 'Actor not found in Trash.',
			'parent_item_colon'  => 'Parent Actor',
		);

		$args = array(
			'labels'              => $labels,
			'description'         => 'Actor',
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-users',
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'has_archive'         => true,
			'exclude_from_search' => false,
			'rewrite'             => array( 'slug' => 'actors' ),
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'editor' ),
		);

		register_post_type( 'actor', $args );
	}


	/**
	 * Creates the custom taxonomies
	 */
	public static function create_custom_taxonomy() {
		$labels = array(
			'name'              => 'Genres',
			'singular_name'     => 'Genre',
			'search_items'      => 'Search Genre',
			'all_items'         => 'All Genres',
			'parent_item'       => 'Parent Genre',
			'parent_item_colon' => 'Parent Genre:',
			'edit_item'         => 'Edit Genre',
			'update_item'       => 'Update Genre',
			'add_new_item'      => 'Add New Genre',
			'new_item_name'     => 'New Type Genre',
			'menu_name'         => 'Genres',
		);

		register_taxonomy(
			'genre',
			'movie',
			array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'exclude_from_search' => false,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'type' ),
			)
		);
	}


	/**
	 * Creates a bidirectional relationship between actors and movies.
	 */
	function bidirectional_acf_update_value( $value, $post_id, $field  ) {

		// vars
		$field_name = $field['name'];
		$field_key = $field['key'];
		$global_name = 'is_updating_' . $field_name;

		// bail early if this filter was triggered from the update_field() function called within the loop below
		// - this prevents an inifinte loop
		if( !empty($GLOBALS[ $global_name ]) ) return $value;

		// set global variable to avoid inifite loop
		// - could also remove_filter() then add_filter() again, but this is simpler
		$GLOBALS[ $global_name ] = 1;	

		// loop over selected posts and add this $post_id
		if( is_array($value) ) {

			foreach( $value as $post_id2 ) {

				// load existing related posts
				$value2 = get_field($field_name, $post_id2, false);

				// allow for selected posts to not contain a value
				if( empty($value2) ) {

					$value2 = array();

				}

				// bail early if the current $post_id is already found in selected post's $value2
				if( in_array($post_id, $value2) ) continue;

				// append the current $post_id to the selected post's 'related_posts' value
				$value2[] = $post_id;

				// update the selected post's value (use field's key for performance)
				update_field($field_key, $value2, $post_id2);

			}

		}

		// find posts which have been removed
		$old_value = get_field($field_name, $post_id, false);

		if( is_array($old_value) ) {

			foreach( $old_value as $post_id2 ) {

				// bail early if this value has not been removed
				if( is_array($value) && in_array($post_id2, $value) ) continue;

				// load existing related posts
				$value2 = get_field($field_name, $post_id2, false);

				// bail early if no value
				if( empty($value2) ) continue;

				// find the position of $post_id within $value2 so we can remove it
				$pos = array_search($post_id, $value2);

				// remove
				unset( $value2[ $pos] );

				// update the un-selected post's value (use field's key for performance)
				update_field($field_key, $value2, $post_id2);

			}

		}

		// reset global varibale to allow this filter to function as per normal
		$GLOBALS[ $global_name ] = 0;

		// return
		return $value;

	}


	/**
	 * Handle the API calls
	 */
	public static function api_call( $url ) {
		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
			),
		);

		$response = wp_remote_get( $url, $args );
		$data     = wp_remote_retrieve_body( $response );

		if ( empty( $data ) ) return false;

		$api_data = json_decode( $data );
		return $api_data;
	}


	/**
	 * Retrieves extra data for a single movie
	 */
	public static function get_movie_extra_data( $api_movie_id ) {
		$extra_data = array(
			'overview'             => '',
			'production_companies' => array(),
			'original_language'    => '',
			'popularity'           => '',
			'trailer'              => '',
			'alternatives_titles'  => array(),
			'reviews'              => array(),
			'similar_movies'       => array(),
		);

		$url            = self::API_URL_SINGLE_MOVIE . "$api_movie_id?api_key=" . self::API_KEY . '&language=en-US&append_to_response=videos,images';
		$api_movie_data = self::api_call( $url );

		if ( $api_movie_data ) {
			$extra_data['overview']             = $api_movie_data->overview;
			$extra_data['production_companies'] = $api_movie_data->production_companies;
			$extra_data['original_language']    = $api_movie_data->original_language;
			$extra_data['popularity']           = $api_movie_data->popularity;
			$extra_data['trailer']              = self::get_movie_trailer( $api_movie_data->videos );
			$extra_data['alternatives_titles']  = self::get_movie_alternative_titles( $api_movie_id );
			$extra_data['reviews']              = self::get_movie_reviews( $api_movie_id );
			$extra_data['similar_movies']       = self::get_similar_movies( $api_movie_id );

		}

		return $extra_data;
	}


	/**
	 * Retrieves the trailer of a movie
	 */
	public static function get_movie_trailer( $movie_videos ) {
		if ( $movie_videos->results ) {
			$movie_videos_data = $movie_videos->results;
			foreach ( $movie_videos_data as $movie_video ) {
				if ( 'Trailer' === $movie_video->type ) {
					return 'https://www.youtube.com/embed/' . $movie_video->key;
				}
			}
		}

		return '';
	}


	/**
	 * Retrieves the movie alternative titles.
	 */
	public static function get_movie_alternative_titles( $api_movie_id ) {
		$url    = self::API_URL_SINGLE_MOVIE . "$api_movie_id/alternative_titles?api_key=" . self::API_KEY;
		$result = self::api_call( $url );
		return $result->titles;
	}

	/**
	 * Retrieves the movie reviews.
	 */
	public static function get_movie_reviews( $api_movie_id ) {
		$url          = self::API_URL_SINGLE_MOVIE . "76341/reviews?api_key=" . self::API_KEY . '&page=1';
		$reviews_data = self::api_call( $url );

		if ( $reviews_data->total_results > 0 ) {
			return $reviews_data->results;
		}
		return array();
	}

	/**
	 * Retrieves the similar movies.
	 */
	public static function get_similar_movies( $api_movie_id ) {
		$url                 = self::API_URL_SINGLE_MOVIE . "$api_movie_id/similar?api_key=" . self::API_KEY . '&page=1';
		$similar_movies_data = self::api_call( $url );
		return $similar_movies_data->results;
	}

	/**
	 * Retrieves extra data for a single actor
	 */
	public static function get_actor_extra_data( $api_actor_id ) {
		$extra_data = array(
			'birthday'       => '',
			'place_of_birth' => '',
			'day_of_death'   => '',
			'website'        => '',
			'bio'            => '',
			'gallery'        => array(),
		);

		$url            = self::API_URL_SINGLE_ACTOR . "$api_actor_id?api_key=" . self::API_KEY . '&append_to_response=images';
		$api_actor_data = self::api_call( $url );

		if ( $api_actor_data ) {
			$extra_data['birthday']       = $api_actor_data->birthday;
			$extra_data['place_of_birth'] = $api_actor_data->place_of_birth;
			$extra_data['day_of_death']   = ( null !== $api_actor_data->deathday ) ? $api_actor_data->deathday : '';
			$extra_data['website']        = ( null !== $api_actor_data->homepage ) ? $api_actor_data->homepage : '';
			$extra_data['bio']            = $api_actor_data->biography;
			$extra_data['gallery']        = ( $api_actor_data->images->profiles ) ? $api_actor_data->images->profiles : array();
		}

		return $extra_data;
	}

}
