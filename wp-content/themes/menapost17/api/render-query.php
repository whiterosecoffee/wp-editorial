<?php

function renderQuery($queryString, $templateName) {
	global $post;

	// Prepare the theme for rendering content
	require_once __DIR__ . '/../functions.php';

	// template name => template path relative to theme, no leading slash.
	$templates = array(
		'article-excerpt' => 'views/article-excerpt',
		'article-mini-excerpt' => 'views/newest-excerpt'
	);

	wp_reset_query();
	$query = new WP_Query($queryString);

	ob_start();
	while($query->have_posts()) {
		$query->the_post();
		get_template_part($templates[$templateName]);
	}

	// Return the generated HTML, don't just spit it out.
	return ob_get_clean();
}
