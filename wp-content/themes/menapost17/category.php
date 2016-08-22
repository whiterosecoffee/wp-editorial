<?php $google_agents = array('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','Googlebot/2.1 (+http://www.google.com/bot.html)', 'googlebot'); ?>
<?php get_header(); do_action('navbar');?>

<?php
	$queried_object = get_queried_object();
	$catId = $queried_object->term_id;
	$catSlug = $queried_object->slug;
	$catName = $queried_object->name;
	$catTax = $queried_object->taxonomy;
	//var_dump($queried_object);
?>

<h1 class="pageName"><?php echo($catName); ?></h1>

<?php if(array_search($_SERVER['HTTP_USER_AGENT'], $google_agents) === false) { ?>

	<div
		id="taxContent" class="floatfix"
		data-query-scroll
		data-query="<?= "cat=$catId&order=DESC&posts_per_archive_page=9" ?>"
		data-template="article-excerpt"
	>
	</div>

<?php } else { ?>


<div id="taxContent">
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$myquery = new WP_Query(
		array(
			'cat'				=>	$catName,
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
