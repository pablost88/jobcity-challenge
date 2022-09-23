<?php

class Jobsity {
	const TRANSIENT_EXPIRATION = 3600;
	const API_URL              = 'https://api.themoviedb.org/3/';
	const API_KEY              = 'd6aee8db7cd6a522157abaf6c8fa7491';

	/**
	 * Fired when the plugin is activated by the activation hook
	 */
	public static function plugin_activation() {
		error_log( 'Plugin activated' );
		self::create_custom_post_type();
		self::create_custom_taxonomy();

		// Flush permalinks.
		flush_rewrite_rules();
	}


	/**
	 * Fired when the plugin is activated by the deactivation hook
	 */
	public static function plugin_deactivation() {
		error_log( 'Plugin deactivated' );

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
	}


	/**
	 * Adds new  admin columns to the 'movie' custom post type.
	 */
	public static function movie_admin_columns( $columns ) {
		//error_log( 'Columns are:' );
		//error_log( print_r( $columns, true ) );

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
		}
	}


	/**
	 * Make sortable the new columns created for the 'movie' CPT
	 */
	public static function movie_admin_sortable_columns( $columns ) {
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
			'labels'             => $labels,
			'description'        => 'Movie',
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-media-video',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_admin_bar'  => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'taxonomies'         => array( 'genre' ),
			'supports'           => array( 'title', 'thumbnail', 'editor' ),
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
			'labels'             => $labels,
			'description'        => 'Actor',
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-admin-users',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_admin_bar'  => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'thumbnail', 'editor' ),
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
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'type' ),
			)
		);
	}


	/**
	 * Handle the API calls
	 */
	public static function api_call() {
		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
			),
		);

		$response = wp_remote_get( self::API_URL . 'movie/550?api_key=' . self::API_KEY, $args );
		$data     = wp_remote_retrieve_body( $response );

		if ( empty( $data ) ) return false;

		$api_data = json_decode( $data );
		error_log( print_r( $api_data, true ) );

	}

}
