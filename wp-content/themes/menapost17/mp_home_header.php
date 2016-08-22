<?php do_action( 'navbar' ); ?>

<?php $article = $result_articles[0]; ?>
<?php
if( isset( $article ) ) : 
$featured_image = $article->get_image();


is_front_page() == true;
?>



<!-- Featured Article
================================================== -->
<header class="" data-page="home">
   
</header>

<div class="hidden">
    <form name="ignore_me">
        <input type="hidden" id="page_is_dirty" name="page_is_dirty" value="0" />
    </form>
</div>

<?php endif; ?>