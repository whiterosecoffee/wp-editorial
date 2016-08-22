<?php

if( class_exists( 'MPMood' ) ) 
	return;

require_once( dirname(__FILE__) . '/MPPlugin.php' );

class MPMood extends MPPlugin {

	const MP_MOOD_VERSION = 0.1;

	public function __construct() {
		parent::__construct( 'MPMood', self::MP_MOOD_VERSION );
		$this->register_taxonomy();
	}

	public function register_taxonomy() {
		register_taxonomy(
			'mood',
			'post',
			array(
				'labels'        => array(
					'name'              => __( 'Moods', 'menapost-custom' ),
					'singular_name'     => __( 'Mood', 'menapost-custom' ),
					'search_items'      => __( 'Search Moods', 'menapost-custom' ),
					'all_items'         => __( 'All Moods', 'menapost-custom' ),
					'parent_item'       => __( 'Parent Mood', 'menapost-custom' ),
					'parent_item_colon' => __( 'Parent Mood:', 'menapost-custom' ),
					'edit_item'         => __( 'Edit Mood', 'menapost-custom' ),
					'update_item'       => __( 'Update Mood', 'menapost-custom' ),
					'add_new_item'      => __( 'Add New Mood', 'menapost-custom' ),
					'new_item_name'     => __( 'New Mood Name', 'menapost-custom' ),
					'menu_name'         => __( 'Moods', 'menapost-custom' ),
				),
				'rewrite'      => array( 'slug' => 'mood' ),
				'hierarchical' => true,
				'capabilities' => array(
					'manage_terms' => 'manage_categories',
					'edit_terms'   => 'manage_categories',
					'delete_terms' => 'manage_categories',
					'assign_terms' => 'edit_posts'
				)
			)
		);
	}
}