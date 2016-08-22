<?php
include_once( CHILD_DIR . '/inc/Series.php' );

$shows_list = Series::get_shows_list( TV_SERIES_TAG_SLUG );

$selected_sub_tag = get_term_by( 'slug', get_queried_object()->slug, 'seasonal');
$parent_tag = get_term_by( 'id', $selected_sub_tag->term_id, 'seasonal' );

$sub_tags = mp_get_seasonal_sub_tags(); 

$selected_tag = $parent_tag->slug;

$selected_show_slug = get_query_var( 'series' );

get_header();

do_action( 'navbar' );
?>

<?php if( !$selected_show_slug ) : ?>  

<div class="tv-series-header">
    <h1 class=""><?= $parent_tag->name; ?></h1>
</div>

<div data-article-post-tag="<?= $parent_tag->slug; ?>" data-shows-page data-article-sub-post-tag="<?= $selected_sub_tag->slug ?>" >
    <div id="article-teasers" class="container-fluid article-container">
        <ul class="container-main grid animated" id="article-list-grid-view">
            
			<?php foreach ($shows_list as $show) : ?>
            <!-- Article -->
            <li class="item">
                <article class="article-tile">
                    <header>
                        <div class="article-teaser-header">
                            <a class="mp-block" title="<?= $show->title; ?>" href="<?= $show->get_permalink(); ?>">
                                <img class="img-responsive lazy-load" alt="<?= $show->title; ?>" data-original="<?= $show->get_image(); ?>">
                            </a>
                        </div>
                    </header>
                </article>
            </li>
            <!-- /Article -->
        	<?php endforeach; ?>
        </ul>
    </div>
</div>

<?php else: 

    $count = 0;
    $filter = get_sort_filter();

    $selected_show = Series::get_show( TV_SERIES_TAG_SLUG, $selected_show_slug );

    $result_articles = mp_get_post_tag_articles( array( $parent_tag->term_id, $selected_show->term_id), $filter, $count ); 

    $show_more_button = $count > Constants::POST_TAG_LANDING_PAGE_COUNT;

    $show_grid = true;

    ?>


<header 
    class="tv-series-header" 
    data-page="post-tag-landing series" 
    data-article-post-tag="<?= $parent_tag->slug; ?>" 
    data-article-sub-post-tag="<?= $selected_show->slug ?>">
    
    <h1><?= $selected_show->title; ?></h1>
</header>

    <?php
    include( CHILD_DIR . '/mp_home_loop.php' );

endif; ?>

<?php
get_footer();