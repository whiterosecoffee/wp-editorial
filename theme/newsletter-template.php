<?php /* Template Name: Newsletter Template */ ?>
<?php
    get_header();
    do_action( 'navbar' );
?>
<!-- Begin Newsletter Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
<style type="text/css">



/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
  We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
</style>
<div id="newsletterSignUp">
	<h2>اشترك الآن في نشرة كسرة</h2>
	<p>سجل معنا في النشرة واكسر روتينك ومللك بنكهة عربية مميزة.</p>
	<p>اكسرها وانشرها!</p>
	<?php mailchimpSF_signup_form(); ?>
</div>

<!--End mc_embed_signup-->
<?php get_footer(); ?>
<?php include('mp_home_footer.php'); ?>
