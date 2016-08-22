<?php

get_header();
do_action( 'navbar' );

if(have_posts()) {
	the_post();
	the_content();
}

include('mp_home_footer.php');
get_footer();
?>
