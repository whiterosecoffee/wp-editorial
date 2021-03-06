<?php $google_agents = array('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','Googlebot/2.1 (+http://www.google.com/bot.html)'); ?>
<?php
$author = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

$filter = get_sort_filter();
$count = 0;
$result_articles = mp_get_author_articles( $author->ID, $filter, $count );
$show_more_button = $count > Constants::AUTHOR_LANDING_PAGE_COUNT;
$previous = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );

$twitter = get_user_meta( $author->ID, 'twitter', true );
$facebook = get_user_meta( $author->ID, 'facebook', true );

$mp_author = new MPUser( $author->ID );

get_header();

do_action( 'navbar' );

?>

<header class="author-header" data-page="author">

	<!-- Info Container -->
	<div class="author-details">
		<!-- Name -->
		<h1 class="hidden-sm hidden-md hidden-lg"><?php echo $author->display_name; ?></h1>

		<!-- Image container -->
	<div class="author-image">
		<img src="<?= $mp_author->get_avatar( 'author-page' ); ?>" alt="<?php echo $author->display_name; ?>" title="<?php echo $author->display_name; ?>" />
	</div>

		<!-- Description -->
		<div class="author-description">
		<!-- Name -->
		<h1 class="hidden-xs"><?php echo $author->display_name; ?></h1>
		<p><?php echo $author->description; ?></p>
		<!-- Contact Info -->
		<div class="author-contact">
			<a class="orange-bg" href="mailto:<?php echo $mp_author->get_email(); ?>"><i class="icon-mail mp-icon-xs orange"></i></a>
			<?php if( $facebook !== "" ): ?>
			<a class="fb-bg" href="<?php echo $facebook; ?>" target="_blank"><i class="icon-facebook-2 mp-icon-xs fb"></i></a>
			<?php endif; ?>
			<?php if( $twitter !== "" ): ?>
			<a class="twitter-bg" href="<?php echo 'http://www.twitter.com/' . $twitter; ?>" target="_blank"><i class="icon-twitter-2 mp-icon-xs twitter"></i></a>
			<?php endif; ?>
		</div>
		</div>
	</div>

</header>
<div class="author-sub-header">
	<h2><?php _e( 'Threads', 'menapost-theme' ); ?>
		<span>&nbsp;(<span><?= $mp_author->get_articles_count_str(); ?></span>)</span>
	</h2>
	<!-- Left side filter dropdown -->
	<div class="article-count-wrapper hidden">
		<span class="article-count"><span>عدد المواضيع:</span><?= $mp_author->get_articles_count_str(); ?></span>
	</div>
	<!-- /Left side filter dropdown -->

</div>

<?php if(array_search($_SERVER['HTTP_USER_AGENT'], $google_agents) === false && strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'googlebot') === false) { ?>

	<div id="taxContent" data-query-scroll
		data-query="<?= "author=$post->post_author&order=DESC&posts_per_archive_page=9" ?>"
		data-template="article-excerpt">
	</div>

<?php } else { ?>
<div data-article-author="<?php echo $author->ID; ?>">

	<div id="taxContent">
		<?php
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$myquery = new WP_Query(
			array(
				'author' 			=> $post->post_author,
				'posts_per_page'	=> '12',
				'paged' 			=> $paged
			)
		);

		if ($myquery->have_posts()) :
			while ($myquery->have_posts()) : $myquery->the_post(); ?>
				<?php get_template_part('views/article-excerpt');
			endwhile;
			wp_pagenavi(array('query' => $myquery));
			wp_reset_query();
		endif; ?>
	</div>

	<?php } //else ?>

</div>

<?php include('mp_home_footer.php'); get_footer(); ?>
