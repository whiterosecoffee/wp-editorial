<?php

/**
 * Plugin Name: Reading list
 * Plugin URI: http://www.menapo.com
 * Description: Allows the user to add article to the reading list.
 * Version: 0.1
 * Author: Ahmed
 * Author URI: http://www.menapo.com
 * License: Private
 */

if (basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__)) exit('Please do not load this page directly');

require_once( dirname(__FILE__) . '/MPPlugin.php' );

class MPReadingList extends MPPlugin {
	
	const READING_LIST_TABLE_NAME = 'readinglist';
	const MP_READING_LIST_VERSION = 0.1;

	function __construct() {
		parent::__construct( 'MPReadingList', self::MP_READING_LIST_VERSION );
		add_shortcode( 'reading-list', array( &$this, 'reading_list_shortcode' ) );
	}

	/**
	 * 	Create database table for reading list.
	 */
	protected function install() {
		global $wpdb;

		$wpdb->show_errors();
		$table = $wpdb->prefix . self::READING_LIST_TABLE_NAME;
		$sql = "";
		$charset_collate = "";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		if ( ! empty($wpdb->charset) ) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) ) $charset_collate .= " COLLATE $wpdb->collate";

		// does the table exists?
		if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table ) { // fresh setup
			$sql = "CREATE TABLE " . $table ." ( UNIQUE KEY compositeID (post_id, user_id), post_id int(10) NOT NULL, user_id int(10) NOT NULL, date_added datetime NOT NULL default '0000-00-00 00:00:00', status int(2) NOT NULL default 1 ) $charset_collate;";
			dbDelta($sql);
		} 
		parent::install();
	}

	static function add_to_list() {
		global $wpdb;
		$post_id = intval( $_POST['post_id'] );

		check_ajax_referer( 'add_to_reading_list' . get_current_user_id() );

		$wpdb->show_errors();
		$table = $wpdb->prefix . self::READING_LIST_TABLE_NAME;

		$now = current_time( 'mysql' );
		$user_id = get_current_user_id();
		$status = 1;
		
		$result = $wpdb->query( $wpdb->prepare( "INSERT INTO {$table} (post_id, user_id, date_added, status) VALUES (%d, %d, '{$now}', '{$status}') ON DUPLICATE KEY UPDATE date_added = '{$now}', status = 1;", $post_id, $user_id ) );
		if ( $result == FALSE ) 
			echo "Error";
		else
			echo "Success";
		die();
	}

	static function remove_from_list() {
		global $wpdb;

		$post_id = intval( $_POST['post_id'] );

		check_ajax_referer( 'remove_from_reading_list' . get_current_user_id() );

		$wpdb->show_errors();
		$table = $wpdb->prefix . self::READING_LIST_TABLE_NAME;

		$now = current_time( 'mysql' );
		$user_id = get_current_user_id();
		$status = 1;
		
		$result = $wpdb->query( $wpdb->prepare( "UPDATE {$table} SET status = 0 WHERE post_id = %d AND user_id = %d;", $post_id, $user_id ) );
		if ( $result == FALSE ) 
			echo "Error";
		else
			echo "Success";
		die();
	}

	static function reading_list_count() {

		check_ajax_referer( 'get_bookmark_count' . get_current_user_id(), 'mp_nonce' );

		global $wpdb;

		$post_id = intval( $_GET['post_id'] );

		$table = $wpdb->prefix . self::READING_LIST_TABLE_NAME;
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE post_id = %d AND status = 1;", $post_id ) );

		wp_send_json( $result );
	}

	static function exists_in_list ( $post_id ){

		global $wpdb;

		$table = $wpdb->prefix . self::READING_LIST_TABLE_NAME;

		$user_id = get_current_user_id();
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT status FROM {$table} WHERE user_id = %d and post_id = %d", $user_id, $post_id ) ); 

		if( $result == 1 )
			return true;
		return false;
	}

	function reading_list_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'remove' => false,
		), $atts ) );
		$post_id = intval($content);
		
		if( $remove ) {
			echo '<span class="reading-list"><a href="#" data-command="remove" data-action="reading-list" data-post-id="' . $content .'">- Reading List</a></span>';
			return;
		}
		
		if( self::exists_in_list( $post_id ) ) 
			echo 'data-complete="true"';
	}

}

add_filter( 'exists_in_reading_list', array( 'MPReadingList', 'exists_in_list' ) );

add_action( 'wp_ajax_add_to_reading_list', array( 'MPReadingList', 'add_to_list' ) );
add_action( 'wp_ajax_remove_from_reading_list', array( 'MPReadingList', 'remove_from_list' ) );
add_action( 'wp_ajax_reading_list_count', array( 'MPReadingList', 'reading_list_count' ) );