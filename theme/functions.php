<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

error_reporting(E_ALL ^ E_NOTICE);


//* Importing the magic ;)
include_once( CHILD_DIR . '/inc/filter.php' );
include_once( CHILD_DIR . '/inc/sidebar-filter.php' );
include_once( CHILD_DIR . '/inc/Constants.php' );
include_once( CHILD_DIR . '/inc/Utilities.php' );
include_once( CHILD_DIR . '/inc/ImageHandler.php' );
include_once( CHILD_DIR . '/inc/ProfileImageHandler.php' );
include_once( CHILD_DIR . '/inc/MP_Post.php' );
include_once( CHILD_DIR . '/inc/DataSource.php' );
include_once( CHILD_DIR . '/inc/FacebookMetaTags.php' );
include_once( CHILD_DIR . '/inc/SocialSharing.php' );
include_once( CHILD_DIR . '/inc/GoogleSearch.php' );
include_once( CHILD_DIR . '/inc/MPUrl.php' );
include_once( CHILD_DIR . '/inc/MPUser.php' );
include_once( CHILD_DIR . '/inc/MPLoadmore.php' );

require_once( CHILD_DIR . '/inc/post-stats.php' );

//* Child theme constants.
define( 'CHILD_THEME_NAME', 'menaPOST' );
define( 'CHILD_THEME_URL', 'http://www.menapo.com/' );
define( 'CHILD_THEME_VERSION', '1.163' ); /* original value was 1.161 - must change incrementally with each push to kasra.co for cdn to refresh */
define( 'FEATURED_ARTICLES', 1 );

define( 'RAMADANIYAT_TAG_SLUG', 'ramadaniyat' );
define( 'RAMADAN_SERIES_TAG_SLUG', 'ramadan-series' );
define( 'TV_SERIES_TAG_SLUG', 'tv-series' );


/**
 * Theme initialize action
 */
add_action( 'after_setup_theme', 'mp_after_theme_setup' );

/**
 * Scripts/Stylesheets action.
 */
add_action( 'wp_enqueue_scripts', 'mp_styles_scripts' );


/**
 * Initializes the theme.
 */
function mp_after_theme_setup() {

	//* Load language
	load_child_theme_textdomain( 'menapost-theme', CHILD_DIR . '/languages' );

	//* Add Featured Content support
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'mp_get_featured_posts',
		'max_posts' => 1,
	) );

	//* Add HTML5 markup structure
	add_theme_support( 'html5' );

	//* Add viewport meta tag for mobile browsers
	//add_theme_support( 'genesis-responsive-viewport' );

	//* Add support for custom background
	add_theme_support( 'custom-background' );

	//* Add support for 3-column footer widgets
	add_theme_support( 'genesis-footer-widgets', 3 );

	//* Add thumbnails support
	add_theme_support( 'post-thumbnails' );
	//add_image_size( $name, $width, $height, $crop );
	add_image_size( 'desktop-new', 111, 56, true );
	add_image_size( 'desktop-view', 278, 139, true );
	add_image_size( 'desktop-share', 573, 287, true );

	add_image_size( 'mobile', 480, 240, true );

	//* Removes the structural wraps
	add_theme_support( 'genesis-structural-wraps', array() );

	//* Clean up html header
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'genesis_load_favicon');


	remove_action( 'genesis_footer', 'genesis_do_footer' );
	add_action( 'genesis_footer', 'mp_do_footer' );

	remove_action( 'genesis_header', 'genesis_do_header' );
	add_action( 'genesis_header', 'mp_do_header' );

	remove_action( 'genesis_loop', 'genesis_do_loop' );
	add_action( 'genesis_loop', 'mp_blog_loop' );

	add_action( 'sidebar', 'mp_sidebar' );

	add_action( 'navbar', 'mp_navbar' );

	remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	show_admin_bar( false );

	add_action( 'genesis_meta', 'sp_viewport_meta_tag' );

	update_option( 'image_default_size', 'full' );

	add_action('wp_head', 'mp_favicon');

	add_filter( 'jpeg_quality', create_function( '', 'return 80;' ) );

	add_action( 'mp_loop_else', 'do_mp_loop_else' );

}

function mp_favicon(){
	echo '<link rel="shortcut icon" href="' . CHILD_URL . '/favicon.ico" />';
	echo '<link rel="apple-touch-icon-precomposed" sizes="152x152" icon" href="' . CHILD_URL . '/favicon/favicon-152.png" />';
	echo '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . CHILD_URL . '/favicon/favicon-144.png" />';
	echo '<link rel="apple-touch-icon-precomposed" sizes="120x120" icon" href="' . CHILD_URL . '/favicon/favicon-120.png" />';
	echo '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="' . CHILD_URL . '/favicon/favicon-72.png" />';
	echo '<meta name="msapplication-TileColor" content="#FFFFFF">';
	echo '<meta name="msapplication-TileImage" content="' . CHILD_URL . '/favicon/favicon-144.png">';

	if( is_single() ) {
		global $post;

		$size = 'polaroid';
		echo '<meta name="thumbnail" content="' . apply_filters( 'get_article_header_image', $post->ID, $size ) . '" />';
	} else {
		echo '<meta name="thumbnail" content="' . apply_filters( 'kasra_logo_header', '' ) . '" />';
	}
}


/**
 * Enqueues stylesheets and scripts.
 */
function mp_styles_scripts() {
	// Remove open sans
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );

	wp_enqueue_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'droid-arabic-naskh', '//openfontlibrary.org/face/droid-arabic-naskh', array(), CHILD_THEME_VERSION );
	// wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css', array(), CHILD_THEME_VERSION );

	//wp_enqueue_style( 'style', CHILD_URL . '/css/style.min.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'main', CHILD_URL . '/css/main.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'animate', CHILD_URL . '/css/animate.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'normalize', CHILD_URL . '/css/normalize.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'animation', CHILD_URL . '/css/animation.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'bootstrapValidator', CHILD_URL . '/css/bootstrapValidator.min.css', array(), CHILD_THEME_VERSION );

	wp_enqueue_style( 'kasra-ie7', CHILD_URL . '/css/kasra-ie7.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'kasra-ie7-codes', CHILD_URL . '/css/kasra-ie7-codes.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'kasra-embedded', CHILD_URL . '/css/kasra-embedded.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'kasra-codes', CHILD_URL . '/css/kasra-codes.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'kasra', CHILD_URL . '/css/kasra.css', array(), CHILD_THEME_VERSION );


	wp_enqueue_script( 'optimizely', '//cdn.optimizely.com/js/1051152560.js', array(), CHILD_THEME_VERSION, false ); // LIVE
	//wp_enqueue_script( 'optimizely', '//cdn.optimizely.com/js/1112513082.js', array(), CHILD_THEME_VERSION, false ); // DEV
	wp_enqueue_script( 'opentracker', 'https://script.opentracker.net/?site=kasra.co', array(), CHILD_THEME_VERSION, false );

	wp_enqueue_script( 'modernizr', CHILD_URL . '/js/vendor/modernizr-2.6.2.min.js', array(), CHILD_THEME_VERSION, false );
	wp_enqueue_script( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'fb-share', CHILD_URL . '/js/fb-share.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'article-loader', CHILD_URL . '/js/article-loader.js', array('jquery', 'lodash'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'query-loader', CHILD_URL . '/js/query-loader.js', array('jquery', 'lodash'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'tabbed-columns', CHILD_URL . '/js/tabbed-columns.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'lodash', CHILD_URL . '/js/lodash.compat.min.js', array(), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'search-form', CHILD_URL . '/js/search-form.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'menu-all-bottom', CHILD_URL . '/js/menu-all-bottom.js', array('jquery'), CHILD_THEME_VERSION, true );


	wp_enqueue_script( 'velocity', CHILD_URL . '/js/vendor/jquery.velocity.min.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'velocity.ui', CHILD_URL . '/js/vendor/velocity.ui.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'jquery.tmpl', CHILD_URL . '/js/vendor/jquery.tmpl.min.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'bootstrapValidator', CHILD_URL . '/js/vendor/bootstrapValidator.min.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'ZeroClipboard', CHILD_URL . '/js/vendor/ZeroClipboard.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'jquery.fitvids', CHILD_URL . '/js/vendor/jquery.fitvids.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'jquery.navgoco', CHILD_URL . '/js/jquery.navgoco.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'jquery.lazyload', CHILD_URL . '/js/vendor/jquery.lazyload.min.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'jquery.isotope', CHILD_URL . '/js/vendor/isotope.pkgd.min.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'main', CHILD_URL . '/js/main.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'jquery.isotope', CHILD_URL . '/js/vendor/isotope.pkgd.min.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'main', CHILD_URL . '/js/main.js', array('jquery'), CHILD_THEME_VERSION, true );
	wp_enqueue_script( 'plugins', CHILD_URL . '/js/plugins.js', array('jquery'), CHILD_THEME_VERSION, true );

	// Pass PHP data to client side scripts. Attaching it as a dependency of jQuery because jQ is likely to be the first script loaded.
	wp_localize_script( 'jquery', 'theme', array(
		'router' => get_stylesheet_directory_uri() .  '/api/routes.php'
	));
}

/**
 * Responsive viewport.
 */
function sp_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"  />';
}


/**
 * Creates the footer for the homepage.
 * @link mo_home_footer.php
 */
function mp_do_footer() {
	include( CHILD_DIR . '/mp_home_footer.php' );
}

/**
 * Creates the slider and the top navbar.
 * @link mp_header.php
 */
function mp_do_header() {
	//include( CHILD_DIR  . '/mp_home_header.php' );
}

/**
 * Creates the article layout (includes the loop).
 * @link mp_home_loop.php
 */
function mp_blog_loop() {
	if( is_search() ) {
		include( CHILD_DIR . '/mp_search_page.php' );
	} else if ( is_single() ) {

		global $post;
		$args = array( 'post__in' => array( $post->ID ), 'posts_per_page' => count( 1 ) );
		$article = __get_posts( $args );
		$article = $article[0];

		include( CHILD_DIR . '/mp_blog_loop.php' );
	}
}

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require CHILD_DIR . '/inc/featured-content.php';
}

/**
 * Fetches the featured posts - the articles with tag "featured".
 * @return WP_Post array - Featured Posts
 */
function mp_get_featured_posts() {
	return apply_filters( 'mp_get_featured_posts', array() );
}


/**
 * Checks if there are a 'minimum' number of featured posts.
 * @param int - minimum of featured posts to be checked.
 * @return bool - true if there are minimum number of featured posts.
 */
function mp_has_featured_posts( $minimum ) {
	if ( is_paged() )
		return false;

	$minimum = absint( $minimum );
	$featured_posts = apply_filters( 'mp_get_featured_posts', array() );

	if ( ! is_array( $featured_posts ) )
		return false;
	if ( $minimum > count( $featured_posts ) )
		return false;
	return true;
}

/**
 * Return an ID of an attachment by searching the database with the file URL.
 *
 * First checks to see if the $url is pointing to a file that exists in
 * the wp-content directory. If so, then we search the database for a
 * partial match consisting of the remaining path AFTER the wp-content
 * directory. Finally, if a match is found the attachment ID will be
 * returned.
 *
 * @return {int} $attachment
 */
function attachment_id_by_url( $url ) {

	// Split the $url into two parts with the wp-content directory as the separator.
	$parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

	// Get the host of the current site and the host of the $url, ignoring www.
	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

	// Return nothing if there aren't any $url parts or if the current host and $url host do not match.
	if ( ! isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) )
		return;

	// Now we're going to quickly search the DB for any attachment GUID with a partial path match.
	// Example: /uploads/2013/05/test-image.jpg
	global $wpdb;

	$prefix	 = $wpdb->prefix;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );

	// Returns null if no attachment is found.
	if( !empty( $attachment ) )
		return $attachment[0];
	else
		return FALSE;

}

/**
 * Checks for article hero image.
 * @param int - post id
 * @return print - image source url
 */
function the_article_image( $post_id ) {
	$meta = get_post_meta( $post_id, 'image', true );
	if( $meta == "" ) {
		$default_article_image = get_option('default_article_image');
		echo ($default_article_image ? $default_article_image : "http://placehold.it/600X400");
	}
	else
		echo $meta;
}

function the_article_thumbnail( $post_id ) {
	$meta = get_post_meta( $post_id, 'image', true );
	$attachmentId = attachment_id_by_url( $meta );

	// $images = array_values( get_children(array(
	//	  'post_type' => 'attachment',
	//	  'post_mime_type' => 'image',
	//	  'post_parent' => $post_id,
	//	  'orderby' => 'menu_order',
	//	  'order'  => 'ASC',
	//	  'numberposts' => 1,
	//	)) );

	if ($attachmentId && ( $src = wp_get_attachment_image_src( $attachmentId, array( 300, 225 ) ) ))
		echo $src[0];
	else
		echo "http://placehold.it/300X225";
}

function the_read_duration( $post_id ) {
	$meta = get_post_meta( $post_id, 'read-duration', true );
	if( $meta == "" )
		echo "";
	else
		echo $meta . ' ' . __( 'minutes read', 'menapost-theme' );
}


function redirect_to_front_page() {
	global $redirect_to;
	if (!isset($_GET['redirect_to'])) {
		$redirect_to = get_option('siteurl');
	}
}
add_action('login_form', 'redirect_to_front_page');


add_filter('query_vars', 'add_query_vars' );

function add_query_vars( $qvars ) {
	$qvars[] = 'sort';
	$qvars[] = 'category';
	$qvars[] = 'subcategory';
	$qvars[] = 'sub_tag';
	$qvars[] = 'series';
	$qvars[] = 'view';
	$qvars[] = 'page';
	$qvars[] = 'reading-time';
	$qvars[] = 'mood';
	return $qvars;
}

function do_mp_loop_else( $view ) {
	$message = '';
	switch ( $view ) {
	case 'bookmarks':
		$message = __( 'You can now save your favorite topics, and return to it at any time you want, by pressing the button (sic)', 'menapost-theme' ) . '<i class="icon-bookmark-empty mp-icon-sm dark add-to-rl"></i>';
		break;
	default:
		$message = __( 'No articles in this list.', 'menapost-theme' );
		break;
	}
	include( CHILD_DIR  . '/mp_loop_else.php' );
}

function mp_sidebar() {
	include( CHILD_DIR  . '/mp_sidebar.php' );
}

function mp_navbar() {
	include( CHILD_DIR . '/mp_navbar.php' );
}

function mp_log( $var ) {
	error_log( print_r( $var, true ) );
}


function custom_error_pages()
{
	global $wp_query;

	if(isset($_REQUEST['status']))
	{
		status_header($_REQUEST['status']);
		get_template_part( 'mp_error' );
		exit;
	}
}

add_action('wp','custom_error_pages');

function get_current_logout( $logout_url ){
	if ( !is_admin() ) {
		$logout_url = site_url('logout', 'login');
		$logout_url = wp_nonce_url( $logout_url, 'log-out' );
		$logout_url = add_query_arg('redirect_to', urlencode(( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), $logout_url);
	} else {
		$logout_url = add_query_arg('redirect_to', urlencode( home_url() ), $logout_url);
	}
	return $logout_url;
}

function get_current_login( $login_url ){
	$login_url = add_query_arg('redirect_to', urlencode(( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), $login_url);
	return $login_url;
}

function get_error_message( $error_code = 0 ) {
	$message = "";
	switch( $error_code ) {
	case 400:
		$message = __( 'ERROR - 400 Bad Request', 'menapost-theme' );
		break;
	case 401:
		$message = __( 'ERROR - 401 Unauthorized', 'menapost-theme' );
		break;
	case 403:
		$message = __( 'ERROR - 403 Forbidden', 'menapost-theme' );
		break;
	case 404:
		$message = __( 'ERROR - 404 File not found', 'menapost-theme' );
		break;
	case 500:
		$message = __( 'ERROR - 500 Internal Server Error', 'menapost-theme' );
		break;
	default:
		$message = __( 'ERROR - An unknown error has occured', 'menapost-theme' );
		break;
	}
	return $message;
}


add_filter('logout_url', 'get_current_logout');
add_filter('login_url', 'get_current_login');

function custom_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 998 );


function get_the_activity_value( $post_id ) {
	$activity_value = "";
	$activity_value = apply_filters( 'mp_get_activity_value', $post_id );
	return $activity_value;
}

function the_activity_count( $activity_value, $type ) {
	if( $activity_value == NULL ) {
		echo 0;
		return;
	}
	$count = 0;
	switch ($type) {
	case 'total':
		$count = $activity_value->get_total_count();
		break;
	case 'comment':
		$count = $activity_value->comment;
		break;
	case 'facebook_like':
		$count = $activity_value->facebook_like;
		break;
	case 'facebook_share':
		$count = $activity_value->facebook_share;
		break;
	case 'twitter':
		$count = $activity_value->twitter;
		break;
	case 'email':
		$count = $activity_value->email;
		break;
	case 'linkedin':
		$count = $activity_value->linkedin;
		break;
	case 'pininterest':
		$count = $activity_value->pininterest;
		break;
	case 'googleplus':
		$count = $activity_value->googleplus;
		break;
	case 'bookmark':
		$count = $activity_value->bookmark;
		break;
	case 'other':
		$count = $activity_value->other;
		break;
	case 'inlinecomment':
		$count = $activity_value->inlinecomment;
		break;
	default:
		$count = 0;
		break;
	}
	echo $count;
}

function convert_to_json( &$item, $key ) {
	$item = json_encode( $item->getIterator() );
}



function mp_extract_source( $img_tag ) {
	$matches = array();
	preg_match('/src="(.+?)"/', $img_tag, $matches);
	$image = $matches[1];
	return $image;
}

function get_article_permalink() {
	global $post;

	if( isset( $post ) )
		return get_permalink( $post->ID );
	else
		return "";
}

function sidebar_load_next_articles() {
	check_ajax_referer( 'load_more', 'mp_nonce' );

	$type = ( isset( $_GET[ 'type' ] ) ) ? $_GET[ 'type' ] : 'recent';
	$offset = ( isset( $_GET[ 'offset' ] ) ) ? $_GET[ 'offset' ] : 0;
	$post_id = ( isset( $_GET[ 'post_id' ] ) ? $_GET[ 'post_id' ] : FALSE );

	$args = array();
	if( $post_id ) {
		$args[ 'post_id' ] = $post_id;
	}

	$result = sidebar_filter_articles_list( $type, $offset, $args );
	array_walk( $result[ 'result' ], 'convert_to_json' );

	wp_send_json_success( $result );
}

function mp_minimum_file_upload_size_check( $file )
{
	// Mime type with dimensions, check to exit earlier
	$mimes = array( 'image/jpeg', 'image/png', 'image/gif' );

	if( !in_array( $file['type'], $mimes ) )
		return $file;

	$img = getimagesize( $file['tmp_name'] );
	$minimum = array( 'width' => 1440, 'height' => 960 );

	if ( $img[0] < $minimum['width'] )
		$file['error'] =
		'Image too small. Minimum width is '
		. $minimum['width']
		. 'px. Uploaded image width is '
		. $img[0] . 'px';

	elseif ( $img[1] < $minimum['height'] )
		$file['error'] =
		'Image too small. Minimum height is '
		. $minimum['height']
		. 'px. Uploaded image height is '
		. $img[1] . 'px';

	return $file;
}

function mp_get_search_results( $query ) {
	$page = (get_query_var('page')) ? get_query_var('page') : 1;

	$dataSource = new DataSource();

	$search_results = $dataSource->search( $query, $page );

	return $search_results;
}

function mp_get_team_members() {
	$dataSource = new DataSource();

	$team_members = $dataSource->team_members();
	return $team_members;
}

function is_sharing_enabled() {
	return Constants::SHARING_ENABLED;
}


function facebook_meta_tags( $meta_tags ) {
	$meta_tags = FacebookMetaTags::update_tags( $meta_tags );
	return $meta_tags;
}

function rewrite_urls() {
 	global $wp_rewrite;
    $author_slug = 'bio'; // change slug name
    $wp_rewrite->author_base = $author_slug;

	add_rewrite_rule( '^topic/([^/]+?)/([^/]+?)/?$', 'index.php/topi?category=$matches[1]&subcategory=$matches[2]', 'top' );
	add_rewrite_rule( '^topic/([^/]*)/?$', 'index.php?category=$matches[1]', 'top' );
	add_rewrite_rule( '^bookmarks/?$', 'index.php?pagename=profile&view=bookmarks', 'top' );
	add_rewrite_rule( '^bookmarks/([^/]+)/?$', 'index.php?pagename=profile&view=bookmarks&sort=$matches[1]', 'top' );
	add_rewrite_rule( '^recommended-readings/?$', 'index.php?pagename=profile&view=recommended-readings', 'top' );
	add_rewrite_rule( '^recommended-readings/([^/]+)/?$', 'index.php?pagename=profile&view=recommended-readings&sort=$matches[1]', 'top' );
	add_rewrite_rule( 'logout.*', '/wp-login.php?action=logout', 'top' );
}

function seasonal_tag_rewrite_rules( $rewrite_rules ) {
	$result = array();

	$seasonal_sub_tags = mp_get_seasonal_sub_tags();
	$seasonal_tags = array_keys( $seasonal_sub_tags );

	foreach ($rewrite_rules as $key => $value) {

		foreach ($seasonal_tags as $ind => $tag) {
			if( strpos( $key, $tag ) !== False ) {
				unset($seasonal_tags[$ind]);
				$sub_tags = array();

				foreach ($seasonal_sub_tags[$tag] as $sub_tag) {
					if( $sub_tag->slug !== RAMADAN_SERIES_TAG_SLUG )
						$sub_tags[] = $sub_tag->slug;
				}

				$sub_tags = implode( '|', $sub_tags );

				if( !empty( $sub_tags ) ) {

					$key_add = "($tag)/($sub_tags)/?$";
					$value_add = 'index.php?seasonal=$matches[1]&sub_tag=$matches[2]';

					$result[$key_add] = $value_add;

					if( $tag === RAMADANIYAT_TAG_SLUG ) {
						$result = apply_filters( 'ramadaniyat-tag-rewrite', $result );
					} else if( $tag === TV_SERIES_TAG_SLUG ) {
						$result = apply_filters( 'tv-series-tag-rewrite', $result );
					}

				}
			}
		}

		$result[$key] = $value;
	}

	mp_log( $result );

	return $result;
}

function ramadaniyat_tag_rewrite( $rewrite ) {
	$tag_slug = RAMADANIYAT_TAG_SLUG;
	$sub_tag_slug = RAMADAN_SERIES_TAG_SLUG;

	$ramadan_series_sub_tags = mp_get_sub_tags( 'seasonal', get_term_by( 'slug', RAMADAN_SERIES_TAG_SLUG, 'seasonal' ) );

	$key_add = "($tag_slug)/($sub_tag_slug)/?$";
	$value_add = "index.php?seasonal=$sub_tag_slug";

	$rewrite[$key_add] = $value_add;

	$rs_sub_tags = array();
	foreach ($ramadan_series_sub_tags as $rs_sub_tag) {
		$rs_sub_tags[] = $rs_sub_tag->slug;
	}

	$rs_sub_tags_slug = implode( '|', $rs_sub_tags );

	$key_add = "($tag_slug)/($sub_tag_slug)/($rs_sub_tags_slug)/?$";
	$value_add = "index.php?seasonal=$sub_tag_slug" . '&series=$matches[3]';

	$rewrite[$key_add] = $value_add;

	return $rewrite;
}

function tv_series_tag_rewrite( $rewrite ) {
	$tag_slug = TV_SERIES_TAG_SLUG;

	$tv_series_sub_tags = mp_get_sub_tags( 'seasonal', get_term_by( 'slug', TV_SERIES_TAG_SLUG, 'seasonal' ) );

	$tv_sub_tags = array();
	foreach ($tv_series_sub_tags as $tv_sub_tag) {
		$tv_sub_tags[] = $tv_sub_tag->slug;
	}

	$tv_sub_tags_slug = implode( '|', $tv_sub_tags );

	$key_add = "($tag_slug)/($tv_sub_tags_slug)/?$";
	$value_add = "index.php?seasonal=$tag_slug" . '&series=$matches[2]';

	$rewrite[$key_add] = $value_add;

	return $rewrite;
}

function mp_get_link( $view, $filter ) {
	if( $filter == 'recent' ) {
		$filter = '';
	}

	return home_url( $view . '/' . $filter );
}

function redirect_non_users_from_wp_admin() {
	$file = basename($_SERVER['PHP_SELF']);
	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '';
	$query_string = isset( $_SERVER['QUERY_STRING'] ) ?  $_SERVER['QUERY_STRING'] : '';

	$is_wp_login = $file == 'wp-login.php';
	$is_post = $method == 'POST';
	$is_logout = ( strpos( $query_string, 'action=logout' ) !== FALSE );
	$is_register_post_request = $is_post && ( strpos( $query_string, 'action=register' ) !== FALSE );

	if( $is_wp_login && ( $is_register_post_request || !( $is_post || $is_logout ) ) ) {
		wp_redirect( home_url(), '301' );
		exit;
	}

	if ( is_admin() && !current_user_can('edit_posts') && $file != 'admin-ajax.php' ) {
		wp_redirect( home_url(), '301' );
		exit;
	}
}

function facebook_locale( $locale ) {
	return 'ar_AR';
}

function get_article_header_image( $post_id, $size = 'desktop' ) {
	$image_attachment_id = get_post_meta( $post_id, 'image-attachment-id', true );

	$resized_images = get_post_meta( $image_attachment_id, 'resized_images', true );
	$url = '';
	if( is_array( $resized_images ) && !empty( $resized_images ) && isset( $resized_images[ 'desktop' ] ) ) {
		$url = $resized_images[ $size ]['url'];
	} else {
		$url = get_post_meta( $post_id, 'image', true );
	}

	$url = Utilities::make_relative( $url );

	return $url;
}

function kasra_logo_header( $var ) {
	return home_url() . '/wp-content/themes/menapost/images/kasra-facebook-logo.jpg';
}

function get_home_category_url( $slug ) {
	if( $slug == "All" ) {
		return home_url();
	}
	if( in_array( $slug, array( RAMADANIYAT_TAG_SLUG, TV_SERIES_TAG_SLUG ) ) ) {
		return home_url( $slug );
	}

	return home_url( '/topic/' . $slug );
}

function mp_log_facebook_response() {

	file_put_contents( WP_CONTENT_DIR . '/fb-log.txt', print_r($_POST, true), FILE_APPEND );
}

function autoplay_youtube_oembed($html, $url, $args) {

	if(strpos($html, "youtube") !== False) {
		$html = substr($html, 0, strpos($html, "feature=oembed")) . "enablejsapi=1&origin=" . home_url() . substr($html, strpos($html, "feature=oembed") + 14);
	}

	mp_log($html);
	return $html;
}

/**
 * Converts the REQUEST_URI to lower case if the URL starts with tv-series or ramadan series tag.
 * Fixes a bug in chrome, when you copy the url and paste in new window is changes the case of arabic slug
 * encoded value.
 */
function lower_case_seasonal_url() {
	$request_uri = $_SERVER['REQUEST_URI'];

	if( strpos( $request_uri, TV_SERIES_TAG_SLUG ) !== FALSE || strpos( $request_uri, RAMADAN_SERIES_TAG_SLUG ) !== FALSE ) {
		$_SERVER['REQUEST_URI'] =  strtolower( $request_uri );
	}
}

/**
 * Adds the Facebook Custom Audience Pixel script to wp_head
 */
function facebook_custom_audience_script() {
	echo <<<END
<script>(function() {
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) {
var fbds = document.createElement('script');
fbds.async = true;
fbds.src = '//connect.facebook.net/en_US/fbds.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(fbds, s);
_fbq.loaded = true;
}
_fbq.push(['addPixelId', '284131935106690']);
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=284131935106690&amp;ev=PixelInitialized" /></noscript>
END;

}


add_action( 'init', 'redirect_non_users_from_wp_admin' );

add_action( 'init','rewrite_urls' );
add_filter( 'seasonal_rewrite_rules', 'seasonal_tag_rewrite_rules', 100 );

add_action('init', 'lower_case_seasonal_url');

add_action( 'wp_ajax_log_facebook_response', 'mp_log_facebook_response' );
add_action( 'wp_ajax_nopriv_log_facebook_response', 'mp_log_facebook_response' );

add_action( 'wp_ajax_mp_sidebar_load_next', 'sidebar_load_next_articles');
add_action( 'wp_ajax_nopriv_mp_sidebar_load_next', 'sidebar_load_next_articles');

add_action( 'wp_ajax_mp_home_load_more', array( 'MPLoadmore', 'home_load_more_articles' ) );
add_action( 'wp_ajax_nopriv_mp_home_load_more', array( 'MPLoadmore', 'home_load_more_articles' ) );

add_action( 'wp_ajax_mp_profile_load_more', array( 'MPLoadmore', 'profile_load_more_articles' ) );
add_action( 'wp_ajax_nopriv_mp_profile_load_more', array( 'MPLoadmore', 'profile_load_more_articles' ) );

add_action( 'wp_ajax_mp_mood-landing_load_more', array( 'MPLoadmore', 'mood_landing_load_more_articles' ) );
add_action( 'wp_ajax_nopriv_mp_mood-landing_load_more', array( 'MPLoadmore', 'mood_landing_load_more_articles' ) );

add_action( 'wp_ajax_mp_post-tag-landing_load_more', array( 'MPLoadmore', 'post_tag_landing_load_more_articles' ) );
add_action( 'wp_ajax_nopriv_mp_post-tag-landing_load_more', array( 'MPLoadmore', 'post_tag_landing_load_more_articles' ) );

add_action( 'wp_ajax_mp_author_load_more', array( 'MPLoadmore', 'author_load_more_articles' ) );
add_action( 'wp_ajax_nopriv_mp_author_load_more', array( 'MPLoadmore', 'author_load_more_articles' ) );

add_action( 'wp_ajax_mp_explore_load_more', array( 'MPLoadmore', 'explore_load_more_articles' ) );
add_action( 'wp_ajax_nopriv_mp_explore_load_more', array( 'MPLoadmore', 'explore_load_more_articles' ) );

add_filter( 'mp_get_avatar', array( 'MPUser', 'get_user_avatar' ) );
add_filter( 'get_article_permalink', 'get_article_permalink' );

add_filter( 'sharing_enabled', 'is_sharing_enabled' );

add_filter( 'fb_meta_tags', 'facebook_meta_tags' );
add_filter( 'fb_locale', 'facebook_locale' );

add_filter( 'get_article_header_image', 'get_article_header_image', 10, 2 );
add_filter( 'kasra_logo_header', 'kasra_logo_header' );

add_filter( 'ramadaniyat-tag-rewrite', 'ramadaniyat_tag_rewrite' );
add_filter( 'tv-series-tag-rewrite', 'tv_series_tag_rewrite' );

// Oembed fetch url filter, used to add autoplay parameter to youtube videos
add_filter('oembed_result', 'autoplay_youtube_oembed', 10, 3);

// Hook for adding Facebook Custom Audience pixel script in the head
add_action('wp_head', 'facebook_custom_audience_script', 100);
