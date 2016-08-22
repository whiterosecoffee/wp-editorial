<?php

function renderArticleList($articleIds, $templateName) {
	global $post;

	// Security: Include template files by referring to a path by a name. Do not allow http requests to directly specify a php file to execute.
	$templates = array(
		'article-excerpt' => '/views/article-excerpt.php',
		'article-mini-excerpt' => '/views/newest-excerpt.php'
	);

	require_once __DIR__ . '/../functions.php';

	$query = new WP_Query(array(
		'post__in' => $articleIds,
	));

	ob_start();
	while( $query->have_posts() ) {
		$query->the_post();
		include get_stylesheet_directory() . $templates[$templateName];
	}

	return ob_get_clean();
}
