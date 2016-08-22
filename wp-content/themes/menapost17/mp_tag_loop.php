<?php

foreach($result_articles as $post):
    setup_postdata($post);?>
	    <article class="articleExcerpt tagExcerpt floatfix">
	        <?php get_template_part('views/article-excerpt'); ?>
	    </article>
<?php endforeach;?>
<style type="text/css">
    article.tagExcerpt{float:right; height:250px; width: 31%; margin-left: 2%; margin-bottom: 1.25rem;}
    article.tagExcerpt .countBox, article.tagExcerpt .readTime{display: none;}
</style>

