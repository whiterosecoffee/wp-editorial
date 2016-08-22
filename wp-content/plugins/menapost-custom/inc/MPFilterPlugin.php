<?php 

// Ajax actions
add_action( 'wp_ajax_increment_views', array( 'MPFilterPlugin', 'ajax_increment_views' ) );
add_action( 'wp_ajax_nopriv_increment_views', array( 'MPFilterPlugin', 'ajax_increment_views' ) );

add_action( 'wp_ajax_get_views', array( 'MPFilterPlugin', 'ajax_get_views' ) );
add_action( 'wp_ajax_nopriv_get_views', array( 'MPFilterPlugin', 'ajax_get_views' ) );

if( class_exists( 'MPFilterPlugin' ) ) 
	return;

require_once( dirname(__FILE__) . '/MPPlugin.php' );

class MPFilterPlugin extends MPPlugin {

	const MP_FILTER_VERSION = 0.1;

	function __construct() {
		parent::__construct( 'MPFilterPlugin', self::MP_FILTER_VERSION );

		// add_action( 'the_content', array( $this, 'the_content' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	public static function ajax_increment_views() {

		check_ajax_referer( 'increment_views' );

		// Extract post id from POST body
		$post_id = isset( $_POST[ 'post_id' ] ) ? intval( $_POST['post_id'] ) : 0;

		// If post_id is not present or 0 then return error.
		if( $post_id === 0 )
			wp_send_json_error( 'Incorrect post id' );

		// Increment the views
		$views = self::increment_views( $post_id );

		// Send as json
		wp_send_json_success( $views );
	} 

	public static function ajax_get_views() {
		check_ajax_referer( 'get_views' );

		$post_ids = isset( $_POST[ 'post_ids' ] ) ? $_POST[ 'post_ids' ] : array();
		$result = array();

		foreach ($post_ids as $post_id) {
			$post_id = intval( $post_id );

			$views = intval( get_post_meta( $post_id, 'views', true ) );

			$result[] = array( 'post_id' => $post_id, 'views' => apply_filters( 'make_k_count', $views ) );
		}

		wp_send_json_success( $result );
	}

	static function increment_views( $post_id ) {
		$views = get_post_meta( $post_id, 'views', true );
		$views = $views === "" ? 1 : $views + 1;
		update_post_meta( $post_id, 'views', $views );

		return apply_filters( 'make_k_count', $views );
	}

	// function the_content( $content ) {
	// 	global $post;
	// 	if( is_single() ) {
	// 		self::increment_views( $post->ID );
	// 	}
	// 	return $content;
	// }

	function save_post( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		add_post_meta( $post_id, 'views', 0, true );
	}

}