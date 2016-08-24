<?php /* Template Name: Contact Template */ ?>
<?php
    get_header();
    do_action( 'navbar' );
?>
<div class="contactWrapper">
    <?php
        $content = get_the_content();
        echo do_shortcode($content);
    ?>
</div>
<?php get_footer(); ?>
<?php include('mp_home_footer.php'); ?>
