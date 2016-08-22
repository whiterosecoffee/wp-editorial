<?php
include_once( CHILD_DIR . '/inc/Series.php' );

$shows_list = Series::get_shows_list( RAMADAN_SERIES_TAG_SLUG );

$selected_sub_tag = get_term_by( 'name', single_tag_title( '', FALSE ), 'seasonal');
$parent_tag = get_term_by( 'id', $selected_sub_tag->parent, 'seasonal' );

$sub_tags = mp_get_seasonal_sub_tags(); 

$selected_tag = $parent_tag->slug;

$selected_show_slug = get_query_var( 'series' );

get_header();

do_action( 'navbar' );

if( !$selected_show_slug ) 
    include( CHILD_DIR . '/taxonomy-seasonal-header.php' );
?>

<?php if( !$selected_show_slug ) : ?>

<div data-article-post-tag="<?= $parent_tag->slug; ?>" data-article-sub-post-tag="<?= $selected_sub_tag->slug ?>" >
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

    $selected_show = Series::get_show( RAMADAN_SERIES_TAG_SLUG, $selected_show_slug );

    $result_articles = mp_get_post_tag_articles( array( $parent_tag->term_id, $selected_sub_tag->term_id, $selected_show->term_id), $filter, $count ); 

    $show_more_button = $count > Constants::POST_TAG_LANDING_PAGE_COUNT;

    $show_grid = true;

    ?>

<style>
    @media screen and ( min-width: 320px ) and ( max-width: 479px ) {
        .featured-cover-image {
            background-image: url('<?php echo esc_url( $selected_show->get_image() ); ?>');
        }
        .featured-cover{height: 145px; }
    }

    @media screen and ( min-width: 480px ) and ( max-width: 767px ) {
        .featured-cover-image {
            background-image: url('<?php echo esc_url( $selected_show->get_image("header-mobile-lg") ); ?>');
        }
        .featured-cover{height: 145px; }
    }

    @media screen and ( min-width: 768px ) and ( max-width: 921px ) {
        .featured-cover-image {
            background-image: url('<?php echo esc_url( $selected_show->get_image("header-tablet") ); ?>');
        }
        .featured-cover{height: 320px;}
    }

    @media screen and ( min-width: 922px ) {
        .featured-cover-image {
            background-image: url('<?php echo esc_url( $selected_show->get_image("header") ); ?>');
        }

        .featured-cover{height: 320px; }
    }
</style>
<header class="featured-cover" 
    data-page="post-tag-landing series" 
    data-article-post-tag="<?= $parent_tag->slug; ?>" 
    data-article-sub-post-tag="<?= $selected_sub_tag->slug ?>"
    data-series-sub-tag="<?= $selected_show->slug ?>">
    
    <div class="featured-cover-image"></div>
</header>

    <?php
    include( CHILD_DIR . '/pk_home_loop.php' );

endif; ?>

<?php
get_footer();