<?php

if( !class_exists( 'MPTrending' ) )  {

	require_once( dirname(__FILE__) . '/MPPlugin.php' );

	class MPTrending extends MPPlugin {

		const TRENDING_TABLE_NAME = 'trending';
		const MP_TRENDING_VERSION = 0.1;

		public function __construct() {
			parent::__construct( 'MPTrending', self::MP_TRENDING_VERSION );

			add_action( 'the_content', array( &$this, 'update_pageviews' ) );
			add_action( 'trending_table_cleanup', array( &$this, 'trending_table_cleanup' ) );

			$this->schedule_cleanup();
		}

		/**
		 * 	Create database table for trending.
		 */
		protected function install() {
			global $wpdb;

			$wpdb->show_errors();
			$table = $wpdb->prefix . self::TRENDING_TABLE_NAME;
			$sql = "";
			$charset_collate = "";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			if ( ! empty($wpdb->charset) ) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) ) $charset_collate .= " COLLATE $wpdb->collate";

			// does the table exists?
			if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table ) { // fresh setup
				$sql = "CREATE TABLE " . $table ." ( UNIQUE KEY compositeID (post_id, date_time), post_id int(10) NOT NULL, date_time datetime NOT NULL default '0000-00-00 00:00', pageviews int(5) NOT NULL default 1 ) $charset_collate;";
				dbDelta($sql);
			} 

			$this->schedule_cleanup();


			parent::install();
		}

		public function update_pageviews( $content ) {
			global $wpdb, $post;

			if( !is_single() ) 
				return $content;

			$table = $wpdb->prefix . self::TRENDING_TABLE_NAME;

			$result = $wpdb->query( $wpdb->prepare(
				"INSERT INTO $table (post_id, date_time, pageviews) VALUES(%d, %s, %d) ON DUPLICATE KEY UPDATE pageviews = pageviews + 1",
				$post->ID, $this->current_date_hour(), 1
				) );
			return $content;
		}

		private function current_date_hour() {
			return gmdate( 'Y-m-d H:i' );
		}

		private function schedule_cleanup() {
			if( !wp_next_scheduled( 'trending_table_cleanup' ) ) 
				wp_schedule_event( time(), 'daily', 'trending_table_cleanup' );
		}

		public function trending_table_cleanup() {
			global $wpdb;
			
			$table = $wpdb->prefix . self::TRENDING_TABLE_NAME;

			$result = $wpdb->query( "DELETE FROM $table WHERE date_time < DATE_SUB( UTC_TIMESTAMP(), INTERVAL 1 DAY )" );

			//error_log( "Cleared" );
		}

		public static function is_article_trending( $article_id ) {
			global $wpdb;

			$trending_articles = $wpdb->get_results( "SELECT wpp.ID as id FROM wp_trending LEFT JOIN wp_posts wpp ON wpp.ID = post_id WHERE date_time >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 HOUR) AND post_type = 'post' AND post_status = 'publish' GROUP BY post_id ORDER BY SUM(pageviews) DESC LIMIT 10", ARRAY_N );
			
			$trending_articles = Utilities::flat_array( $trending_articles );

			$result = is_array( $trending_articles ) && !empty( $trending_articles ) && in_array( $article_id, $trending_articles ); 
			
			return $result; 
		}

	}

}

add_filter( 'is_article_trending', array( 'MPTrending', 'is_article_trending') );