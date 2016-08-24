<?php

/**
 * Template Name: Work With Us
 */

get_header();
the_post();
?>
<?php do_action('navbar'); ?>

<!-- Story content -->
<section class="our-story-container" data-page="work-with-us">
	<h1><?php the_title(); ?></h1>
				<ul class="share-btns">
                    <li class="fb-horizontal">
                        <a class="social-link" data-activity-name="facebook-share" href="<?php echo SocialSharing::get_facebook_share_link_for_page( get_the_title(), get_permalink() ); ?>" target="_blank">
                            <i class="icon-facebook-2 mp-icon-xxs fb"></i>
                            <span ><?php _e( 'Share', 'menapost-theme' ); ?></span>                 
                        </a>
                    </li>

                    <li class="twitter-horizontal">
                      <a class="social-link" data-activity-name="twitter" href="<?php echo SocialSharing::get_twitter_link_for_page( get_the_title(), get_permalink() ); ?>" target="_blank">
                        <i class="icon-twitter-2 mp-icon-xxs twitter"></i>
                        <span ><?php _e( 'Chirp', 'menapost-theme' ); ?></span>           
                    </a>
                </li>
            </ul>
	<?php the_content();  ?>
    <div class="hidden-xs" style="height: 130px;"></div>
</section>



<?php get_footer(); ?>