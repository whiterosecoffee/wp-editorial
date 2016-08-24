<?php /* Template Name: Home Page */?>
<?php get_header(); ?>
<?php global $deviceType; 
$cat1=3; $cat2=3; $cat3=3; 
if($deviceType == "mobile"){$cat1=2; $cat2=0; $cat3=0;}
else if($deviceType == "tablet"){$cat1=2; $cat2=2; $cat3=2;}

?>
<section id="most-shared" class="col-1-2 rightCol <?php echo ($deviceType); ?>">
	<h1 class="col-title">انشرها - test</h1>
	<?php global $post;
	$args = array( 
		'numberposts'		=> $cat1,
		'post_type'			=> 'article',
		'order-by'			=> 'rand',
		'post_status'		=> 'publish' 
		);$query = get_posts($args);
	$allPosts = get_posts($args);
	foreach ($allPosts as $post) : setup_postdata($post); ?>
		<article class="articleExcerpt home-grid-box mostShared floatfix">
			<?php get_template_part('views/article-excerpt'); ?>
        </article>
	<?php endforeach;?> 
	<?php wp_reset_postdata();?>

</section><!--hot-->
<?php if($deviceType != "mobile"){ ?>
    <section id="most-viewed" class="col-1-3 middleCol">
        <h1 class="col-title">ينكسر الآن</h1>
        <?php $args = array( 
            'numberposts'		=> $cat2,
            'offset'            => 5,
            'post_type'			=> 'article',
            'order-by'			=> 'rand',
            'post_status'		=> 'publish' 
            );$query = get_posts($args);
        $allPosts = get_posts( $args );
        foreach ( $allPosts as $post ) : setup_postdata( $post ); ?>
            <article class="articleExcerpt home-grid-box mostViewed floatfix">
                <?php get_template_part('views/article-excerpt'); ?>
            </article>
        <?php endforeach;?> 
        <?php wp_reset_postdata();?>
    </section><!--new-->
    
    <section id="newest" class="col-1-6 leftCol">
        <h1 class="col-title">آخر العناوين</h1>
        <?php $args = array( 
            'numberposts'		=> $cat3,
            'offset'            => 10,
            'post_type'			=> 'article',
            'order-by'			=> 'rand',
            'post_status'		=> 'publish' 
            );$query = get_posts($args);
        $allPosts = get_posts( $args );
        foreach ( $allPosts as $post ) : setup_postdata( $post ); ?>
            <article class="articleExcerpt home-grid-box newestArticles floatfix">
                <?php get_template_part('views/newest-excerpt'); ?>
            </article>
        <?php endforeach;?> 
        <?php wp_reset_postdata();?>
    </section><!--trending-->
<?php } /* END if != "mobile" */?>
<style>
/*styles*/
.col-title { margin-top:25px; }
.home-rebuild .content { padding:0 10px; }/*change class to home when finished*/
	.home-rebuild section { float:right; }/*change class to home when finished*/
.articleExcerpt { background-color:white; margin-bottom:20px; position:relative;}
	.articleExcerpt header { height:35px; padding:0 10px; }
	.articleDate, .readTime { line-height:35px; }
	.articleExcerpt footer { padding:10px 5% 2%; }
	.tile-title { float:left; }
.mostShared, .mostViewed { border:1px solid #ddd; }
.newestArticles { border-bottom:1px solid #ddd; border-top:1px solid #ddd; }
.featured-image-link { position:relative; }
	.articleStats { position:absolute; bottom:0; left:0; width:100%; }
		.viewCount { background-color:rgba(255,255,255,.74); }
		.socialCount { display:none; color:white; }
			.socialCount i { background-color:white; border-radius:50%; display:block; height:1.3em; width:1.3em; }
			.fbCount { color:#306199; }
			.twCount { color:#26c4f1; }
		.countBox { width:70px; margin-right:10px; padding:6px 5px; display:block; float:left;}
		.mostShared .countBox { width:80px; padding:8px 6px; }
		.mostShared i { font-size:1.3em; }
.articleAuthorImgLink { }
	.articleAuthorImgLink img { border-radius:50%; border:1px solid #4d4d4d; }
.articleAuthorLink { display:block; width:45px; text-align:center; font-size:.8em; }
/*structure*/
.col-3-4 { width:75%; }
.col-2-3 { width:66.66%; } 
.col-1-2 { width:50%; }
.col-1-3 { width:33.33%; }
.col-1-4 { width:25%; }
.col-1-6 { width:16.66%; }
.rightCol {padding-left:10px; }
.middleCol { padding:0 10px; }
.leftCol {padding-right:10px; }
</style>
<?php get_footer(); ?>
