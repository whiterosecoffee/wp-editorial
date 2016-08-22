<?php 

$recent_articles      = get_recent_articles_list(); 
$trending_articles    = get_trending_articles_list();
$most_viewed_articles = get_most_viewed_articles_list();
$recommended_articles = get_recommended_articles_list();
$active_articles      = get_active_articles_list();

?>

<div class="nocontent">
    <aside class="sidebar-with-thumbnail article-sidebar">
        <div class="sidebar-filter">
            <ul class="nav nav-pills">
                <li class="active"><a href="#most-active" role="tab" data-toggle="tab" data-menu-type="active"  data-menu-selected="true"><?php _e( 'Most Active', 'menapost-theme' ); ?></a></li>
                <li><a href="#most-recent" role="tab" data-toggle="tab" data-menu-type="recent" ><?php _e( 'Most Recent', 'menapost-theme' ); ?></a></li>
            </ul>
        </div>
        <ul class="article-teaser tab-content">

        <!-- Most Recent Articles -->
        <div class="tab-pane" id="most-recent">
        <?php foreach( $recent_articles[ 'result' ] as $article) :  ?>
            <li class="article-teaser-item  col-sm-12" data-type="recent">
                <a href="<?php echo $article->get_permalink(); ?>">
                    <div class="teaser-content">            
                         
                        <div class="teaser-title">
                            <img src="<?php echo $article->get_image( 'mobile' ); ?>">
                            <h5><?php echo $article->title; ?></h5>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
        </div>

        <!-- Most Viewed Articles -->
        <?php foreach( $most_viewed_articles[ 'result' ] as $article) : ?>
            <li class="article-teaser-item  col-sm-12" data-type="most-viewed">
                <a href="<?php echo $article->get_permalink(); ?>">
                    <div class="teaser-content">            
                         
                        <div class="teaser-title">
                            <img src="<?php echo $article->get_image( 'mobile' ); ?>">
                            <h5><?php echo $article->title; ?></h5>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
        
            <!-- Trending Articles -->
        <?php if( $trending_articles ) : foreach( $trending_articles[ 'result' ] as $article) : ?>
            <li class="article-teaser-item col-sm-12" data-type="trending">
                <a href="<?php echo $article->get_permalink(); ?>">
                    <div class="teaser-content">            
                         
                        <div class="teaser-title">
                            <img src="<?php echo $article->get_image( 'mobile' ); ?>">
                            <h5><?php echo $article->title; ?></h5>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; endif; ?>

            <!-- Recommended Articles -->
        <?php foreach ( $recommended_articles[ 'result' ] as $article) : ?>
            <li class="article-teaser-item col-sm-12" data-type="recommended">
                <a href="<?php echo $article->get_permalink(); ?>">
                    <div class="teaser-content">                                 
                        <div class="teaser-title">
                            <img src="<?php echo $article->get_image( 'mobile' ); ?>">
                            <h5><?php echo $article->title; ?></h5>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>

            <!-- Active Articles -->
        <div class="tab-pane fade in active" id="most-active">
        <?php foreach ( $active_articles[ 'result' ] as $article) : ?>
            <li class="article-teaser-item col-sm-12" data-type="active">
                <a href="<?php echo $article->get_permalink(); ?>">
                    <div class="teaser-content">                                 
                        <div class="teaser-title">
                            <img src="<?php echo $article->get_image( 'mobile' ); ?>">
                            <h5><?php echo $article->title; ?></h5>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
        </div>
        </ul>
          <a class="btn btn-primary btn-medium vm-btn" data-action-type="refresh"><span><i class="icon-refresh"></i></span><?php _e( 'refresh', 'menapost-theme' ); ?></a>
    </aside>
</div>

<!-- Template for dynamically loaded sidebar items -->
<script type="text/x-jquery-tmpl" id="sidebar-item-template">
    <li class="article-teaser-item col-sm-12" data-type="${type}">
        <a href="${permalink}">
            <div class="teaser-content">                                 
                <div class="teaser-title">
                    <img src="${image}" title="${title}">
                    <h5>${title}</h5>
                </div>
            </div>
        </a>
    </li>
</script>