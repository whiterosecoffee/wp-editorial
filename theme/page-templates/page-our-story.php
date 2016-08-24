<?php

/**
 * Template Name: Our story
 */

get_header();
the_post();
?>
<?php do_action('navbar'); ?>

<!-- Story content -->
<section class="our-story-container" data-page="our-story">
	<h1><?php the_title(); ?></h1>
	<?php the_content();  ?>
	<div class="hidden-xs" style="height: 150px;"></div>
</section>



<?php get_footer(); ?>