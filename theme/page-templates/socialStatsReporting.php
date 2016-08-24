<?php //Template Name: Social Stats Reporting ?>
<h1 id="aliceTitle">Alice 0.1<img id="alice" src="<?php echo(site_url());?>/wp-content/themes/menapost/img/alice.jpeg"></h1>

<style type="text/css">
#aliceTitle {line-height:65px; }
#alice{height:45px; line-height:65px; margin:.5rem .75rem 0; float:left;}
section.socialStatsTable{width:49%; float:right; margin-left:1%; margin-bottom: 1.5rem;}
div.statsTable {padding:.5rem; height:250px; overflow-y: scroll; outline: 1px solid grey;}
	div.statsTable:hover {background-color:#eee;}
	h1{margin:0;}
	.reportLink{display:inline-block; width:30%;}
	.articleStat > span{display:inline-block; width:15%;} @media screen and (max-width:70rem){ span span {display:none;}}
	div.statsTable * {height:25px; line-height: 25px; overflow: hidden;}
	
	.articleStat:hover {background-color:#ddd;}
	strong{font-size:.8em;color:#3b5998;}
</style>
<section id="reportMostShared" class="socialStatsTable">
 <?php global $wpdb; global $post;
	   $query ="select post_title, rank, allviews, allshares, post_date  from wp_posts join post_stats on post_id=wp_posts.id where category=1 order by rank desc limit 50";
	   $result = $wpdb->get_results($query, OBJECT);
?>
	<h1>Most Shared New (Cat1) <?php print_r(count($result));?> Articles</h1> 
	<div class="statsTable">    
	   
	   
	    <?php
	    foreach($result as $post):
	        setup_postdata($post);?>
	        <article> 
	            <?php get_template_part('views/socialStatsList'); ?>
	        </article>            
	    <?php endforeach;?>
    </div>
</section><!--most-shared-->

<section id="reportMostViewed" class="socialStatsTable">
	<?php 
		global $wpdb;
		global $post;
		$query ="select post_title, rank, allviews, allshares, post_date from wp_posts join post_stats on post_id=wp_posts.id where category=2 order by rank desc limit 50";
		$result = $wpdb->get_results($query, OBJECT);
	?>
	<h1>Most Viewed New (Cat2) <?php print_r(count($result));?> Articles</h1>
	<div class="statsTable">    
	    <?php
	    foreach($result as $post):
	        setup_postdata($post);?>
	        <article> 
	            <?php get_template_part('views/socialStatsList'); ?>
	        </article>            
	    <?php endforeach;?>
    </div>
</section><!--most viewed -->

<section id="reportSharedAll" class="socialStatsTable">
    <?php 
	    global $wpdb;
	    global $post;
	    $query ="select post_title, rank, allviews, allshares, post_date  from wp_posts join post_stats on post_id=wp_posts.id where category=3 order by rank desc limit 50";
	    $result = $wpdb->get_results($query, OBJECT);	
	?>
	<h1>Most Viewed All (Cat3) <?php print_r(count($result));?> Articles</h1>  
	<div class="statsTable">    
		<?php	
	    foreach($result as $post):
	        setup_postdata($post);?>
	        <article> 
	            <?php get_template_part('views/socialStatsList'); ?>
	        </article>            
	    <?php endforeach;?>
    </div>
</section><!--most shared ALL -->

<section id="reportViewedAll" class="socialStatsTable">
	<?php 
		global $wpdb;
		global $post;
		$query ="select post_title, rank, allviews, allshares, post_date  from wp_posts join post_stats on post_id=wp_posts.id where category=4 order by rank desc limit 50";
		$result = $wpdb->get_results($query, OBJECT);
	?>

	<h1>Most Viewed All (Cat4) <?php print_r(count($result));?> Articles</h1>  
	<div class="statsTable">    

		<?php
		foreach($result as $post):
		    setup_postdata($post);?>
		    <article> 
		        <?php get_template_part('views/socialStatsList'); ?>
		    </article>            
		<?php endforeach;?>
	</div>    
</section><!--most viewed ALL -->