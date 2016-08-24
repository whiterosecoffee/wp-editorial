<?php

$sub_tags = mp_get_seasonal_sub_tags(); 

$selected_sub_tag = get_query_var( 'sub_tag' );

$filter = get_sort_filter();

$parent_tag = get_term_by( 'name', single_tag_title( '', FALSE ), 'seasonal');
$selected_sub_tag = get_term_by( 'slug', $selected_sub_tag, 'seasonal');

$selected_tag = $parent_tag->slug;

$count = 0;

if( $selected_sub_tag ) {
	$result_articles = mp_get_post_tag_articles( array( $parent_tag->term_id, $selected_sub_tag->term_id), $filter, $count ); 
} else {
	$result_articles = mp_get_post_tag_articles( $parent_tag->slug, $filter, $count ); 
}

$show_more_button = $count > Constants::POST_TAG_LANDING_PAGE_COUNT;

get_header();

do_action( 'navbar' );

$show_grid = FALSE;

if( $parent_tag->slug === RAMADANIYAT_TAG_SLUG )
	$show_grid = TRUE;

include( CHILD_DIR . '/taxonomy-seasonal-header.php' );

?>

<div class="nocontent" data-article-post-tag="<?php echo $parent_tag->slug; ?>" <?php if( $selected_sub_tag ) : ?> data-article-sub-post-tag="<?= $selected_sub_tag->slug ?>" <?php endif; ?>>
	<?php include( CHILD_DIR . '/pk_home_loop.php' ); ?>
</div>

<?php
get_footer();