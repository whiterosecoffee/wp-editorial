<?php the_post(); ?>

<?php 
$header_image = $article->get_image();
$suggested_articles = $article->get_suggested_articles($article->id);

?> 

<?php do_action( 'navbar' ); ?>

<?php if( is_array( $header_image ) ) : ?>
<style type="text/css">

@media screen and ( min-width: 320px ) and ( max-width: 767px ) {
    .featured-cover-image {
        background-image: url('<?php echo esc_url( $header_image[ "mobile" ]["url"] ); ?>');
    }
    .featured-cover{height: 145px; }
}

@media screen and ( min-width: 768px ) and ( max-width: 921px ) {
    .featured-cover-image {
        background-image: url('<?php echo esc_url( $header_image[ "tablet" ]["url"] ); ?>');
    }
    .featured-cover{height: 320px;}
}

@media screen and ( min-width: 922px ) {
    .featured-cover-image {
        background-image: url('<?php echo esc_url( $header_image[ "desktop" ]["url"] ); ?>');
    }

    .featured-cover{height: 320px; }
}

</style>

<?php else: ?>

<style>

.featured-cover-image {
    background-image: url('<?php echo esc_url( $header_image ); ?>');
}

</style>

<?php endif;?>

<!-- Article Header
================================================== -->
<header class="featured-cover" data-page="article-detail">
            <div class="featured-cover-image"></div>
</header>

<div class="facebook-like">
    <h4 class="orange"><?= __( 'Like us on Facebook?', 'menapost-theme' ); ?><i id="remove-popup" class="icon-cancel-circled mp-icon-xs dark-gray pull-left"></i></h4>
    <?= SocialSharing::get_facebook_like_box(); ?>
</div>

<div class="container-fluid container-main-article">
    <!-- Article -->
    <article data-article-id="<?php echo $article->id; ?>" 
        data-author-id="<?php echo $article->author_id; ?>" 
        data-activity-update="true"
        data-activity-url="<?php echo $article->get_short_link(); ?>"
        data-activity-total="<?php echo $article->activity_value->get_total_count(); ?>"
        data-activity-comment="<?php echo $article->activity_value->comment; ?>"
        data-activity-facebook-like="<?php echo $article->activity_value->facebook_like; ?>"
        data-activity-facebook-share="<?php echo $article->activity_value->facebook_share; ?>"
        data-activity-twitter="<?php echo $article->activity_value->twitter; ?>"
        data-activity-googleplus="<?php echo $article->activity_value->googleplus; ?>"
        data-activity-bookmark="<?php echo $article->activity_value->bookmark; ?>"
        data-activity-full-url="<?php echo $article->get_permalink(); ?>"
        data-perma-link="<?php echo $article->get_short_link(); ?>">

        <?php if( $series = $article->is_series_article() ) : ?>
        <div class="series-link">
            <a href="<?= $article->get_series_permalink( $series ); ?>">
                <i class="icon-right-open orange mp-icon-xs"></i>
                <h4><?= $series->name; ?></h4>
            </a>
        </div>
        <?php endif; ?>
        <div class="article-heading">
            <h1><?php echo $article->title; ?></h1>
            <div class="article-teaser-bookmark-container">
                <span class="article-teaser-bookmark add-to-reading-list">
                                        <i class="icon-bookmark-empty mp-icon-sm dark "  data-action="reading-list" data-command="add" <?php echo do_shortcode( '[reading-list]' . $article->id . '[/reading-list]' ); ?>></i>
                                            <span class="reading-label"><?php _e( 'Add to reading list', 'menapost-theme' ); ?></span>
                                            <span class="reading-time"><?php echo $article->get_read_duration(); ?></span>
                                    </span>
                <?php if( $article->is_trending() ) : ?>
                    <div class="article-trending-indicator"><i class="icon-flash mp-icon-sm orange" title="<?php _e('Now refracted', 'menapost-theme'); ?>"></i></div>    
                <?php endif; ?>
            </div>
        </div>

        


        <!-- Article container -->
        <div class="blog-post-meta">
            <div class="article-horizontal-share">
                <div class="article activitybar-horizontal">
                    <ul>


                        <li class="fb-horizontal like hidden-xs">
                            <b><?php echo SocialSharing::get_facebook_like_code( $article ); ?></b>
                            <a class="social-link">
                                <i class="icon-thumbs-up-outline mp-icon-xxs fb"></i>
                            </a> 
                        </li>

                        <li class="fb-horizontal">
                            <a class="social-link" data-activity-name="facebook-share" href="<?php echo SocialSharing::get_facebook_share_link(); ?>" target="_blank">
                                <i class="icon-facebook-2 mp-icon-xxs fb"></i>
                                <span class="hidden-xs"><?php _e( 'Share', 'menapost-theme' ); ?></span>                 
                            </a>
                        </li>

                        <li class="twitter-horizontal">
                            <a class="social-link" data-activity-name="twitter" href="<?php echo SocialSharing::get_twitter_link( $article ); ?>" target="_blank">
                                <i class="icon-twitter-2 mp-icon-xxs twitter"></i>
                                <span class="hidden-xs"><?php _e( 'Chirp', 'menapost-theme' ); ?></span>           
                            </a>
                        </li>
                        <li class="whatsapp-horizontal" style="display: none;" data-ios-only="true">
                            <a class="social-link nopopup" href="<?php echo SocialSharing::get_whatsapp_link( $article ); ?>">
                                <i class="icon-whatsapp mp-icon-xs light"></i>           
                            </a>
                        </li>

                        <li class="activity-view">

                             <i class="icon-eye mp-icon-xs dark hidden-xs"></i>
                            <span data-page-views-indicator="update"><?php echo Utilities::make_k_count( $article->get_views() ); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="cite">
                <!-- Author Avatar -->
                <?php if( $article->has_multiple_authors() ) : ?>
                    <a href="<?= MPUrl::get_page_link( 'our-team' ); ?>" class="pull-right" title="<?php echo $article->get_merged_authors(); ?>"><i class="icon-kasra-emblem orange mp-icon-lg"></i></a>
                <?php else : ?>
                    <a href="<?php echo $article->get_author_page_link(); ?>" class="pull-right"><img src="<?= $article->get_author_avatar(); ?>" width="40" height="40"></a>
                <?php endif; ?>
                <div class="pull-right">
                    <h5>
                        <?php if( $article->has_multiple_authors() ) : ?>
                        <!-- Co-Authors -->
                            <a href="<?= MPUrl::get_page_link( 'our-team' ); ?>"><?php echo $article->get_merged_authors(); ?></a>
                        <?php else : ?>
                        <!-- Author Name -->
                            <a href="<?php echo $article->get_author_page_link(); ?>"><?php echo $article->author; ?></a>
                        <?php endif; ?>
                        <!-- Date -->
                        <span><?php echo $article->get_date(); ?></span>
                    </h5>

                </div>

            </div>
            <div class="clearfix"></div>

        </div>
        <div class="article-detail-container" >
           <!-- __Article content -->
           <div class="clearfix"></div>
           <div class="article-detail-content">

            <section class="article-content-section">

                <div class="mp-article-content">
                    <?php echo $article->get_embedded_content(); ?>
                    <hr>
                      <div class="pull-right shorturl">
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-8 input-group hidden-sm hidden-xs">
                        <?php
                            $short_link = $article->get_short_link();
                        ?>
                        <input type="hidden" id="copy-short-url" value="<?php echo $article->get_short_link(); ?>" />
                        <label type="text" class="form-control" id="label-copy-short-url"><?php echo $short_link; ?></label>
                        <span class="input-group-btn">
                            <button class="btn copy-button" data-clipboard-target="copy-short-url" type="button"><?php _e( 'Copy Short Url', 'menapost-theme' ); ?></button>
                        </span>
                        <!-- Suggest a correction -->
                        <div class="suggestion-btn">
                            <a id="suggest-edit" href="" class="btn btn-default" href="javascript:void(0);" data-toggle="modal" data-target="#suggest-modal">اقترح تعديل للموضوع</a>
                        </div>
                        </div><!-- /input-group -->
                            <div class="copyurl-mobile">
                                <label class="link-thread control-label hidden-lg hidden-md">رابط الموضوع</label>
                                <div class="form-control hidden-lg hidden-md mobile">
                                    <a href="<?php echo $article->get_short_link(); ?>" onClick="return false;"><?php echo $short_link; ?></a>
                                </div>
                            </div>
                        
                      </div><!-- /.col-lg-6 -->
                    
                    <div class="clearfix"></div>
                    <!-- Suggest a correction-mobile -->
                    <div class="suggestion-btn mobile hidden-md hidden-lg">
                        <a id="suggest-edit" href="" class="btn btn-default" href="javascript:void(0);" data-toggle="modal" data-target="#suggest-modal">اقترح تعديل للموضوع</a>
                    </div>  
                    <div class="article-tags">
                    
                        <!-- Article Tags -->
                        <?php foreach( $article->get_tags() as $tag ) : if( $tag->slug != 'featured' ) : ?>
                            <a class="label" href="<?php echo '/' . $tag->slug; ?>"><?php echo $tag->name; ?></a>
                        <?php endif; endforeach; ?>

                        <!-- Mood Tags -->
                        <?php foreach( $article->get_mood_tags() as $mood ) : ?>
                            <a class="label" href="<?php echo '/' . $mood->slug; ?>"><?php echo $mood->name; ?></a>
                        <?php endforeach; ?>

                    </div>
                    <br>
                    <?php

                        if (shortcode_exists('related_articles_shortcode')) {
                            echo do_shortcode("[related_articles_shortcode]"); 
                        }
                    ?>
                    <br>
                </div>
            </div>
            <div class="article activitybar-vertical hidden-xs">
                <ul>
                    <li class="fb-like-vertical">
                        <div><?= SocialSharing::get_facebook_like_code( $article, 'box_count' ); ?></div>    
                    </li>

                    <li>
                        <a class="social-link" data-activity-name="facebook-share" href="<?php echo SocialSharing::get_facebook_share_link(); ?>" target="_blank">
                           <div>
                               <div class="pluginCountBox"><span><?php echo Utilities::make_k_count( $article->activity_value->facebook_share ); ?></span></div>
                               <div class="pluginCountBoxNub"><span></span><i></i></div>
                               <div class="pluginButton"><span class="pluginButtonLabel"><?php _e( 'Share', 'menapost-theme' ); ?></span></div>
                           </div>
                        </a>
                    </li>

                    <li>
                        <a class="social-link" data-activity-name="twitter" href="<?php echo SocialSharing::get_twitter_link( $article ); ?>" target="_blank">           
                            <div>
                               <div class="pluginCountBox"><span><?php echo Utilities::make_k_count( $article->activity_value->twitter ); ?></span></div>
                               <div class="pluginCountBoxNub"><span></span><i></i></div>
                               <div class="tweet-btn"><i class="icon-twitter-2"></i><span class="label"><?php _e( 'Chirp', 'menapost-theme' ); ?></span></div>
                           </div>
                        </a>
                    </li>
                    <li>
                        <a href="#comments">
                            <div class="pluginCountBox"><span><?php echo Utilities::make_k_count( $article->activity_value->comment ); ?></span></div>
                            <div class="pluginCountBoxNub"><span></span><i></i></div>
                            <div class="comment-btn"><i class="icon-comment"></i><span class="label">تعليق</span></div>
                        </a>
                    </li>
                    <li class="activity-view">
                        <div class="pluginCountBox"><span data-page-views-indicator><?php echo Utilities::make_k_count( $article->get_views() ); ?></span></div>
                        <div class="pluginCountBoxNub"><span></span><i></i></div>
                        <div class="pageviews-btn"><i class="icon-eye"></i><!-- <span class="label">عرض</span> --></div>
                    </li>
                </ul>
            </div>
</section>


<div class="clearfix"></div>
<!-- __Article comments -->

<?php if( Constants::SHARING_ENABLED ): ?>
<footer class="article-commentsection">
   <?php comments_template(); ?> 
</footer>
<?php endif; ?>
</div>

<!-- /Article container -->

<script type="text/x-jquery-tmpl" id="micro-content-template">
    <div class="share-micro-content hidden-xs hidden-sm">
        <h6><?= _e( 'Image Video Share', 'menapost-theme' ); ?></h6>
        <a href="<?php echo SocialSharing::get_facebook_share_link( $article ); ?>"><i class=" icon-facebook-2 fb"></i></a>
        <a href="<?php echo SocialSharing::get_twitter_link( $article, true ); ?>"><i class=" icon-twitter-2 twitter"></i></a>
        <a href="<?php echo SocialSharing::get_google_plus_link( $article ); ?>"><i class=" icon-gplus g-plus"></i></a>
    </div>
</script>

</article>
<!-- /Article -->
<!-- Article sidebar -->
<?php do_action( 'sidebar' ); ?>
<!-- /Article sidebar -->

</div>