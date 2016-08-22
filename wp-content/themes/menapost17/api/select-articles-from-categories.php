<?php

function selectArticlesFromCategories($catgories) {
	global $wpdb;

	require_once __DIR__ . '/../functions.php';

	$postIds = array();

	foreach( $catgories as $category ) {
		$query = "
			SELECT SQL_CACHE ID from wp_posts
			JOIN post_stats ON wp_posts.id = post_stats.post_id
			WHERE
				post_type = 'post'
				AND post_status = 'publish'
				AND category = %s
			ORDER BY rank DESC
		";

		$results = $wpdb->get_col($wpdb->prepare($query, $category));

		$postIds = array_merge($postIds, $results);
	}

	return $postIds;
}
