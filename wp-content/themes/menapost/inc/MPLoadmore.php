<?php 

class MPLoadmore {

	private static function send_response( $count, $articles ) {

		// mp_log( $count );

		$show_more_button = $count > Constants::LOADMORE_COUNT;
		array_walk( $articles, 'convert_to_json' );
		wp_send_json_success( $articles );
	}

	public static function home_load_more_articles() {
		check_ajax_referer( 'load_more', 'mp_nonce' );

		$limit = Constants::LOADMORE_COUNT;

		$categories = mp_get_categories();

		$filter = ( isset( $_GET[ 'filter' ] ) ) ? $_GET[ 'filter' ] : 'recent';
		$start = ( isset( $_GET[ 'start' ] ) ) ? intval( $_GET[ 'start' ] ) : 0;
		$category = ( isset( $_GET[ 'category' ] ) ) ? $_GET[ 'category' ] : false;
		$sub_category = ( isset( $_GET[ 'subcategory' ] ) ) ? $_GET[ 'subcategory' ] : '';
		$count = 0;

		if( $category == 'all' )
			$category = false;

		$result_articles = get_articles_by_filter( $filter, get_category_filter( $categories, $category, $sub_category ), $count, $start, true, $limit );

		self::send_response( $count, $result_articles );
	}

	public static function profile_load_more_articles() {
		check_ajax_referer( 'load_more', 'mp_nonce' );

		$limit = Constants::LOADMORE_COUNT;

		$filter = ( isset( $_GET[ 'filter' ] ) ) ? $_GET[ 'filter' ] : 'recent';
		$view = ( isset( $_GET['view'] ) ) ? $_GET[ 'view' ] : 'recommended-readings';
		$start = ( isset( $_GET[ 'start' ] ) ) ? intval( $_GET[ 'start' ] ) : 0;
		$count = 0;

		// mp_log( "Load more" );
		// mp_log( $start );

		$result_articles = mp_get_profile_articles( $view, $filter, $count, $start, $limit );

		self::send_response( $count, $result_articles );
	}

	public static function mood_landing_load_more_articles() {
		check_ajax_referer( 'load_more', 'mp_nonce' );

		$limit = Constants::LOADMORE_COUNT;

		$filter = ( isset( $_GET[ 'filter' ] ) ) ? $_GET[ 'filter' ] : 'recent';
		$mood = $_GET[ 'mood' ];
		$start = ( isset( $_GET[ 'start' ] ) ) ? intval( $_GET[ 'start' ] ) : 0;
		$count = 0;

		$result_articles = mp_get_mood_articles( $mood, $filter, $count, $start, $limit );

		self::send_response( $count, $result_articles );
	}

	public static function post_tag_landing_load_more_articles() {
	    check_ajax_referer( 'load_more', 'mp_nonce' );

	    $limit = Constants::LOADMORE_COUNT;

	    $filter = ( isset( $_GET[ 'filter' ] ) ) ? $_GET[ 'filter' ] : 'recent';
	    
	    $post_tag = $_GET[ 'post_tag' ];
	    $sub_post_tag = ( isset( $_GET[ 'sub_post_tag' ] ) ) ? $_GET[ 'sub_post_tag' ] : FALSE;
	    $series_sub_tag = ( isset( $_GET[ 'series_sub_tag' ] ) ) ? $_GET[ 'series_sub_tag' ] : FALSE;
	    
	    $start = ( isset( $_GET[ 'start' ] ) ) ? intval( $_GET[ 'start' ] ) : 0;
	    $count = 0;

	    if( $sub_post_tag ) {
	        $slugs = array( $post_tag, $sub_post_tag );

	        if( $series_sub_tag )
	          $slugs[] = $series_sub_tag;

	        $result_articles = mp_get_post_tag_articles( $slugs, $filter, $count, $start, $limit );
	    } else {
	        $result_articles = mp_get_post_tag_articles( $post_tag, $filter, $count, $start, $limit );
	    }

	    self::send_response( $count, $result_articles );
	}

	public static function author_load_more_articles() {
		check_ajax_referer( 'load_more', 'mp_nonce' );

		$limit = Constants::LOADMORE_COUNT;

		$filter = ( isset( $_GET[ 'filter' ] ) ) ? $_GET[ 'filter' ] : 'recent';
		$author_id = intval( $_GET[ 'author_id' ] );
		$start = ( isset( $_GET[ 'start' ] ) ) ? intval( $_GET[ 'start' ] ) : 0;
		$count = 0;

		$result_articles = mp_get_author_articles( $author_id, $filter, $count, $start, $limit );

		self::send_response( $count, $result_articles );
	}

	public static function explore_load_more_articles() {
		check_ajax_referer( 'load_more', 'mp_nonce' );

		$limit = Constants::LOADMORE_COUNT;

		$filter = ( isset( $_GET[ 'filter' ] ) ) ? $_GET[ 'filter' ] : 'recent';
		$reading_time = intval( $_GET[ 'reading_time' ] );
		$mood = ( isset( $_GET[ 'mood' ] ) ) ? $_GET[ 'mood' ] : '';
		$start = ( isset( $_GET[ 'start' ] ) ) ? intval( $_GET[ 'start' ] ) : 0;
		$count = 0;

		$result_articles = mp_get_explore_articles( $mood, $reading_time, $filter, $count, $start, $limit );

		self::send_response( $count, $result_articles );
	}
}