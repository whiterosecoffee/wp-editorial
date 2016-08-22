<?php

add_filter('posts_join', function ($join) {
	global $wpdb;

	$join .= "LEFT JOIN post_stats ON $wpdb->posts.ID = post_stats.post_id";

	return $join;
});

add_filter('posts_fields', function ($select) {
	$select .= ', post_stats.*';
	return $select;
});
