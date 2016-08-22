<?php global $deviceType; $deviceType="desktop";
if(preg_match("/android/i",$_SERVER["HTTP_USER_AGENT"])){$deviceType = "tablet";}
if(preg_match("/mobile/i",$_SERVER["HTTP_USER_AGENT"])) {$deviceType = "mobile";}
if(preg_match("/ipad/i",$_SERVER["HTTP_USER_AGENT"]))   {$deviceType = "tablet";}
/*$deviceType = "mobile";*/
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
<script type="text/javascript">document.body.className += " mobile-nav-closed";</script>
<div id="main" class="push floatfix <?php echo ($deviceType); ?>"> <!-- header-in -->
    <nav id="social-media" class="">
        <?php include('views/socialMediaNav.php');?>
        <?php include('views/followContent.php');?>
    </nav><!-- Social Media -->
    <div id="profile" class="floatfix">
        <a data-login-link="" class="nav-links login-btn" data-toggle="modal" data-target="#loginModal"><i class="icon-user icon"></i></a>
        <a class="nav-links search-btn in" data-target="search-open"><i class="icon-search icon"></i></a>
        <?php if( is_user_logged_in() ): ?>
            <a id="loggedInAvatar" data-login-link="" data-toggle="dropdown" class="nav-links profile-btn in avatar" ><img src="<?php echo ($mp_current_user->get_avatar()); ?>"/><b class="login caret"></b></a>
            <ul class="dropdown-menu login-dropdown" role="menu" aria-labelledby="teaser-filter">
            <?php include('views/user-profile.php');?>
        <?php endif; ?>
    </div><!-- Profile-->

    <a class="nav-btn menu-link" id="nav-open-btn" href="#">
        <i class="icon-menu icon"></i>
    </a>
    <a href="<?php bloginfo('url');?>" id="logo" class="">
        <i id="logo-icon" class="icon-kasra-logo"></i>
    </a>
    <nav id="nav" role="navigation" class="floatfix nav-right">
        <a id="nav-close-btn" class="nav-btn" href="#">
            <i class="icon-cancel-circled icon-small floatleft"></i>
            أكسرها و أنشرها
        </a>
        <?php if( is_user_logged_in() ): ?>
            <div id="loggedInUserInfo">
                <ul class="" role="menu" aria-labelledby="teaser-filter">
                <?php include('views/user-profile.php');?>
            </div>
        <?php endif; ?>

        <div id="main-all-menus-wrapper" class=" floatfix <?php echo ($deviceType); ?>"><!--Grey background on papa-->
          <div id="main-all-menus-container" class="menu">
              <?php wp_nav_menu(array('menu' => 'all', 'menu_class' => 'all-menu floatfix'));?>
              <?php wp_nav_menu(array('menu' => 'main', 'menu_class' => 'home-nav floatfix',));?>
              <?php include('views/menuAllBottom.php');?>
              <?php include('views/aboutKasra.php');?>
          </div>
        </div><!--<div id="main-all-menus-wrapper">-->


    </nav>

  <?php include('views/search-form.php');?>
  <?php
    if( is_single() ){
        global $post;
        $article = DataSource::get_article_by_id( $post->ID );
        include('views/singleSocialMenu.php');
    }?>
</div>
