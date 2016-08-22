<?php do_action( 'navbar' ); ?>

<?php $article = $result_articles[0]; ?>
<?php
if( isset( $article ) ) : 
$featured_image = $article->get_image();

?>
<?php if( is_array( $featured_image ) ) : ?>
<style type="text/css">

@media screen and ( min-width: 320px ) and ( max-width: 767px ) {
    .featured-cover-image {
        background-image: url('<?php echo esc_url( $featured_image[ "mobile" ]["url"] ); ?>');
    }
}

@media screen and ( min-width: 768px ) and ( max-width: 921px ) {
    .featured-cover-image {
        background-image: url('<?php echo esc_url( $featured_image[ "tablet" ]["url"] ); ?>');
    }
}

@media screen and ( min-width: 922px ) {
    .featured-cover-image {
        background-image: url('<?php echo esc_url( $featured_image[ "desktop" ]["url"] ); ?>');
    }
}

</style>

<?php else: ?>

<style>

.featured-cover-image {
    background-image: url('<?php echo esc_url( $featured_image ); ?>');
}

</style>

<?php endif;?>


<!-- Featured Article
================================================== -->
<header class="featured-cover" data-page="home">
    <article data-article-id="<?php echo $article->id; ?>" data-perma-link="<?php echo $article->get_short_link(); ?>">
        <a href="<?php echo $article->get_permalink(); ?>" data-element="article-link">
            <div class="featured-cover-image"></div>
            <div class="featured-cover-content">
       
            <h1><?php echo $article->title; ?></h1>
      
            <span class="featured-cover-bookmark">
                <span class="reading-time"><?php echo $article->get_read_duration(); ?></span>
            </span>
        </div>
        </a>
        
        <div class="featured activitybar-vertical">
             <ul>
                <li>
                    <a class="social-link" data-activity-name="facebook-share" href="<?php echo SocialSharing::get_facebook_share_link(); ?>" target="_blank">
                         <i class="icon-fb-circle mp-icon-lg fb"></i>
                         <span><?php echo Utilities::make_k_count( $article->activity_value->get_total_facebook_count() ); ?></span>                 
                    </a>
                </li>

                 <li>
                    <a class="social-link" data-activity-name="twitter" href="<?php echo SocialSharing::get_twitter_link( $article ); ?>" target="_blank">
                        <i class="icon-twitter-circled-1 mp-icon-lg twitter"></i>
                        <span><?php echo Utilities::make_k_count( $article->activity_value->twitter ); ?></span>           
                     </a>
                </li>
                <li class="activity-view" title="<?php _e( 'Total Posts and comments', 'menapost-theme' ); ?>">
                    <a href="<?php echo $article->get_permalink(); ?>" class="activity-view" data-element="article-link">
                    <i class="icon-eye mp-icon-md dark"></i>
                    <span data-page-views-indicator="readonly"><?php echo Utilities::make_k_count( $article->get_views() ); ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </article>
</header>

<div class="hidden">
    <form name="ignore_me">
        <input type="hidden" id="page_is_dirty" name="page_is_dirty" value="0" />
    </form>
</div>

<?php endif; ?>