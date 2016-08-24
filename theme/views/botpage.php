<?php global $deviceType, $post; ?>

<div class="tabContent content floatfix">
    <section id="most-shared" class="kasraMostShared articles <?php echo ($deviceType); ?>">

     <?php $result = renderForBot(array(1,3),'article-excerpt','fetchCategoryPostsForBot');
      echo $result->html; ?>

    </section><!--most-shared-->
<div class="gutter gutterTablet"></div>

<?php if($deviceType != "mobile"){ ?>
    <section id="most-viewed" class="kasraMostViewed articles">

    <?php $result =  renderForBot(array(2,4),'article-excerpt','fetchCategoryPostsForBot','red');
     echo $result->html; ?>

    </section><!--most-viewed-->
    <div class="gutter gutterDesktop"></div>
    <section id="newest" class="kasraNewest articles">

    <?php $result = renderForBot(array(1),'newest-excerpt','fetchNewPostsForBot');
    echo $result->html; ?>
    </section><!--newest-->
    <?php wp_pagenavi(array('query' =>  $result->q)); ?>

<?php } //END IF !MOBILE ?>

