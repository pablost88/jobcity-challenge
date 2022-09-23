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
		self::cpt_create();

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
		self::cpt_create();
	}


	/**
	 * Creates the custom post types
	 */
	public static function cpt_create() {
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
