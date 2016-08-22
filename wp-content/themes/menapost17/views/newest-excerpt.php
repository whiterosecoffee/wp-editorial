<?php global $post;?>

<article class="articleExcerpt newestArticles floatfix" data-post-id="<?= get_the_id() ?>">
	<a href="<?php the_permalink();?>" class="articleLink" data-post-id="<?= get_the_id() ?>">
		<?php
			if ( '' != get_the_post_thumbnail() ){
				the_post_thumbnail( 'full', array( 'class'=> 'featuredImage' ));
			}
			else{ ?>
				<img src="<?= get_post_meta( $post->ID, 'image', true );?>" class="featuredImage">
		<?php } ?>
		<h2 class="tileTitle newestTitle">
			<?php the_title();?>
		</h2>
	</a>
</article>
