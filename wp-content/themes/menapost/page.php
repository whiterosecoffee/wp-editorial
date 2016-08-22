<?php

get_header();
the_post();
?>
<?php do_action('navbar'); ?>


<section>
	<?php the_content();  ?>
</section>



<?php get_footer(); ?>