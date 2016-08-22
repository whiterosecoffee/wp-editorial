<?php 

function apply_category( $query, $category ) {
	/*if( !$category )
		return str_replace( '{category}', '', $query );
	if( is_array( $category ) ) {

		$categories_cond = array();
		if( isset( $category['category'] ) ) {
			$categories_cond[]= $category['category'][2];
		}
		if( isset( $category['subcategory'] ) ) {
			$categories_cond[]= $category['subcategory'][2];
		}

		$query = str_replace( '{category}', "JOIN (SELECT object_id as post_id, wp_term_taxonomy.term_id AS category_id FROM wp_term_relationships JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_taxonomy.term_id IN ( " . implode( ',', $categories_cond ) . " ) group by object_id having count(1) = " . count( $categories_cond ) . " ) categories ON wp_posts.ID = categories.post_id ", $query );
	} else {
		$query = str_replace( '{category}', "JOIN (SELECT wp_terms.term_id as category_id, wp_term_relationships.object_id as post_id FROM wp_term_taxonomy JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id JOIN wp_term_relationships ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id WHERE wp_terms.slug = '" . mysql_real_escape_string( $category ) . "') categories ON wp_posts.ID = categories.post_id ", $query );
	}
	//mp_log( $query );
	return $query;*/
}

function apply_include( $query, $posts_array, $include_query = False ) {
	if( $posts_array && !empty( $posts_array ) ) {
		if( is_string( $include_query ) )
			$query = str_replace( '{include}', ' AND wp_posts.ID IN (' . $include_query . ') ', $query );
		else
			$query = str_replace( '{include}', ' AND wp_posts.ID IN (' . implode(',', $posts_array ) . ') ', $query );
	} else {
		$query = str_replace( '{include}', '', $query );
	}
	return $query;
}


function __parse_query( $results ) {
	$parsed = array();
	array_walk_recursive($results, function($a) use (&$parsed) { $parsed[] = $a; });
	return $parsed;
}

function __get_categories() {
	global $wpdb;
	$query = "SELECT wp_terms.term_id
				FROM wp_term_taxonomy
				JOIN wp_terms ON wp_term_taxonomy.term_id = wp_terms.term_id AND wp_terms.name NOT IN ('Uncategorized')
				WHERE taxonomy = 'category'";

	return __parse_query( $wpdb->get_results( $query, ARRAY_N ) );
}

function get_element_by_id( $posts, $value ) {
	for ($i=0; $i < count($posts); $i++) { 
		if( $posts[$i]->id == $value)
			return $posts[$i];
	}
}

function __wp_sort_posts( $posts, $order ) {
	//mp_log( $order );

	foreach ($order as $key => $value) {
		$result[] = get_element_by_id( $posts, $value );
	}
	//mp_log( $result );
	
	return $result;
}

function __get_posts( $args ) {
	global $wpdb;

	$limit = $args["posts_per_page"];
	$post__in = $args["post__in"];

	$query = "SELECT wpp.ID as id, wpp.post_title as title, wpp.post_content as content, IFNULL(wpm.meta_value, '') as image, wpu.display_name as author, wpu.ID as author_id, wpp.post_date as date, IFNULL(rdm.meta_value, 0) as read_duration, wpv.* FROM wp_posts wpp LEFT JOIN wp_postmeta wpm ON wpm.post_id = wpp.ID AND wpm.meta_key = 'image' LEFT JOIN wp_postmeta rdm ON rdm.post_id = wpp.ID AND rdm.meta_key = 'read-duration' LEFT JOIN wp_activity_value wpv ON wpv.post_id = wpp.ID JOIN wp_users wpu ON wpu.ID = wpp.post_author WHERE wpp.post_type = 'post' AND wpp.ID IN ( " . implode(",", $post__in) . " ) LIMIT $limit";

	$results = $wpdb->get_results( $query, ARRAY_A );
	$results_posts = array();

	foreach ($results as $key => $value) {
		$mp_post = new MP_Post();
		$results_posts[] = $mp_post->parse($value);
	}
	return $results_posts;
}

function __wp_query_results( $query, $sort = false, $posts_array = array(), &$count = -1, $exclude_featured = false ) {
	global $wpdb;
	$posts_excl = array();

	if( empty( $posts_array )) {

		$excl_query = "";

		if( $exclude_featured ) {

			// Exclude featured post
			$featured_id = get_featured_articles_id();
			if( isset( $featured_id ) ){
				$posts_excl[] = $featured_id;
			}

			// If there are ids in posts to be excluded then add it to the query
			if( !empty( $posts_excl ) ) {
				$excl_query = ' AND wp_posts.ID NOT IN ( ' . implode( ',', $posts_excl ) . ' ) ';
			}

			// Exclude TV Series articles
			$tv_series_article_ids_query = get_tv_series_article_ids_query();
			$excl_query .= " AND wp_posts.ID NOT IN ( $tv_series_article_ids_query ) ";
		}

		$query = str_replace( '{exclude}', $excl_query, $query );
		
		$results = $wpdb->get_results( $query, ARRAY_N );

		if( $count != -1 ) {
			$count = get_last_query_rows_count();
		}
		
		if( !empty( $results ) )
			array_walk_recursive($results, function($a) use (&$posts_array) { $posts_array[] = $a; });
	}
	
	$args = array( 'post__in' => $posts_array, 'posts_per_page' => count( $posts_array ), 
				'post__not_in' => $posts_excl );

	// mp_log( $posts_array );
	if( !empty( $posts_array ) ) 
		// $results = ( $sort ) ? __wp_sort_posts( get_posts( $args ), $posts_array ) : get_posts( $args );
		$results = ( $sort ) ? __wp_sort_posts( __get_posts( $args ), $posts_array ) : __get_posts( $args );
	else 
		$results = NULL;
	// mp_log( $results );
	return $results;

}

function get_last_query_rows_count() {
	global $wpdb;

	$count = $wpdb->get_var( "SELECT FOUND_ROWS()" );
	
	return $count;
}

function mp_get_most_viewed_articles( $category = false, $limit = 10, &$count = -1, $offset = 0, $exclude_featured = false, $posts_array = false, $include_query = False ) {
	$query = "SELECT SQL_CALC_FOUND_ROWS distinct wp_posts.ID AS mp_post_id
				FROM wp_posts
				JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID
				{category}
				WHERE post_type = 'post' AND post_status = 'publish' AND meta_key = 'views'
				{exclude}
				{include}
				ORDER BY CAST(meta_value AS UNSIGNED) DESC LIMIT $limit OFFSET $offset";
	$query = apply_category( $query, $category );
	$query = apply_include( $query, $posts_array, $include_query );	
	return __wp_query_results( $query, true, array(), $count, $exclude_featured );
}


function mp_get_recent_articles( $category = false, $limit = 10, &$count = -1, $offset = 0, $exclude_featured = false, $posts_array = false, $include_query = False ) {
	$query = "SELECT SQL_CALC_FOUND_ROWS wp_posts.ID AS mp_post_id
				FROM wp_posts
				{category}
				WHERE post_type = 'post' AND post_status = 'publish' 
				{exclude}
				{include}
				ORDER BY wp_posts.post_date DESC LIMIT $limit OFFSET $offset";
	$query = apply_category( $query, $category );
	$query = apply_include( $query, $posts_array, $include_query );
	return __wp_query_results( $query, true, array(), $count, $exclude_featured );
}


function mp_get_trending_articles( $category = false, $limit = 10, &$count = -1, $offset = 0, $exclude_featured = false, $posts_array = false, $include_query = False ) {
	$query = "SELECT SQL_CALC_FOUND_ROWS wp_posts.ID AS mp_post_id
				FROM wp_trending
				LEFT JOIN wp_posts ON wp_posts.ID = post_id
				{category}
				WHERE date_time >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 HOUR) AND post_type = 'post' AND post_status = 'publish' 
				{exclude}
				{include}
				GROUP BY wp_trending.post_id
				ORDER BY SUM(pageviews) DESC LIMIT $limit OFFSET $offset";
	$query = apply_category( $query, $category );
	$query = apply_include( $query, $posts_array, $include_query );	
	return __wp_query_results( $query, true, array(), $count, $exclude_featured );
}

function mp_get_active_articles( $category = false, $limit = 10, &$count = -1, $offset = 0, $exclude_featured = false, $posts_array = false, $include_query = False ) {
	$query = "SELECT SQL_CALC_FOUND_ROWS wp_posts.ID AS mp_post_id
				FROM wp_activity_value
				LEFT JOIN wp_posts ON wp_posts.ID = wp_activity_value.post_id
				{category}
				WHERE post_type = 'post' AND post_status = 'publish'
				{exclude}
				{include}
				ORDER BY wp_activity_value.total DESC LIMIT $limit OFFSET $offset";
	$query = apply_category( $query, $category );
	$query = apply_include( $query, $posts_array, $include_query );	
	return __wp_query_results( $query, true, array(), $count, $exclude_featured );
}

function mp_get_reading_list( $filter, $limit , &$count, $offset = 0 ) {
	global $wpdb;

	$user_id = get_current_user_id();

	$query = "SELECT wp_posts.ID AS mp_post_id 
				FROM wp_posts JOIN wp_readinglist ON wp_posts.ID = post_id AND user_id = $user_id AND status = 1
				ORDER BY wp_posts.post_date DESC";

	$posts_array = __parse_query( $wpdb->get_results( $query, ARRAY_N ) );
	
	// mp_log( "reading list" );
	// mp_log( $offset );

	if( empty( $posts_array ) ) {
		$count = 0;
		return array();
	}

	return sort_posts_by_filter( $posts_array, $filter, $limit, $count, $offset );
}

function mp_get_recommended_readings( $categories, $posts_array ) {
	global $wpdb;

	foreach ($categories as $key => $value) {

		if( !is_array( $value ) )
			$value = array( $value );

		$query = "SELECT wp_posts.id AS post_id
				FROM wp_posts
				JOIN (
				SELECT wp_term_relationships.object_id AS object_id
				FROM wp_term_taxonomy
				JOIN wp_term_relationships ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_taxonomy.term_id IN (" 
				. implode( ",", $value ) . ") ) categories ON categories.object_id = wp_posts.ID
				JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID AND meta_key = 'views'"
				. ( empty( $posts_array ) ? "" : ( " WHERE wp_posts.id NOT IN (" . implode( ",", $posts_array ) . ")" ) ) .
				" ORDER BY CAST(meta_value AS UNSIGNED) DESC LIMIT 3";

				
	 	$posts_array = array_merge( $posts_array, __parse_query( $wpdb->get_results( $query, ARRAY_N ) ) );	
	 	
	}

	return $posts_array;

}

function sort_posts_by_filter( $posts_array, $filter, $limit, &$count = 0, $offset = 0, $query = False ) {

	switch( $filter ) {
        case "recent": 
            $result_articles = mp_get_recent_articles( false, $limit, $count, $offset, false, $posts_array, $query );
            break;
        case "popular":
            $result_articles = mp_get_most_viewed_articles( false, $limit, $count, $offset, false, $posts_array, $query );
            break;
        case "trending":
            $result_articles = mp_get_trending_articles( false, $limit, $count, $offset, false, $posts_array, $query );
            break;
        case "active":
            $result_articles = mp_get_active_articles( false, $limit, $count, $offset, false, $posts_array, $query );
            break;
    } 

	return $result_articles;
}

function mp_get_recommended_list( $is_single = false, $filter = false, $limit = 10, &$count = 0, $offset = 0 ) {

	global $post;

	$posts_array = array();
	$categories = array(); 

	if( !$is_single ) {
		$categories = __get_categories();
	} else {
		$category = get_the_category( $post->ID );
		$categories[] = array();

		foreach ($category as $key => $value) {
			array_push( $categories[0], $value->term_id );
		}

		$posts_array[] = $post->ID;
	}

	$query = "";

	$posts_array = mp_get_recommended_readings( $categories, $posts_array );

	if( $is_single ) {
		unset( $posts_array[0] );
	} else {
		return sort_posts_by_filter( $posts_array, $filter, $limit, $count, $offset );
	}
	
	// mp_log( $posts_array );
	// mp_log( $categories );

	return __wp_query_results( $query, true, $posts_array );
}


function get_articles_by_filter( $filter, $category, &$total_count, $offset = 0, $exclude_featured = false, $limit = Constants::HOMEPAGE_ARTICLES_COUNT ) {
    // mp_log( 'Inside get filters' );

    switch( $filter ) {
        case "recent": 
            $result_articles = mp_get_recent_articles( $category, $limit, $total_count, $offset, $exclude_featured );
            break;
        case "popular":
            $result_articles = mp_get_most_viewed_articles( $category, $limit, $total_count, $offset, $exclude_featured );
            break;
        case "trending":
            $result_articles = mp_get_trending_articles( $category, $limit, $total_count, $offset, $exclude_featured );
            break;
        case "active":
            $result_articles = mp_get_active_articles( $category, $limit, $total_count, $offset, $exclude_featured );
            break;
    } 
    return $result_articles;
}

function get_featured_articles_query() {
	$query = "SELECT wp_posts.ID as mp_post_id 
				FROM wp_posts
				JOIN (
				SELECT wp_term_relationships.object_id
				FROM wp_terms
				JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
				JOIN wp_term_relationships ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
				WHERE wp_terms.name = 'featured' ) featured_posts
				ON wp_posts.ID = featured_posts.object_id
				WHERE wp_posts.post_status = 'publish'
				ORDER BY wp_posts.post_date DESC
				LIMIT 1";
	return $query;
}

function get_featured_articles_id() {
	global $wpdb;
	
	$query = get_featured_articles_query();
	$result = $wpdb->get_var( $query ); 		
	
	return $result;		
}

function get_featured_articles() {
	$query = get_featured_articles_query();
	$result = __wp_query_results( $query );			
	return $result;		
}

function get_category_order( $category ) {
	$order = 100;
	switch( $category[0] ) {
		case 'حياتي':
			$order = 0;
			break;
		case 'عائلتي':
			$order = 1;
			break;
		case 'مجتمعي':
			$order = 2;
			break;
		case 'عالمي':
			$order = 3;
			break;
	}
	return $order;
}

function mp_get_categories() {
	global $wpdb;
	
	$query = "SELECT wp_terms.name as category_name, wp_terms.slug as category_slug, wp_terms.term_id as id FROM wp_terms JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'category' WHERE wp_terms.name NOT IN ( 'Uncategorized' )";

	$result = $wpdb->get_results( $query, ARRAY_N );

	$ordered = array();
	foreach ($result as $key => $value) {
		$order = get_category_order( $value );
		if( $order == 100 )
			$order += $key;
		$ordered[ $order ] = $value;
	}
	krsort( $ordered ); 
	
	return $ordered;
}

function get_view_name( $view ) {
	$result = "";
	switch( $view ) {
		case 'bookmarks':
			$result = 'My Reading List';
			break;
		case 'recommended-readings':
			$result = "Recommended Readings";
			break;
		default:
			$result = "Recommended Readings";
			break;
	}
	return $result;
}

function get_filter_name( $filter ) {

    switch( $filter ) {
        case "popular":
            return "Most Viewed";
        case "trending":
            return "Trending";
        case "active":
            return "Most Active";
        case "":
        case "recent":
            return "Most Recent";

    }
}

function get_selected_view() {
	
	$views = array( 'recommended-readings', 'bookmarks' );
    $selected_view = get_query_var( 'view' );

    if( in_array( $selected_view , $views ) ) {
        return $selected_view;
    }

    return $views[0];
}

/**
 * Returns the selected sort filter
 */
function get_sort_filter() {

    $sort_filters = array( 'recent', 'popular', 'trending', 'active' );
    $selected_filter = get_query_var( 'sort' );

    if( in_array( $selected_filter , $sort_filters ) ) {
        return $selected_filter;
    }

    return $sort_filters[0];
}

function mp_get_profile_articles( $view, $filter, &$count, $offset = 0, $limit = Constants::PROFILE_PAGE_ARTICLES_COUNT ) {
	$result = "";
	// mp_log( "Profile articles" );
	// mp_log( $offset );
	switch ( $view ) {
		case 'recommended-readings':
			$result = mp_get_recommended_list( false, $filter, $limit, $count, $offset );
			break;
		case 'bookmarks':
			$result = mp_get_reading_list( $filter, $limit, $count, $offset );
			break;
	}
	return $result;
}

function mp_get_articles_by_mood( $mood, $filter, $limit, &$count, $offset ) {
	global $wpdb;

	$query = "SELECT object_id FROM wp_terms JOIN wp_term_taxonomy ON wp_term_taxonomy.term_id = wp_terms.term_id AND wp_terms.slug = '$mood' JOIN wp_term_relationships ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id JOIN wp_posts ON wp_posts.ID = object_id AND wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post'";

	$posts_array = __parse_query( $wpdb->get_results( $query, ARRAY_N ) );

	if( empty( $posts_array ) ) {
		$count = 0;
		return array();
	}

	return sort_posts_by_filter( $posts_array, $filter, $limit, $count, $offset, $query );
}


function mp_get_mood_articles( $mood, $filter, &$count, $offset = 0, $limit = Constants::MOOD_LANDING_PAGE_COUNT ) {
	$result = "";
	
	$result = mp_get_articles_by_mood( $mood, $filter, $limit, $count, $offset );

	return $result;
}

function mp_get_articles_by_post_tag( $post_tag, $filter, $limit, &$count, $offset ) {
	global $wpdb;

	if( is_array( $post_tag ) ) {
		if( is_numeric( $post_tag[0] ) )
			$query = $wpdb->prepare( "SELECT object_id FROM wp_term_relationships JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_taxonomy.term_id IN ( " . esc_sql( implode( ',', $post_tag ) ) . " ) JOIN wp_posts ON wp_posts.ID = object_id AND wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post' group by object_id having count(1) = %d", count( $post_tag ));
		else
			$query = $wpdb->prepare( "SELECT object_id FROM wp_term_relationships JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id JOIN wp_terms ON wp_terms.term_id = wp_term_taxonomy.term_id AND wp_terms.slug IN ( " . implode( ',', array_map( 'Utilities::add_quotes_and_escape', $post_tag ) ) . " ) JOIN wp_posts ON wp_posts.ID = object_id AND wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post' group by object_id having count(1) = %d", count( $post_tag ));
	} else {
		$query = $wpdb->prepare( "SELECT object_id FROM wp_terms JOIN wp_term_taxonomy ON wp_term_taxonomy.term_id = wp_terms.term_id AND wp_terms.slug = '%s' JOIN wp_term_relationships ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id JOIN wp_posts ON wp_posts.ID = object_id AND wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post'", $post_tag );
	}

	$posts_array = __parse_query( $wpdb->get_results( $query, ARRAY_N ) );

	if( empty( $posts_array ) ) {
		$count = 0;
		return array();
	}

	return sort_posts_by_filter( $posts_array, $filter, $limit, $count, $offset, $query );
}


function mp_get_post_tag_articles( $post_tag, $filter, &$count, $offset = 0, $limit = Constants::POST_TAG_LANDING_PAGE_COUNT ) {
	$result = "";
	
	$result = mp_get_articles_by_post_tag( $post_tag, $filter, $limit, $count, $offset );

	return $result;
}

function mp_get_articles_by_author( $author_id, $filter, $limit, &$count, $offset ) {
	global $wpdb;

	$author_id = intval( $author_id );

	$query = "SELECT wp_posts.ID FROM wp_posts WHERE wp_posts.post_author = $author_id AND post_type = 'post' AND post_status = 'publish'";

	$posts_array = __parse_query( $wpdb->get_results( $query, ARRAY_N ) );

	if( empty( $posts_array ) ) {
		$count = 0;
		return array();
	}

	return sort_posts_by_filter( $posts_array, $filter, $limit, $count, $offset, $query );
}


function mp_get_author_articles( $author_id, $filter, &$count, $offset = 0, $limit = Constants::AUTHOR_LANDING_PAGE_COUNT ) {
	$result = "";
	
	$result = mp_get_articles_by_author( $author_id, $filter, $limit, $count, $offset );

	return $result;
}

function mp_get_articles_by_mood_reading_time( $mood, $reading_time, $filter, $limit, &$count, $offset ) {
	global $wpdb;

	// if( !ctype_alpha( $mood ) ) {
	// 	wp_die( 'Cheating, uh!' );
	// }

	$reading_time = intval( $reading_time );

	$query = $wpdb->prepare( "SELECT distinct wp_posts.ID FROM wp_posts JOIN (SELECT object_id FROM wp_terms JOIN wp_term_taxonomy ON wp_term_taxonomy.term_id = wp_terms.term_id AND wp_terms.slug = '%s' JOIN wp_term_relationships ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id) mood_articles ON mood_articles.object_id = wp_posts.ID JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID AND wp_postmeta.meta_key = 'read-duration' AND wp_postmeta.meta_value != '' WHERE CONVERT( wp_postmeta.meta_value, UNSIGNED ) <= %d AND wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post'", $mood, $reading_time );

	$posts_array = __parse_query( $wpdb->get_results( $query, ARRAY_N ) );

	if( empty( $posts_array ) ) {
		$count = 0;
		return array();
	}

	return sort_posts_by_filter( $posts_array, $filter, $limit, $count, $offset );
}

function mp_get_explore_articles( $mood, $reading_time, $filter, &$count, $offset = 0, $limit = Constants::EXPLORE_PAGE_ARTICLES_COUNT ) {
	
	$result = mp_get_articles_by_mood_reading_time( $mood, $reading_time, $filter, $limit, $count, $offset );

	return $result;	
}

function mp_get_article_type_tags() {
    $result = array();

    $tags = array( 
    	'opinion',
    	'infographics',
    	'videos',
    	'comics',
    	'quiz',
    	);
    
    foreach ($tags as $tag) {
    	$result[] = get_term_by('slug', $tag, 'post_tag'); 
    }

    return array_filter( $result );	
}

function mp_get_seasonal_sub_tags( $category_slug = FALSE ) {
	$result = array();

	$seasonal_tags = mp_get_seasonal_tags();

	foreach ($seasonal_tags as $key => $value) {
		$result[ $value->slug ] = get_terms('seasonal', array(
			'parent' => $value->term_id,
			'hide_empty' => false,
			));
	}

	if( $category_slug && !array_key_exists( $category_slug, $result ) ) {
		return FALSE;
	}

    return $result;	
}

function mp_get_seasonal_tags( $excl = array() ) {
    $tags = array();
    $result = array();

    $tags = get_terms( 'seasonal', array(
    	'hide_empty'    => false, 
    	'parent'        => 0,
    	) );


    foreach ($tags as $tag) {
    	if( !in_array( $tag->slug, $excl ) )
    		$result[] = $tag;
    }

    // mp_log( $result );
    return array_filter( $result );	
}

function mp_get_sub_tags( $taxonomy, $term ) {
	if( is_string( $term ) ) {
		$term = get_term_by( 'slug', $term, $taxonomy );
	}

	return get_terms( $taxonomy, array(
			'parent'     => $term->term_id,
			'hide_empty' => False
		));
}

function mp_get_selected_category() {
	return get_query_var('category');
}

function mp_get_selected_sub_category() {
	return get_query_var('subcategory');
}

function get_sub_category_url( $category, $sub_category ) {
	return home_url( 'topic/' . $category . '/' . $sub_category );
}

function get_sub_tag_url( $category, $sub_category ) {
	if( is_array( $sub_category ) )
		$sub_category = implode( '/', $sub_category );

	return home_url( $category . '/' . $sub_category );
}

function get_category_filter( $categories, $selected_category, $selected_subcategory ) {
    
    $result = false;
    foreach ($categories as $category) {
        if( $category[1] == $selected_category )
            $result['category'] = $category;
    }


    if( !$result ) {
        foreach ( array_merge( mp_get_seasonal_tags(), mp_get_article_type_tags() ) as $tag ) {
            if( $tag->slug == $selected_category ) {
                $result = array();
                $category = array();
                $category[] = $tag->name;
                $category[] = $tag->slug;
                $category[] = $tag->term_id;
                $result['category'] = $category;
                
                if( $tag->taxonomy === 'seasonal' && !empty( $selected_subcategory ) ) {
                	$seasonal_sub_tags = mp_get_seasonal_sub_tags();
                	$selected_seasonal_sub_tags = $seasonal_sub_tags[$selected_category];

                	foreach ($selected_seasonal_sub_tags as $sub_tag) {

                		if( $sub_tag->slug === $selected_subcategory ) {
	                		$result['subcategory'] = array(
	                			$sub_tag->name,
	                			$sub_tag->slug,
	                			$sub_tag->term_id,
	                			);
                		}

                	}
                }

                break;
            }
        }
    }

    
    return $result;
}

/**
* Returns the ids of TV Series articles
*/
function get_tv_series_article_ids_query() {
	return get_post_ids_query_by_term( "tv-series" );
}

function get_post_ids_query_by_term( $term ) {
	return "Select wp_term_relationships.object_id FROM wp_terms JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id JOIN wp_term_relationships ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id WHERE wp_terms.slug = '$term'";
}