<?php

class DataSource {
	
	public function fetch_post_ids( $query ) {
		global $wpdb;

		$result = Utilities::flat_array( $wpdb->get_results( $query, ARRAY_N ) );

		return $result;
	}

	public function fetch_posts( $ids, $args ) {
		global $wpdb;

		$fields = $args[ 'fields' ];
		$joins = array();

		$query = "SELECT ";
		
		if( in_array( "post_id", $fields ) ) {
			$query .= "wp_posts.ID AS id, ";
		}

		if( in_array( "title", $fields ) ) {
			$query .= "wp_posts.post_title AS title, ";
		}

		if( in_array( "content", $fields ) ) {
			$query .= "wp_posts.post_content AS content, ";
		}

		if( in_array( "date", $fields ) ) {
			$query .= "wp_posts.post_date AS date, ";
		}

		if( in_array( "author", $fields ) ) {
			$query .= "wp_users.display_name AS author, ";
			$joins["wp_users"] = " JOIN wp_users ON wp_users.ID = wp_posts.post_author ";
		}

		// Remove the last comma and space
		$query = substr( $query, 0, -2 );

		$query .= " FROM wp_posts ";

		foreach ($joins as $join) {
			$query .= $join;
		}

		$query .= " WHERE wp_posts.ID IN (" . implode( ", ", $ids ) . ")";

		$result = $wpdb->get_results( $query, ARRAY_A );
		$result = $this->convert_to_mp_post( $result );
		$result = Utilities::sort_array( $result, $ids );
		
		return $result;
	}

	public function convert_to_mp_post( $array ) {
		$result = array();

		foreach ($array as $value) {
			$mp_post = new MP_Post();
			$result[] = $mp_post->parse( $value );
		}

		return $result;
	}

	public function search( $query, $page = 1 ) {
		global $wpdb;

		$query = '%' . like_escape( esc_sql( $query ) ) . '%'; 

		$query = "SELECT wp_posts.ID FROM wp_posts JOIN wp_users ON wp_users.ID = wp_posts.post_author WHERE ( wp_posts.post_title LIKE '$query' OR wp_posts.post_content LIKE '$query' OR wp_users.display_name LIKE '$query' OR wp_posts.ID IN ( SELECT object_id FROM wp_term_relationships JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id JOIN wp_terms ON wp_terms.term_id = wp_term_taxonomy.term_id WHERE wp_terms.name LIKE '$query' ) ) AND wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post'";

		$ids = $this->fetch_post_ids( $query );

		$paginated = Utilities::apply_pagination( $ids, $page, Constants::SEARCH_RESULTS_COUNT );
		
		if( empty( $paginated[ 'ids' ] ) ) {
			return array();
		}
		
		$paginated[ 'result' ] = $this->fetch_posts( $paginated[ 'ids' ], array( 'fields' => array( 'post_id', 'title', 'content', "date", 'author' ) ) );

		return $paginated;
	}

	public static function get_article_by_id( $id ) {
		$dataSource = new DataSource();
		$result = $dataSource->fetch_posts( array( $id ), array( 'fields' => array( 'post_id', 'title' ) ) );

		if( !empty( $result ) ) {
			return $result[0];
		}
		return FALSE;
	}

	public static function get_mood_tags() {
		$mood_tags = get_terms( 'mood' );

		return $mood_tags;
	}

	public static function get_reading_times() {
		global $wpdb;

		$reading_times = $wpdb->get_results( "SELECT distinct meta_value FROM wp_postmeta WHERE meta_key = 'read-duration' AND meta_value != '' ORDER BY CONVERT(meta_value, UNSIGNED)", ARRAY_N );

		foreach ($reading_times as $key => $value) {
			$value[] = Utilities::reading_time_string( $value[ 0 ] );
			$reading_times[ $key ] = $value;
		}

		return $reading_times;
	}

	public static function team_members() {
		global $wpdb;

		$query = "SELECT wp_users.ID AS ID, wp_users.display_name AS display_name, wp_users.user_email AS email, fb.meta_value AS facebook_id, tw.meta_value AS twitter_id, titl.meta_value AS title, CONVERT(ord.meta_value, UNSIGNED) AS 'order' FROM wp_users JOIN (SELECT * FROM wp_usermeta WHERE meta_key = 'is-team-member' AND meta_value = 'on') team_members ON team_members.user_id = wp_users.ID LEFT JOIN wp_usermeta fb ON fb.user_id = ID AND fb.meta_key = 'facebook' LEFT JOIN wp_usermeta tw ON tw.user_id = ID AND tw.meta_key = 'twitter' LEFT JOIN wp_usermeta ord ON ord.user_id = ID AND ord.meta_key = 'team-member-order' LEFT JOIN wp_usermeta titl ON titl.user_id = ID AND titl.meta_key = 'title' ORDER BY `order`";
		
		return $wpdb->get_results( $query );		
	}

	public static function get_random_most_shared_articles( $limit = 10, $count = 3 ) {
		$query = "SELECT wp_posts.ID FROM wp_activity_value LEFT JOIN wp_posts ON wp_posts.ID = wp_activity_value.post_id WHERE post_type = 'post' AND post_status = 'publish' ORDER BY wp_activity_value.total DESC LIMIT $limit";

		$dataSource = new DataSource();

		$ids = $dataSource->fetch_post_ids( $query );

		$rand_indexes = array_rand( $ids, $count );

		$result_ids = array();

		foreach ($rand_indexes as $rand_index) {
			$result_ids[] = $ids[ $rand_index ];
		}

		if( empty($ids) ) {
			return FALSE;
		}

		$result = $dataSource->fetch_posts( $result_ids, array( 'fields' => array( 'post_id', 'title' ) ) );

		return $result;
	}
}
