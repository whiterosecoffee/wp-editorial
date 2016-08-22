<?php if( $result_articles != NULL &&  count( $result_articles ) > 0 ) : ?>
    <div id="article-teasers" class="container-fluid article-container">
        <ul class="article-view list animated">
        <?php foreach( $result_articles as $article ) :  ?>
            <li class="col-md-12" id="post-<?php echo $article->id; ?>">
                <article data-article-id="<?php echo $article->id; ?>">
                    <a href="<?php echo $article->get_permalink(); ?>" class="article-tile">
                        <div class="article-overlay"></div>
                        <img class="teaser-img" src="<?php echo $article->get_image(); ?>">
                        <section class="article-inner">
                            <div class="article-title">
                                <h3><?php echo $article->title; ?></h3>
                                <h6><?php echo __( 'by:', 'menapost-theme' ) . ' ' . $article->author; ?></h6>
                                <p class="descp-snippet hidden-xs"><?php echo $article->get_excerpt(); ?></p>
                            </div>
                            <div class="article-commentview">
                                <span class="comment-bubble" data-action="comments-count" data-convert-numbers="arabic" data-post-url="<?php echo $article->get_permalink(); ?>">0</span>
                            </div>
                            <span class="article-date"><small data-convert-numbers="arabic"><?php echo $article->get_date(); ?></small></span>
                        </section>
                    </a>
                    <div class="article-like">
                        <span data-action="reading-list" data-command="add" data-complete="<?php echo $article->in_reading_list(); ?>" 
                                class="like-icon"></span>
                    </div>
                </article>
            </li>
        <?php endforeach; ?> 
        </ul>
    </div>
    <?php if( isset( $show_more_button ) && $show_more_button ): ?>
        <div class="align-center">
            <button id="view-more-button" class="btn btn-info" data-total-articles="<?php echo $count; ?>" data-article-filter="<?php echo get_sort_filter(); ?>"><?php _e( 'view more', 'menapost-theme' ); ?></button>
        </div>
    <?php endif; ?>

    <!-- Template -->
    <script type="text/x-jquery-tmpl" id="item-template">
            <li class="col-md-12" id="post-${id}">
                <article data-article-id="${id}">
                    <a href="${permalink}" class="article-tile">
                        <div class="article-overlay"></div>
                        <img class="teaser-img" src="${image}">
                        <section class="article-inner">
                            <div class="article-title">
                                <h3>${title}</h3>
                                <h6><?php echo __( 'by:', 'menapost-theme' ) . ' '; ?> ${author}</h6>
                                <p class="descp-snippet hidden-xs">${excerpt}</p>
                            </div>
                            <div class="article-commentview">
                                <span class="comment-bubble" data-action="comments-count" data-convert-numbers="arabic" data-post-url="${comment}">0</span>
                            </div>
                            <span class="article-date"><small data-convert-numbers="arabic">${date}</small></span>
                        </section>
                    </a>
                    <div class="article-like ">
                        <span data-action="reading-list" data-command="add" data-complete="${in_reading_list}" 
                                class="like-icon"></span>
                    </div>
                </article>
            </li>
    </script>    
    <!-- /Template -->

<?php
else : //* if no posts exist
    do_action( 'mp_loop_else' );
endif;
?> 