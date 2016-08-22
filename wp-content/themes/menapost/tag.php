<?php
$post_tag = get_term_by( 'name', single_tag_title( '', FALSE ), 'post_tag');
$filter = get_sort_filter();
$count = 0;
$result_articles = mp_get_post_tag_articles( $post_tag->slug, $filter, $count ); 
$show_more_button = $count > Constants::POST_TAG_LANDING_PAGE_COUNT;

get_header();

do_action( 'navbar' );
?>

<header class="profile-header" data-page="post-tag-landing">

	<!-- post_tag name -->
	<h1 class="profile-menu-right"><?php echo $post_tag->name; ?></h1>
	<!-- /post_tag name -->

	<!-- Left side filter dropdown -->
	<ul class="nav navbar-nav profile-menu-left ">
		<li class="filter-profile dropdown article-toolbar">
			<!-- <a id="teaser-filter" role="button" class="dropdown-toggle" data-toggle="dropdown" href="#0"><b class="caret"></b>&nbsp;<?php // if( isset( $filter ) && $filter ): _e( get_filter_name( $filter ), 'menapost-theme' ); else: _e( get_filter_name( 'recent' ), 'menapost-theme' ); endif; ?></a> -->
			<a id="teaser-filter" role="button" class="dropdown-toggle"><?php if( isset( $filter ) && $filter ): _e( get_filter_name( $filter ), 'menapost-theme' ); else: _e( get_filter_name( 'recent' ), 'menapost-theme' ); endif; ?></a>
			<ul class="filter-profile-dropdown dropdown-menu" role="menu" aria-labelledby="teaser-filter" data-query-key="sort">
				<li role="presentation"><a role="menuitem" data-nav-change="recent" tabindex="-1" data-query-value="recent"><?php _e( get_filter_name( 'recent' ), 'menapost-theme' ); ?></a></li>
				<!-- <li role="presentation"><a role="menuitem" data-nav-change="viewed" tabindex="-1" data-query-value="popular"><?php // _e( get_filter_name( 'popular' ), 'menapost-theme' ); ?></a></li> -->
				<!-- <li role="presentation"><a role="menuitem" data-nav-change="trending" tabindex="-1" data-query-value="trending"><?php // _e( get_filter_name( 'trending' ), 'menapost-theme' ); ?></a></li> -->
				<!-- <li role="presentation"><a role="menuitem" data-nav-change="active" tabindex="-1" data-query-value="active"><?php // _e( get_filter_name( 'active' ), 'menapost-theme' ); ?></a></li> -->
			</ul>
		</li>
	</ul>
	<!-- /Left side filter dropdown -->

	
</header>
<div data-article-post-tag="<?php echo $post_tag->slug; ?>" class="nocontent">
	<?php include( CHILD_DIR . '/mp_home_loop.php' ); ?>
</div>

<?php
get_footer();