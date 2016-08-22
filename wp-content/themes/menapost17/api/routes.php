<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
/* wp-load loads all of Wordpress, executing a bunch of unnecessary DB queries in the process. The only reason we load it here is to get a database connection. We could skip the big WP load if we had a way of getting the DB credentials from wp-config.php without executing the `require_once` call at the bottom of it (classic seperation-of-concerns fail).
*/

require_once( __DIR__ . '/render-query.php' );
require_once( __DIR__ . '/render-article-list.php' );
require_once( __DIR__ . '/select-articles-from-categories.php' );
require_once( __DIR__ . '/select-newest-articles.php' );

// HTTP requests to this file that have an `action` parameter will be handled by a function named after the action value, below.
$routes = array(
	'select-newest-articles' => function() {
		header('Content Type: text/json');
		die( json_encode( selectNewestArticles() ));
	},
	'select-articles-from-categories' => function() { // Return a list of articles
		$categories = array_map( 'trim', explode( ',', $_REQUEST['categories'] ));

		header('Content Type: text/json');
		die( json_encode( selectArticlesFromCategories( $categories )));
	},
	'render-article-list' => function() { // Render a list of articles
		$ids = array_map( 'trim', explode( ',', $_REQUEST['ids'] ));

		header('Content Type: text/html');
		die( renderArticleList( $ids, $_REQUEST['template'] ));
	},
	'wp-query' => function() { // Render a WP_Query loop
		header('Content Type: text/html');
		die( renderQuery( urldecode( $_REQUEST['query'] ), $_REQUEST['template'] ));
	}
 );

call_user_func( $routes[$_REQUEST['action']] );
