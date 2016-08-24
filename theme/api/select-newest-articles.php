<?php

function selectNewestArticles() {
	global $wpdb;

	require_once __DIR__ . '/../functions.php';

	$query = "
		SELECT SQL_CACHE ID from wp_posts
		WHERE
		post_type = 'post'
		AND post_status = 'publish'
		ORDER BY post_date DESC
	";

	return $wpdb->get_col($query);
}
