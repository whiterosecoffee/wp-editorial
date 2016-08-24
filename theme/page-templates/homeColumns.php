<?php /* Template Name: HTML5 Home Page*/?>
<?php get_header(); ?>
<?php global $deviceType; 
$cat1=3; $cat2=3; $cat3=3; 
if($deviceType == "mobile"){$cat1=2; $cat2=0; $cat3=0;}
else if($deviceType == "tablet"){$cat1=2; $cat2=2; $cat3=2;}π
?>
        <div class="col-title tabs kasraMostShared">
            <h1 href="#most-shared" class="tabTitle">شاركوها</h1>
        </div>
        <div class="gutter gutterTablet"></div>
        <div class="col-title tabs kasraMostViewed">
            <div class="col-emblem"><i class="icon-kasra-emblem"></i>
            </div>
            <div class="triangle-bottom"></div>
                <h1 href="#most-viewed" class="tabTitle">ينكسر الان</h1>
        </div>
        <div class="gutter gutterDesktop"></div>
        <div class="col-title tabs kasraNewest">
            <h1 href="#newest" class="tabTitle">جديد</h1>
        </div>
    </nav>
    <div class="tabContent content floatfix">
        <section id="most-shared" class="kasraMostShared articles <?php echo ($deviceType); ?>">
            
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
        </section><!--most-shared-->
        <div class="gutter gutterTablet"></div>
        <?php if($deviceType != "mobile"){ ?>
            <section id="most-viewed" class="kasraMostViewed articles">
                
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
            </section><!--most-viewed-->
           	<div class="gutter gutterDesktop"></div>
            <section id="newest" class="kasraNewest articles">
                
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
                        <?php get_template_part('views/newest-excerpt');?>
                    </article>
                <?php endforeach;?> 
                <?php wp_reset_postdata();?>
            </section><!--newest-->
    </div> <!-- content -->

<?php } /* END if != "mobile" */?>

<?php get_footer(); ?>
