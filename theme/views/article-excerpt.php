<?php $showInternalStats = false; ?>
<?php if (get_the_terms($post->ID, 'seasonal') !== false){$seasonal = "seasonal";}?>

<article class="articleExcerpt floatfix <?php /* echo $seasonal; // Hides unintended posts, some of which do not appear to have a seasonal term. See https://github.com/menaPOST/menapost-site/issues/71 */ ?>" data-post-id="<?= get_the_id()?>">
	<?php if($showInternalStats) { ?>
		<p>group: <?= $post->category ?> rank: <?= $post->rank ?> id: <?= $post->ID ?></p>
		<p>date: <?= $post->post_date ?></p>
		<p>categories: <?php var_dump(wp_get_post_categories($post->ID, array('fields' => 'names'))); ?></p>
	<?php } else { ?>

		<a href="<?php the_permalink();?>" class="articleLink" data-post-id="<?= get_the_id() ?>">
			<?php
			if ( '' != get_the_post_thumbnail() ){
				the_post_thumbnail( 'full', array( 'class'=> 'featuredImage' ));
			}
			else{ ?>
				<img src="<?= get_post_meta( $post->ID, 'image', true ) ?>" class="featuredImage">
			<?php } ?>
			<strong href="<?= wp_get_shortlink( get_the_id() ) ?>" data-action="fb-share" class="countBox">
				<span><?= Utilities::make_k_count( $post->facebook_total ) ?></span>
				<i class="icon-facebook-2"></i>
			</strong>
		</a>
		<footer class="tileFooter floatfix">
			<div class="tileAuthor">
				<a href="<?= get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" class="articleAuthorLink"><?= get_the_author();?></a>
				<a href="<?= get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>" class="articleAuthorImgLink">
				<img src="<?= get_user_meta( $post->post_author, 'profile_picture_thumbnail', true ) ?>" width="45" height="45" ></a>
			</div><!-- tileAuthor -->
			<h2 class="tileTitle">
				<a href="<?php the_permalink();?>" class="articleLink"><?php the_title() ?></a>
			</h2>
			<span class="readTime"><?= Utilities::reading_time_string(get_post_meta( $post->ID, 'read-duration', true )); ?></span>
		</footer>

	<?php } ?>
</article>
