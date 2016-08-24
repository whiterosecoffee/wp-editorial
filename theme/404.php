<?php
get_header();
do_action( 'navbar' );

//Active articles as those shown oin left panel on article detail page.
$active_articles = get_active_articles_list();
?>

    <!-- Error content -->
    <div class="jumbotron error-container">
        <div class="container">
          <!-- <div class="error-img">
                <img src="<?php //echo CHILD_URL . '/img/404.png'; ?>">
            </div> -->
        <h1><?php _e( 'Sorry .. we can not find the page you are looking for. The following topics impressed a lot of our readers, we hope you like it too.', 'menapost-theme' ); ?></h1>
            <div class="ra_Wrap">
                <?php for( $i=0; $i<3; $i++): ?>
                    <div class="ra_box">
                        <div class="ra_thumb">
                            <a href="<?php echo $active_articles[ 'result' ][$i]->get_permalink(); ?>">
                                 <img class="img-responsive" src="<?php echo $active_articles[ 'result' ][$i]->get_image( 'mobile' ); ?>" >
                            </a>
                        </div>
                        <div class="ra_description">
                            <a href="<?php echo $active_articles[ 'result' ][$i]->get_permalink(); ?>">
                                <p><?php echo $active_articles[ 'result' ][$i]->title; ?></p>
                            </a>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
<?php
/**
 * Loads genesis engine.
 */
get_footer();
