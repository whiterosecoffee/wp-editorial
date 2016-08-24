<?php

/**
 * Template Name: Profile Page
 */


if( !is_user_logged_in() ) { wp_redirect( home_url() ); exit; } ?>
<?php get_header(); ?>
<?php global $current_user;
      get_currentuserinfo(); 


$view = get_selected_view();
$filter = get_sort_filter();
$count = 0;
$result_articles = mp_get_profile_articles( $view, $filter, $count ); 
$show_more_button = $count > Constants::PROFILE_PAGE_ARTICLES_COUNT;
?>


<?php include( CHILD_DIR . '/mp_navbar.php' ); ?>
<header class="profile-header" data-page="profile">

	<!-- Right side dropdown -->

	<h1 class="category-mobile hidden-sm hidden-md hidden-lg"> <?php if( isset( $view ) && $view ): _e( get_view_name( $view ), 'menapost-theme' ); else: _e( get_view_name( 'recommended-readings' ), 'menapost-theme' ); endif; ?></h1>

	<ul class="nav navbar-nav profile-menu-right hidden-xs">
	      <li class="category-profile dropdown article-toolbar">
	        <a id="teaser-filter" role="button" class="dropdown-toggle" data-toggle="dropdown" href="#0"><b class="caret"></b>&nbsp;<?php if( isset( $view ) && $view ): _e( get_view_name( $view ), 'menapost-theme' ); else: _e( get_view_name( 'recommended-readings' ), 'menapost-theme' ); endif; ?></a>
	        <ul class="dropdown-menu" role="menu" aria-labelledby="teaser-filter" data-query-key="view">
	           <li role="presentation"><a role="menuitem" data-nav-change="recommended-readings" href="<?php echo mp_get_link( 'recommended-readings', $filter ); ?>" tabindex="-1" data-query-value="recommended-readings"><?php _e( get_view_name( 'recommended-readings' ), 'menapost-theme' ); ?></a></li>
	           <li role="presentation"><a role="menuitem" data-nav-change="bookmarks" href="<?php echo mp_get_link( 'bookmarks', $filter ); ?>" tabindex="-1" data-query-value="bookmarks"><?php _e( get_view_name( 'bookmarks' ), 'menapost-theme' ); ?></a></li>
	       </ul>
	   </li>
	</ul>
	<!-- /Right side dropdown -->

	<!-- Left side filter dropdown -->
	<ul class="nav navbar-nav profile-menu-left ">
		<li class="filter-profile dropdown article-toolbar">
			<a id="teaser-filter" role="button" class="dropdown-toggle" data-toggle="dropdown" href="#0"><b class="caret"></b>&nbsp;<?php if( isset( $filter ) && $filter ): _e( get_filter_name( $filter ), 'menapost-theme' ); else: _e( get_filter_name( 'recent' ), 'menapost-theme' ); endif; ?></a>
			<ul class="filter-profile-dropdown dropdown-menu" role="menu" aria-labelledby="teaser-filter" data-query-key="sort">
				<li role="presentation"><a role="menuitem" data-nav-change="recent" href="<?php echo mp_get_link( $view, 'recent' ); ?>" tabindex="-1" data-query-value="recent"><?php _e( get_filter_name( 'recent' ), 'menapost-theme' ); ?></a></li>
				<li role="presentation"><a role="menuitem" data-nav-change="viewed" href="<?php echo mp_get_link( $view, 'popular' ); ?>" tabindex="-1" data-query-value="popular"><?php _e( get_filter_name( 'popular' ), 'menapost-theme' ); ?></a></li>
				<!-- <li role="presentation"><a role="menuitem" data-nav-change="trending" href="<?php // echo mp_get_link( $view, 'trending' ); ?>" tabindex="-1" data-query-value="trending"><?php // _e( get_filter_name( 'trending' ), 'menapost-theme' ); ?></a></li> -->
				<li role="presentation"><a role="menuitem" data-nav-change="active" href="<?php echo mp_get_link( $view, 'active' ); ?>" tabindex="-1" data-query-value="active"><?php _e( get_filter_name( 'active' ), 'menapost-theme' ); ?></a></li>
			</ul>
		</li>
	</ul>
	<!-- /Left side filter dropdown -->

	
</header>
<div data-article-view="<?php echo $view; ?>" data-article-count="<?php echo count( $result_articles ); ?>">
<?php include( CHILD_DIR . '/mp_home_loop.php' ); ?>
</div>

<?php get_footer(); ?>