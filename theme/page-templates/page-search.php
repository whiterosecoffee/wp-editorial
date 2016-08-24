<?php /* Template Name: Search */ ?>
<?php
	get_header();
	do_action( 'navbar' );
?>
<div class="search-container searchPage">
    <div class="search-form">
        <form action="<?php echo home_url( '/' ); ?>" method="GET" role="search">
            <div>
                <input type="text" value="" autocomplete="off" placeholder="ابحث عن ..." name="s" id="s">
            </div>
        </form>
    </div>
</div>
<?php get_footer(); ?>
<?php include('mp_home_footer.php'); ?>
