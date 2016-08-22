<?php

/**
 * Returns the articles list based on the type.
 * @param $type - Type of the filter
 * @param $offset - Start Offset 
 * @return Array of articles 
 */
function sidebar_filter_articles_list( $type, $offset, $args = array() ) {
	$result = FALSE;

	switch( $type ) {
		case 'recent':
			$result = get_recent_articles_list( $offset );
			break;
		case 'most-viewed':
			$result = get_most_viewed_articles_list( $offset );
			break;
		case 'trending':
			$result = get_trending_articles_list( $offset );
			break;
		case 'recommended':
			$result = get_recommended_articles_list( $offset, $args );
			break;
		case 'active': 
			$result = get_active_articles_list( $offset );
			break;
	}

	return $result;
}

/**
 * Get Recent articles list.
 */
function get_recent_articles_list( $start = 0 ) {
	$query = "SELECT wp_posts.ID FROM wp_posts WHERE wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post' ORDER BY wp_posts.post_date DESC";

	$dataSource = new DataSource();

	$ids = $dataSource->fetch_post_ids( $query );

	//mp_log( $ids );

	if( empty($ids) ) {
		return FALSE;
	}

	$next_start = 0;
	$ids = Utilities::circular_array_slice( $ids, $start, Constants::SIDEBAR_ARTICLES_COUNT, $next_start );

	// mp_log( $ids );

	$result = $dataSource->fetch_posts( $ids, array( 'fields' => array( 'post_id', 'title' ) ) );

	return wrap_result( $next_start, $result );
}

/**
 * Get Most Viewed articles list.
 */
function get_most_viewed_articles_list( $start = 0 ) {
	$query = "SELECT wp_posts.ID FROM wp_posts JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID WHERE post_type = 'post' AND post_status = 'publish' AND meta_key = 'views' ORDER BY CAST(meta_value AS UNSIGNED) DESC";

	$dataSource = new DataSource();

	$ids = $dataSource->fetch_post_ids( $query );

	//mp_log( $ids );

	if( empty($ids) ) {
		return FALSE;
	}

	$next_start = 0;
	$ids = Utilities::circular_array_slice( $ids, $start, Constants::SIDEBAR_ARTICLES_COUNT, $next_start );

	// mp_log( $ids );

	$result = $dataSource->fetch_posts( $ids, array( 'fields' => array( 'post_id', 'title' ) ) );

	return wrap_result( $next_start, $result );
}

/**
 * Get Trending articles list.
 */
function get_trending_articles_list( $start = 0 ) {
	$query = "SELECT wp_posts.ID FROM wp_trending LEFT JOIN wp_posts ON wp_posts.ID = post_id WHERE date_time >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 HOUR) AND post_type = 'post' AND post_status = 'publish' GROUP BY wp_trending.post_id ORDER BY SUM(pageviews) DESC";

	$dataSource = new DataSource();

	$ids = $dataSource->fetch_post_ids( $query );

	//mp_log( $ids );

	if( empty($ids) ) {
		return FALSE;
	}

	$next_start = 0;
	$ids = Utilities::circular_array_slice( $ids, $start, Constants::SIDEBAR_ARTICLES_COUNT, $next_start );

	// mp_log( $ids );

	$result = $dataSource->fetch_posts( $ids, array( 'fields' => array( 'post_id', 'title' ) ) );

	return wrap_result( $next_start, $result );
}

/**
 * Get Recommended articles list.
 */
function get_recommended_articles_list( $start = 0, $args = array() ) {
	if( !empty( $args ) )
		$post_id = $args[ 'post_id' ];
	else {
		global $post;
		$post_id = $post->ID;
	}

	$query = "SELECT wp_posts.ID FROM wp_posts JOIN (SELECT DISTINCT object_id FROM wp_term_relationships JOIN (SELECT term_taxonomy_id FROM wp_term_relationships WHERE wp_term_relationships.object_id = $post_id ) post_terms ON post_terms.term_taxonomy_id = wp_term_relationships.term_taxonomy_id) term_objects ON term_objects.object_id = wp_posts.ID JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID AND meta_key = 'views' WHERE wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post' ORDER BY CAST(meta_value AS UNSIGNED) DESC";

	$dataSource = new DataSource();

	$ids = $dataSource->fetch_post_ids( $query );

	//mp_log( $ids );

	if( empty($ids) ) {
		return FALSE;
	}

	$next_start = 0;
	$ids = Utilities::circular_array_slice( $ids, $start, Constants::SIDEBAR_ARTICLES_COUNT, $next_start );

	// mp_log( $ids );

	$result = $dataSource->fetch_posts( $ids, array( 'fields' => array( 'post_id', 'title' ) ) );

	return wrap_result( $next_start, $result );
}

/**
 * Get Most Active articles list.
 */
function get_active_articles_list( $start = 0 ) {
	$query = "SELECT wp_posts.ID FROM wp_activity_value LEFT JOIN wp_posts ON wp_posts.ID = wp_activity_value.post_id WHERE post_type = 'post' AND post_status = 'publish' ORDER BY wp_activity_value.total DESC";

	$dataSource = new DataSource();

	$ids = $dataSource->fetch_post_ids( $query );

	// mp_log( $ids );

	if( empty($ids) ) {
		return FALSE;
	}

	$next_start = 0;
	$ids = Utilities::circular_array_slice( $ids, $start, Constants::SIDEBAR_ARTICLES_COUNT, $next_start );

	// mp_log( $ids );

	$result = $dataSource->fetch_posts( $ids, array( 'fields' => array( 'post_id', 'title' ) ) );

	return wrap_result( $next_start, $result );
}


function wrap_result( $next_start, $array ) {
	$result = array( 
		'offset' => $next_start, 
		'result' => $array 
	);

	return $result;
}