<?php

$mood = get_term_by( 'slug', get_query_var( 'term' ), 'mood');

$filter = get_sort_filter();
$count = 0;
$result_articles = mp_get_mood_articles( $mood->slug, $filter, $count ); 
$show_more_button = $count > Constants::MOOD_LANDING_PAGE_COUNT;

get_header();

do_action( 'navbar' );
?>

<header class="profile-header" data-page="mood-landing">

	<!-- Mood name -->
	<h1 class="profile-menu-right"><?php echo $mood->name; ?></h1>
	<!-- /Mood name -->

	
</header>
<div class="nocontent" data-article-mood="<?php echo $mood->slug; ?>">
	<?php include( CHILD_DIR . '/mp_home_loop.php' ); ?>
</div>

<?php
get_footer();