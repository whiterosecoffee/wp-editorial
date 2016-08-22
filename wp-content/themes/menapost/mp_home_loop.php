<?php if( $result_articles != NULL &&  count( $result_articles ) > 0 ) : ?>
    <div id="article-teasers" class="container-fluid article-container">
        <ul class="container-main <?php echo ( is_home() || ( isset( $show_grid ) && $show_grid )   ? 'grid' : 'list' ); ?> animated" id="article-list-grid-view">
            <?php foreach( $result_articles as $article ) :  
                $attached_tags = array();
                $fetched_tags = $article->get_tags();
                foreach ($fetched_tags as $fetched_tag) {
                    $attached_tags[] = $fetched_tag->slug;
                    $attached_tag = implode(" ", $attached_tags);
                }
                ?>
                <!-- Article -->
                <li class="item <?php echo $attached_tag; ?>" id="article-<?php echo $article->id; ?>">
                    <article class="article-tile" data-perma-link="<?php echo $article->get_short_link(); ?>" data-article-id="<?php echo $article->id; ?>" data-category="<?php echo $article->category; ?>">
                        <header>
                            <div class="article-teaser-header">
                                    <a class="mp-block" data-element="article-link" href="<?php echo $article->get_permalink(); ?>">
                                    <img class="img-responsive lazy-load" data-original="<?php echo $article->get_image( 'polaroid' ); ?>">
                                    <?php
                                        if($article->if_videos_tag_in_article()){
                                            echo '<i class="icon-play-circle play-icon light"></i>';
                                        }
                                    ?>
                                     </a>
                                <div class="cite">
                                    <?php if( $article->has_multiple_authors() ) : ?>
                                    <a href="<?= MPUrl::get_page_link( 'our-team' ); ?>" class="pull-right" title="<?php echo $article->get_merged_authors(); ?>"><i style="font-size: 30px; margin-right: 10px; line-height:40px;" class="icon-kasra-emblem orange mp-icon-lg"></i></a>
                                    <?php else : ?>
                                    <a href="<?php echo $article->get_author_page_link(); ?>" class="pull-right"><img class="lazy-load" data-original="<?= $article->get_author_avatar(); ?>" width="40" height="40"></a>
                                    <?php endif; ?>
                                    <div class="pull-right">
                                        <h5>
                                            <?php if( $article->has_multiple_authors() ) : ?>
                                            <a href="<?= MPUrl::get_page_link( 'our-team' ); ?>"><?php _e( 'Kasra Authors', 'menapost-theme' ); ?></a>
                                            <?php else : ?>
                                            <a href="<?php echo $article->get_author_page_link(); ?>"><?php echo $article->author; ?></a>
                                            <?php endif; ?>
                                            <span><?php echo $article->get_date(); ?></span>
                                        </h5>

                                    </div>
                                    <?php if( $article->is_trending() ) : ?>
                                    <div class="pull-left article-trending-indicator"><i class="icon-flash orange" style="font-size:24px;" title="<?php _e( 'Now refracted', 'menapost-theme' ); ?>"></i></div>    
                                    <?php endif; ?>
                                </div>
                            </div>

                        </header>
                        <footer class="article-content">
                            <div class="title">
                                <a data-element="article-link" href="<?php echo $article->get_permalink(); ?>">
                                    <h1><?php echo $article->title; ?></h1>
                                </a>
                            </div>
                            <div class="article-activity-container">
                                <div class="article-activity">
                                    <div id="activitybtn" class="activity-badge" title="<?php _e( 'Total Posts and comments', 'menapost-theme' ); ?>">
                                        <a data-element="article-link" href="<?php echo $article->get_permalink(); ?>" class="activity-view">
                                        <i class="icon-eye mp-icon-sm dark"></i>
                                            <span data-page-views-indicator="readonly"><?php echo Utilities::make_k_count( $article->get_views() ); ?></span>
                                        </a>
                                    </div>
                                    <div class="activitybar-horizontal">
                                       
                               
                                       <a class="social-link twitter-horizontal activity-badge" data-activity-name="twitter" href="<?php echo SocialSharing::get_twitter_link( $article ); ?>" target="_blank">
                                    <i class="icon-twitter-2 mp-icon-xs twitter"></i>
                                    <span class="hidden-xs"><?php echo Utilities::make_k_count( $article->activity_value->twitter ); ?></span>           
                                        </a>
                                   

                                    
                                        <a class="social-link fb-horizontal activity-badge" data-activity-name="facebook-share" href="<?php echo SocialSharing::get_facebook_share_link(); ?>" target="_blank">
                                        <i class="icon-facebook-2 mp-icon-xs fb"></i>
                                        <span class="hidden-xs"><?php echo Utilities::make_k_count( $article->activity_value->get_total_facebook_count() ); ?></span>                 
                                        </a>
                                  

                                    </div>
                                </div>

                                <div class="article-teaser-bookmark-container without-button">
                                        <span class="article-teaser-bookmark">
                                                <span class="reading-time"><?php echo $article->get_read_duration(); ?></span>
                                        </span>
                                </div>
                                <div class="article-teaser-bookmark-container with-button">
                                        <span class="article-teaser-bookmark add-to-reading-list">
                                            <i class="icon-bookmark-empty mp-icon-sm dark "  data-action="reading-list" data-command="add" <?php echo do_shortcode( '[reading-list]' . $article->id . '[/reading-list]' ); ?>></i>
                                                <span class="reading-label"><?php _e( 'Add to reading list', 'menapost-theme' ); ?></span>
                                                <span class="reading-time"><?php echo $article->get_read_duration(); ?></span>
                                        </span>
                                </div>
                            </div>
                        </footer>
                    </article>
                </li>
                <!-- /Article -->
            <?php endforeach; ?> 
        </ul>
    </div>
    <?php if( isset( $show_more_button ) && $show_more_button ): ?>
        <div class="align-center" id="view-more-button-container">
            <span id="view-more-button"  data-total-articles="<?php echo $count; ?>" <?php if( !empty( $selected_subcategory ) ) echo 'data-sub-category="' . $selected_subcategory . '"'; ?> data-article-filter="<?php echo get_sort_filter(); ?>"><i class="icon-loading animate-spin orange mp-icon-lg"></i></span>
        </div>
    <?php endif; ?>

        <!-- Template -->
    <script type="text/x-jquery-tmpl" id="item-template">
           <!-- Article -->
                <li class="item ${tag_string} animated fadeInDown" id="article-${id}">
                    <article data-title="${title}" class="article-tile" data-perma-link="${short_link}" data-article-id="${id}" data-category="${category}">
                        <header>
                            <div class="article-teaser-header">
                                <a data-element="article-link" href="${permalink}">
                                <img class="img-responsive lazy-load" data-original="${image}">
                                {{if is_video_article}}
                                    <i class="icon-play-circle play-icon light"></i>
                                {{/if}}
                                </a>
                                <div class="cite">
                                    {{if has_multiple_authors}}
                                    <a href="<?= MPUrl::get_page_link( 'our-team' ); ?>" class="pull-right" title="${merged_authors}"><i style="font-size: 30px; margin-right: 10px; line-height:40px;" class="icon-kasra-emblem orange mp-icon-lg"></i></a>
                                    {{else}}
                                    <a href="${author_page_link}" class="pull-right"><img class="lazy-load" data-original="${author_avatar}" width="40" height="40"></a>
                                    {{/if}}
                                    <div class="pull-right">
                                        <h5>
                                            {{if has_multiple_authors}}
                                            <a href="<?= MPUrl::get_page_link( 'our-team' ); ?>"><?php _e( 'Kasra Authors', 'menapost-theme' ); ?></a>
                                            {{else}}
                                            <a href="${author_page_link}">${author}</a>
                                            {{/if}}
                                            <span>${date}</span>
                                        </h5>

                                    </div>
                                    {{if trending}}
                                    <div class="pull-left article-trending-indicator"><i class="icon-flash orange" style="font-size:24px;" title="<?php _e( 'Now refracted', 'menapost-theme' ); ?>"></i></div>    
                                    {{/if}}   
                                </div>
                            </div>

                        </header>
                        <footer class="article-content">
                            <div class="title">
                                <a data-element="article-link" href="${permalink}">
                                    <h1>${title}</h1>
                                </a>
                            </div>
                            <div class="article-activity-container">
                                <div class="article-activity">
                                    <div id="activitybtn" class="activity-badge" title="<?php _e( 'Total Posts and comments', 'menapost-theme' ); ?>">
                                        <a data-element="article-link" href="${permalink}" class="activity-view">
                                            <i class="icon-eye mp-icon-sm dark"></i>
                                            <span>${total_views}</span>
                                        </a>
                                    </div>
                                    <div class="activitybar-horizontal">
                                       
                               
                                       <a class="social-link twitter-horizontal activity-badge" data-activity-name="twitter" href="<?php echo SocialSharing::get_twitter_link( '' ); ?>" target="_blank">
                                    <i class="icon-twitter-2 mp-icon-xs twitter"></i>
                                    <span class="hidden-xs">${twitter}</span>           
                                        </a>
                                   

                                    
                                        <a class="social-link fb-horizontal activity-badge" data-activity-name="facebook-share" href="<?php echo SocialSharing::get_facebook_share_link(); ?>" target="_blank">
                                    <i class="icon-facebook-2 mp-icon-xs fb"></i>
                                    <span class="hidden-xs">${facebook_total}</span>                 
                                        </a>
                                  

                                    </div>
                                </div>

                                <div class="article-teaser-bookmark-container with-button">
                                        <span class="article-teaser-bookmark add-to-reading-list">
                                            <i class="icon-bookmark-empty mp-icon-sm dark" data-action="reading-list" data-command="add" data-complete="${in_reading_list}"></i>
                                            
                                            <span class="reading-label"><?php _e( 'Add to reading list', 'menapost-theme' ); ?></span>
                                            <span class="reading-time">${read_duration}</span>
                                        </span>
                                    </div>
                                  <div class="article-teaser-bookmark-container without-button">
                                        <span class="article-teaser-bookmark">
                                            <span class="reading-time">${read_duration}</span>
                                        </span>
                                    </div>
                            </div>
                        </footer>
                    </article>
                </li>
                <!-- /Article -->
    </script>    
    <!-- /Template -->

    <?php
else : //* if no posts exist 
?>


<?php 
$view = isset( $view ) ? $view : 'general'; 
do_action( 'mp_loop_else', $view ); 
?>

<?php endif; ?> 
