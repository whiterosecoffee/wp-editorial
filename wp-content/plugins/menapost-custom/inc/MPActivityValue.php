<?php 

require_once( dirname(__FILE__) . '/MPPlugin.php' );
require_once( dirname(__FILE__) . '/MCActivity.php' );

class MPActivityValue extends MPPLugin {
	
	const MP_ACTIVITY_VALUE_VERSION = 0.2;
	const ACTIVITY_VALUE_TABLE_NAME = "activity_value";

	public function __construct() {
		parent::__construct( 'MPActivityValue', self::MP_ACTIVITY_VALUE_VERSION );

		add_filter( 'mp_get_activity_value', array( &$this, 'get_activity_value' ) );
	}

	/**
	 * 	Create database table for activity value.
	 */
	protected function install() {
		global $wpdb;

		$wpdb->show_errors();
		$table = $wpdb->prefix . self::ACTIVITY_VALUE_TABLE_NAME;
		$sql = "";
		$charset_collate = "";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		if ( ! empty($wpdb->charset) ) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) ) $charset_collate .= " COLLATE $wpdb->collate";

		// does the table exists?
		if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table ) { // fresh setup
			$sql = "CREATE TABLE " . $table ." ( post_id int(10) NOT NULL UNIQUE, 
						comment int(5) NOT NULL default 0, 
						facebook_like int(5) NOT NULL default 0, facebook_share int(5) NOT NULL default 0, 
						linkedin int(5) NOT NULL default 0, 
						pininterest int(5) NOT NULL default 0, googleplus int(5) NOT NULL default 0, 
						email int(5) NOT NULL default 0, twitter int(5) NOT NULL default 0, 
						bookmark int(5) NOT NULL default 0, 
						inlinecomment int(5) NOT NULL default 0, other int(5) NOT NULL default 0,
						total int(10) NOT NULL default 0 ) $charset_collate;";
			dbDelta($sql);
		} 
		parent::install();
	}

	static function get_activity_value( $post_id ) {
		global $wpdb;

		$activity_value = new MCActivity( $post_id );

		$row = $wpdb->get_row( "SELECT * FROM wp_activity_value WHERE post_id = " . $post_id );
		
		if( $row != "" ) {
			foreach($row as $key => $value) {
				$activity_value->{$key} = $value;
	        }
        }

		return $activity_value;
	}

	static function parse_activity_value( $array ) {
		$activity_value = new MCActivity();
		$activity_value->init( $array );
		return $activity_value;
	}

	static function __update_activity_value( $activity_value ) {
		global $wpdb;
	
		$wpdb->query( $wpdb->prepare( "INSERT INTO wp_activity_value 
			(post_id, comment, facebook_like, facebook_share, linkedin, pininterest, googleplus, email, twitter, bookmark, other, inlinecomment, total) 
			VALUES (%d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d) ON DUPLICATE KEY UPDATE 
			comment = %d, facebook_like = %d, facebook_share = %d, linkedin = %d, pininterest = %d, googleplus = %d, 
			email = %d, twitter = %d, bookmark = %d, other = %d, inlinecomment = %d, total = %d;",
			$activity_value->post_id, $activity_value->comment, $activity_value->facebook_like, $activity_value->facebook_share, 
			$activity_value->linkedin, $activity_value->pininterest, $activity_value->googleplus, $activity_value->email, 
			$activity_value->twitter, $activity_value->bookmark, $activity_value->other, $activity_value->inlinecomment, $activity_value->get_total_count(),

			$activity_value->comment, $activity_value->facebook_like, $activity_value->facebook_share, $activity_value->linkedin, 
			$activity_value->pininterest, $activity_value->googleplus, $activity_value->email, $activity_value->twitter,
			$activity_value->bookmark, $activity_value->other, $activity_value->inlinecomment, $activity_value->get_total_count() ) );

	}

	static function get_google_plus_count() {
		check_ajax_referer( 'google_plus_count' . get_current_user_id(), 'mp_nonce' );
		include( 'GooglePlus.php' );
		die(); // this is required to return a proper result
	}

	static function validate_count( $type, $new, $old ) {
		return $new;
	}

	static function update_activity_value() {
		check_ajax_referer( 'update_activity_value' . get_current_user_id(), 'mp_nonce' );

		$type = isset($_GET['type']) ? $_GET['type'] : NULL;
		$new_count = isset($_GET['count']) ? $_GET['count'] : NULL;
		$post_id = isset($_GET['postId']) ? $_GET['postId'] : NULL;

		if( is_null($type) || is_null($new_count) || is_null($post_id) ) {
			wp_send_json("Error");
		}

		$activity_value = self::get_activity_value( $post_id );
		$new_count = intval( $new_count );
		switch ($type) {
			case 'comment':
				$new_count = $activity_value->comment = self::validate_count( $type, $new_count, $activity_value->comment );
				break;
			case 'facebook_like':
				$new_count = $activity_value->facebook_like = self::validate_count( $type, $new_count, $activity_value->facebook_like );
				break;
			case 'facebook_share':
				$new_count = $activity_value->facebook_share = self::validate_count( $type, $new_count, $activity_value->facebook_share );
				break;
			case 'twitter':
				$new_count = $activity_value->twitter = self::validate_count( $type, $new_count, $activity_value->twitter );
				break;
			case 'email':
				$new_count = $activity_value->email = $new_count;
				break;
			case 'linkedin':
				$new_count = $activity_value->linkedin = $new_count;
				break;
			case 'pininterest':
				$new_count = $activity_value->pininterest = $new_count;
				break;
			case 'googleplus':
				$new_count = $activity_value->googleplus = self::validate_count( $type, $new_count, $activity_value->googleplus );
				break;
			case 'bookmark':
				$new_count = $activity_value->bookmark = self::validate_count( $type, $new_count, $activity_value->bookmark );
				break;
			case 'inlinecomment':
				$new_count = $activity_value->inlinecomment = $new_count;
				break;
			case 'other':
				$new_count = $activity_value->other = $new_count;
				break;
			default:
				break;
		}
		self::__update_activity_value( $activity_value );
		$response = array( 'total' => $activity_value->get_total_count(), 'new_count' => $new_count );
		
		if( class_exists( 'Utilities' ) ) {
			$response[ 'total_str' ] = Utilities::make_k_count( $activity_value->get_total_count() );
			$response[ 'new_count_str' ] = Utilities::make_k_count( $new_count );
		} else {
			$response[ 'total_str' ] = $activity_value->get_total_count();
			$response[ 'new_count_str' ] = $new_count;
		}
		
		wp_send_json( $response );
	}
}

add_action( 'wp_ajax_google_plus_count', array( 'MPActivityValue', 'get_google_plus_count' ) );
add_action( 'wp_ajax_nopriv_google_plus_count', array( 'MPActivityValue','get_google_plus_count' ) );

add_action( 'wp_ajax_update_activity_value', array( 'MPActivityValue', 'update_activity_value' ) );
add_action( 'wp_ajax_nopriv_update_activity_value', array( 'MPActivityValue','update_activity_value' ) );

//add_filter( 'mp_get_activity_value', array( 'MPActivityValue' => 'get_activity_value' ) );
add_filter( 'parse_activity_value', array( 'MPActivityValue', 'parse_activity_value' ) );