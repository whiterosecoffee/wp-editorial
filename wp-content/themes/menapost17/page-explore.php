<?php

/*
 * Template Name: Explore Page
 */

get_header();

do_action( 'navbar' );

$selected_mood = get_query_var( 'mood' );
$selected_reading_time = get_query_var( 'reading-time' );
$filter = get_sort_filter();

$count = 0;
$result_articles = mp_get_explore_articles( $selected_mood, $selected_reading_time, $filter, $count ); 
$show_more_button = $count > Constants::EXPLORE_PAGE_ARTICLES_COUNT;
$show_grid = true;

?>
<header data-page="explore">
	<h1><?php _e( 'Discoveries crumb', 'menapost-theme' ); ?></h1>
</header>

<div data-article-mood="<?php echo $selected_mood; ?>" data-article-reading-time="<?php echo $selected_reading_time; ?>">
	<?php include( CHILD_DIR . '/mp_home_loop.php' ); ?>
</div>
<?php 

get_footer(); 
