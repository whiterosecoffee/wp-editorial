<?php
$categories = mp_get_categories(); 
$article_type_tags = mp_get_article_type_tags(); 

$seasonal_tags = mp_get_seasonal_tags( array( 'world-cup', 'ramadaniyat' ) ); 
$seasonal_sub_tags = mp_get_seasonal_sub_tags(); 

$merged_tags = array_merge($seasonal_tags, $article_type_tags); 

$selected_tag = mp_get_selected_category(); 
$selected_sub_tag = mp_get_selected_sub_category(); 

$mp_current_user = False;
if( is_user_logged_in() )
    $mp_current_user = MPUser::get_current_user();

?>
<!-- Navbar -->

<!-- Off-canvas Navbar -->
 <nav class="off-canvas">
        <ul class="list-unstyled main-menu">
          
          <!--Include your navigation here-->
          <li class="text-right"><a href="#" id="nav-close"><span class="orange">أكسرها و أنشرها </span><i class="icon-cancel-circled 
          mp-icon-xs"></i></a></li>
          <li class="hidden-md hidden-lg nav-search">
          <div  class="search-form">
                    <form action="<?php echo home_url( '/' ); ?>" method="GET" role="search" >
                        <div>
                            <input type="text" value="" autocomplete="off" placeholder="<?php _e( 'I\'m looking for ...', 'menapost-theme' ); ?>" name="s" id="s" />
                        </div>
                    </form>
          </div>
          </li>

        <?php if( !is_user_logged_in() ): ?>
                    
                      <li>
                      <ul class="list-unstyled nav-sub" style="display:block !important;" data-query-key="category">
                        <li ><a data-query-default="true" href="<?php echo get_home_category_url( "All" ); ?>" data-query-value="all"><?php _e( 'All', 'menapost-theme' ); ?><span class="icon"></span></a></li>
                        <?php foreach ( $merged_tags as $tag ) : ?>
                        <li ><a href="<?php echo get_home_category_url( $tag->slug ); ?>" data-query-value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?><span class="icon"></span></a></li>
                        <?php if(False && $tag->slug == $selected_tag && array_key_exists( $selected_tag, $seasonal_sub_tags ) ) : ?>   
                            <?php foreach( $seasonal_sub_tags[$selected_tag] as $key => $value ): ?> 
                                <li class="sub-tag"><a class="<?php if( $selected_sub_tag == $value->slug ) echo 'selected'; ?>" href="<?php echo get_sub_category_url( $selected_tag, $value->slug ); ?>" data-query-value="<?php echo $value->slug; ?>"><?php echo $value->name; ?><span class="icon"></span></a></li>    
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                  </li>
                  <li>
                      <ul class="list-unstyled nav-sub" style="display:block !important;">
                        <li ><a href="<?= SocialSharing::get_facebook_page(); ?>" target="_blank">فيسبوك<span class="icon"></span></a></li>
                        <li ><a href="<?= SocialSharing::get_twitter_page(); ?>" target="_blank">تويتر<span class="icon"></span></a></li>
                        <li ><a href="<?= SocialSharing::get_google_plus_page(); ?>" target="_blank">غوغل بلس<span class="icon"></span></a></li>
                    </ul>
                  </li>
                <?php else: ?>
                    <li class="offcanvas-profile"><div style="background: url(<?= $mp_current_user->get_avatar(); ?>) no-repeat;"></div><a role="menuitem" tabindex="-1" href="<?= MPUrl::get_page_link( 'profile' ); ?>"><?= $mp_current_user->get_nickname(); ?></a></li>
                    <li>
                        <ul class="list-unstyled nav-sub" style="display:block !important;"><!-- <li role="presentation"><a role="menuitem" tabindex="-1"><?php // _e( 'My Drafts', 'menapost-theme' ); ?></a></li> -->
                         <li  ><a role="menuitem" tabindex="-1" href="<?php echo mp_get_link( 'bookmarks', 'recent' ); ?>"><?php _e( 'My Reading List', 'menapost-theme' ); ?><span class="icon"></span></a></li>
                         <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php // echo mp_get_link( 'recommended-readings', 'recent' ); ?>"><?php // _e( 'Recommended Readings', 'menapost-theme' ); ?></a></li> -->
                         
                         <?php if( current_user_can( 'edit_posts' ) ) : ?>
                         <li ><a role="menuitem" tabindex="-1" href="<?php echo admin_url('index.php'); ?>"><?php _e( 'Admin Panel', 'menapost-theme' ); ?><span class="icon"></span></a></li>
                         <?php endif; ?>   
                     </ul>
                 </li>
             
          <li>
              <ul class="list-unstyled nav-sub" style="display:block !important;" data-query-key="category">
                <li><a data-query-default="true" href="<?php echo get_home_category_url( "All" ); ?>" data-query-value="all"><?php _e( 'All', 'menapost-theme' ); ?><span class="icon"></span></a></li>
                <?php foreach ( $merged_tags as $tag ) : ?>
                    <li ><a href="<?php echo get_home_category_url( $tag->slug ); ?>" data-query-value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?><span class="icon"></span></a></li>
                <?php if(False && $tag->slug == $selected_tag && array_key_exists( $selected_tag, $seasonal_sub_tags ) ) : ?>   
                    <?php foreach( $seasonal_sub_tags[$selected_tag] as $key => $value ): ?> 
                        <li class="sub-tag"><a class="<?php if( $selected_sub_tag == $value->slug ) echo 'selected'; ?>" href="<?php echo get_sub_category_url( $selected_tag, $value->slug ); ?>" data-query-value="<?php echo $value->slug; ?>"><?php echo $value->name; ?><span class="icon"></span></a></li>    
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php endforeach; ?>
                <!-- Kasra Eid Greetings -->
                <?php // if(shortcode_exists( 'kasra-eid-greetings' )): ?>
                <!-- <li><a href="<?= home_url('عيد'); ?>"><?php _e( 'Create a Greetings Card', 'menapost-theme' ); ?><span class="icon"></span></a></li> -->
                <?php // endif; ?>
                <!-- /Kasra Eid Greetings -->
            </ul>
          </li>
          <li>
              <ul class="list-unstyled nav-sub" style="display:block !important;">
                <li><a href="https://www.facebook.com/KasraOnline" target="_blank">فيسبوك<span class="icon"></span></a></li>
                <li ><a href="https://twitter.com/<?php echo get_option( 'twitter_handle'); ?>" target="_blank">تويتر<span class="icon"></span></a></li>
                <li ><a href="https://plus.google.com/+KasraCoOnline" target="_blank">غوغل بلس<span class="icon"></span></a></li>
            </ul>
          </li>
          <li><a href="<?php echo wp_logout_url(); ?>"><?php _e( 'Logout', 'menapost-theme' ); ?><span class="icon"></span></a></li>
          <?php endif; ?>
          <!-- <li>
            <a href="#">Dropdown</a>
            <ul class="list-unstyled">
                <li class="sub-nav"><a href="#">Sub Menu One <span class="icon"></span></a></li>
                <li class="sub-nav"><a href="#">Sub Menu Two <span class="icon"></span></a></li>
                <li class="sub-nav"><a href="#">Sub Menu Three <span class="icon"></span></a></li>
                <li class="sub-nav"><a href="#">Sub Menu Four <span class="icon"></span></a></li>
                <li class="sub-nav"><a href="#">Sub Menu Five <span class="icon"></span></a></li>
            </ul>
          </li> -->
         
        </ul>
      </nav>
      <div class="offcanvas-overlay"></div>
<!-- /Off-canvas Navbar -->

<!-- Main Navbar Mobile -->

<div class="navbar-wrapper mobile-nav main-nav hidden-md hidden-lg">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="nav-inner">
            <ul class="nav navbar-nav">
                <?php if( !is_user_logged_in() ): ?>
                    <li><a data-login-link="" class="nav-links login-btn" data-toggle="modal" data-target="#loginModal"><i class="icon-user mp-icon-md light"></i></a></li>
                    <li><a class="nav-links search-btn" data-target="search-open"><i class="icon-search mp-icon-md light"></i></a></li>
                    <li class="hidden"><a class="nav-links explore-btn" data-target="explore-open"><i class="icon-compass mp-icon-md light"></i></a></li>
                <?php else: ?>
                    <li><a data-login-link="" data-toggle="dropdown" class="nav-links profile-btn" style="background: url(<?= $mp_current_user->get_avatar(); ?>) no-repeat;"><b class="login caret"></b></a>
                        
                        <ul class="dropdown-menu login-dropdown" role="menu" aria-labelledby="teaser-filter">
                         <li class="profile-btn" role="presentation"><a role="menuitem" tabindex="-1" href="<?= MPUrl::get_page_link( 'profile' ); ?>"><?= $mp_current_user->get_nickname(); ?></a></li>
 
                         <!-- <li role="presentation"><a role="menuitem" tabindex="-1"><?php // _e( 'My Drafts', 'menapost-theme' ); ?></a></li> -->
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo mp_get_link( 'bookmarks', 'recent' ); ?>"><?php _e( 'My Reading List', 'menapost-theme' ); ?></a></li>
                         <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php // echo mp_get_link( 'recommended-readings', 'recent' ); ?>"><?php // _e( 'Recommended Readings', 'menapost-theme' ); ?></a></li> -->
                         
                         <?php if( current_user_can( 'edit_posts' ) ) : ?>
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo admin_url('index.php'); ?>"><?php _e( 'Admin Panel', 'menapost-theme' ); ?></a></li>
                         <?php endif; ?>   
                         <li class="logout-btn" role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo wp_logout_url(); ?>"><?php _e( 'Logout', 'menapost-theme' ); ?></a></li>
                     </ul>
                 </li>
                 <li><a class="nav-links search-btn in" data-target="search-open"><i class="icon-search mp-icon-md light"></i></a></li>
                 <li class="hidden"><a class="nav-links explore-btn in" data-target="explore-open"><i class="icon-compass mp-icon-md light"></i></a></li>
             <?php endif; ?>
             
         </ul>
 
         <div class="navbar-header">
            <a  class="nav-expander fixed">
            <i class="icon-menu  light mp-icon-md "></i>
          </a>
            <a  class="logo" href="<?php echo get_home_url(); ?>">
                <i class="icon-kasra-logo mp-icon-xxlg light"></i>
            </a>
        </div>
 
        <div class="navbar-collapse collapse">
 
        </div>
    </div>
</div>
</div>

<!-- Main Navbar Desktop -->

<div class="navbar-wrapper desktop-nav main-nav hidden-sm hidden-xs">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="nav-inner">
            <div class="nav navbar-nav">
                <ul class="top-bar">
                <?php if( !is_user_logged_in() ): ?>
                    <li><a data-login-link="" class="nav-links login-btn" data-toggle="modal" data-target="#loginModal"><i class="icon-user mp-icon-md light"></i></a></li>
                    <li><a class="nav-links search-btn" data-target="search-open"><i class="icon-search mp-icon-md light"></i></a></li>
                    <li class="hidden"><a class="nav-links explore-btn" data-target="explore-open"><i class="icon-compass mp-icon-md light"></i></a></li>
                <?php else: ?>
                    <li><a data-login-link="" data-toggle="dropdown" class="nav-links profile-btn in" style="background: url(<?= $mp_current_user->get_avatar(); ?>) no-repeat;"><b class="login caret"></b></a>
                        
                        <ul class="dropdown-menu login-dropdown" role="menu" aria-labelledby="teaser-filter">
                         <li class="profile-btn" role="presentation"><a role="menuitem" tabindex="-1" href="<?= MPUrl::get_page_link( 'profile' ); ?>"><?= $mp_current_user->get_nickname(); ?></a></li>

                         <!-- <li role="presentation"><a role="menuitem" tabindex="-1"><?php // _e( 'My Drafts', 'menapost-theme' ); ?></a></li> -->
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo mp_get_link( 'bookmarks', 'recent' ); ?>"><?php _e( 'My Reading List', 'menapost-theme' ); ?></a></li>
                         <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php // echo mp_get_link( 'recommended-readings', 'recent' ); ?>"><?php // _e( 'Recommended Readings', 'menapost-theme' ); ?></a></li> -->
                         
                         <?php if( current_user_can( 'edit_posts' ) ) : ?>
                         <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo admin_url('index.php'); ?>"><?php _e( 'Admin Panel', 'menapost-theme' ); ?></a></li>
                         <?php endif; ?>   
                         <li class="logout-btn" role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo wp_logout_url(); ?>"><?php _e( 'Logout', 'menapost-theme' ); ?></a></li>
                     </ul>
                 </li>
                 <li><a class="nav-links search-btn in" data-target="search-open"><i class="icon-search mp-icon-md light"></i></a></li>
                 <li class="hidden"><a class="nav-links explore-btn in" data-target="explore-open"><i class="icon-compass mp-icon-md light"></i></a></li>
             <?php endif; ?>
                 <li class="pull-right">
                     <ul class="footer-social hidden-xs">
                        <li class="follow-trigger">
                            <a  class="fb-bg" href="https://www.facebook.com/KasraOnline" target="_blank"><i class="icon-facebook-2 light mp-icon-sm"></i></a>
                            <a  class="twitter-bg" href="https://twitter.com/<?php echo get_option( 'twitter_handle'); ?>" target="_blank"><i class="icon-twitter-2 light mp-icon-sm"></i></a>
                            <a  class="g-plus-bg" href="https://plus.google.com/+KasraCoOnline" target="_blank"><i class="icon-gplus light mp-icon-sm"></i></a>
                            <div class="follow-content">
                                <span><?= SocialSharing::get_facebook_like_button(); ?></span>
                                <span style="margin-left:6px;"><?= SocialSharing::get_twitter_follow_button(); ?></span>
                                <span><?= SocialSharing::get_google_plus_follow_button(); ?></span>
                           </div>
                        </li>
                     </ul>
                 </li>
             </ul>
             <div class="clearfix"></div>
             
             <ul class="bottom-bar" data-query-key="category">
             <li><a data-query-default="true" href="<?php echo get_home_category_url( "All" ); ?>" data-query-value="all"><?php _e( 'All', 'menapost-theme' ); ?></a></li>


             <?php //foreach( $categories as $category ) : ?>
           <!--         <li><a href="<?php //echo get_home_category_url( $category[1] ); ?>" data-query-value="<?php //echo $category[1]; ?>"><?php //echo $category[0]; ?></a></li>
             <?php //endforeach; ?> -->
                   <!-- <li><a class="separator" data-query-value=""><?php //echo "|"; ?></a></li> -->
             <?php foreach( $merged_tags as $tag ) : ?>
                   <li><a href="<?php echo get_home_category_url( $tag->slug ); ?>"  data-query-value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></a></li>
             <?php endforeach; ?>

            <!-- Kasra Eid Greetings -->
            <?php // if(shortcode_exists( 'kasra-eid-greetings' )): ?>
            <!-- <li><a href="<?= home_url('/عيد'); ?>" data-query-value="عيد"><?php _e( 'Create a Greetings Card', 'menapost-theme' ); ?></a></li> -->
            <?php // endif; ?>
            <!-- /Kasra Eid Greetings -->
             </ul>
         </div>

         <div class="navbar-header">
            <a  class="logo" href="<?php echo get_home_url(); ?>">
                <i class="icon-kasra-logo mp-icon-xxxlg light"></i>
            </a>
        </div>

        <div class="navbar-collapse collapse">

        </div>
    </div>
</div>
</div>
 <!-- Search Form -->
            <div class="search-header" data-element="search-form">
            
            <div class="search-container">
            <!-- Search input-->
            <div  class="search-form">
                    <form action="<?php echo home_url( '/' ); ?>" method="GET" role="search" >
                        <div>
                            <input type="text" value="" autocomplete="off" placeholder="<?php _e( 'I\'m looking for ...', 'menapost-theme' ); ?>" name="s" id="s" />
                        </div>
                    </form>
                </div>
             <!-- Search close -->
            <div class="search-close">
                <a data-target="search-close"><i class="icon-cancel-circled dark mp-icon-sm"></i></a>
            </div>

            </div>
                
            </div>
 <!-- /Search Form -->


<!-- /Navbar -->
<?php 
    if( is_single() ) :
        global $post;     
        $article = DataSource::get_article_by_id( $post->ID );
    endif;
?>
<div class="onscroll-nav  navbar-wrapper main-nav">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="inner-wrapper">
            <div class="navbar-header">
             <a  class="nav-expander fixed">
            <i class="icon-menu mp-icon-xs "></i>
          </a>
                <a id="emblem_link" href="<?php echo get_home_url(); ?>">
                     <i class="icon-kasra-emblem orange"></i>
                </a>
            </div>

            <!-- Article -->
            <?php if( is_single() ) : ?>
            <div class="nav-stick activitybar-horizontal ">
                <ul>
                    <li class="fb-horizontal">
                        <a class="social-link" data-activity-name="facebook-share" href="<?php echo SocialSharing::get_facebook_share_link(); ?>" target="_blank">
                            <i class="icon-facebook-2 mp-icon-xxs fb"></i>
                            <span class="hide-portrait show-landscape--inline-block"><?php _e( 'Share', 'menapost-theme' ); ?></span>                 
                        </a>
                    </li>

                    <li class="twitter-horizontal">
                      <a class="social-link" data-activity-name="twitter" href="<?php echo SocialSharing::get_twitter_link( $article ); ?>" target="_blank">
                        <i class="icon-twitter-2 mp-icon-xxs twitter"></i>
                        <span class="hide-portrait show-landscape--inline-block"><?php _e( 'Chirp', 'menapost-theme' ); ?></span>           
                    </a>
                </li>
                <li class="whatsapp-horizontal" style="display: none;" data-ios-only="true">
                    <a class="social-link nopopup" href="<?php echo SocialSharing::get_whatsapp_link( $article ); ?>">
                        <i class="icon-whatsapp mp-icon-xs light"></i>            
                    </a>
                </li>
            </ul>
        </div>
        <?php elseif( is_home() || is_tax( 'seasonal', 'ramadaniyat' ) || is_tax( 'seasonal', 'ramadan-series' ) ) : ?>
        <!-- Home -->
            <ul class="category list-unstyled hidden-sm hidden-xs" data-query-key="category">
            <li>
                <div class="article-tags">

                <?php if( is_home() ) : ?>
                    <a class="label" href="<?php echo get_home_category_url( "All" ); ?>" data-query-default="true" data-query-value="all"><?php _e( 'All', 'menapost-theme' ); ?></a>
                <?php endif; ?>
                    <?php
                        if( is_tax( 'seasonal', 'ramadaniyat' ) || is_tax( 'seasonal', 'ramadan-series' ) ) {
                            $merged_tags = mp_get_sub_tags( 'seasonal', RAMADANIYAT_TAG_SLUG );
                            $local_tags = true;
                        }
                        // if( is_tax( 'seasonal', 'world-cup' ) ) 
                        //     $merged_tags = mp_get_sub_tags( 'seasonal', 'world-cup' );
                        foreach ($merged_tags as $tag) : ?>
                           <a class="label" href="<?php echo isset( $local_tags) ? get_sub_tag_url( RAMADANIYAT_TAG_SLUG, $tag->slug ) : get_home_category_url( $tag->slug ); ?>" data-query-value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></a>
                    <?php endforeach; ?>
                </div>
            </li>
                
             </ul>
         <?php endif; ?>
    </div>
</div>
</div>
