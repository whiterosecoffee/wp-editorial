<?php $google_agents = array('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','Googlebot/2.1 (+http://www.google.com/bot.html)'); ?>
<?php get_header(); do_action('navbar');?>

<?php $tagName = get_query_var('tag');?>


<h1 class="pageName"><?php echo($tagName);?></h1>

<?php if(array_search($_SERVER['HTTP_USER_AGENT'], $google_agents) === false && strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'googlebot') === false) { ?>

	<div id="taxContent" class="floatfix"
		data-query-scroll
		data-query="<?= "tag=$tagName&order=DESC&post_per_page=6" ?>"
		data-template="article-excerpt">
	</div>

<?php } else { ?>

<div id="taxContent">
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$myquery = new WP_Query(
		array(
			'tag'				=>	$tagName,
			'posts_per_page' 	=>  '12',
			'paged'				=>	$paged
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

<?php } ?>

<?php include('mp_home_footer.php'); get_footer(); ?>
