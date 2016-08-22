<?php $filter = get_sort_filter(); ?>
<?php $categories = mp_get_categories(); ?>
<?php $article_type_tags = mp_get_article_type_tags(); ?>

<?php $seasonal_tags = mp_get_seasonal_tags(); ?>
<?php $seasonal_sub_tags = mp_get_seasonal_sub_tags(); ?>

<?php $merged_tags = array_merge($seasonal_tags, $article_type_tags); ?>

<?php $selected_category = mp_get_selected_category(); ?>
<?php $selected_sub_category = mp_get_selected_sub_category(); ?>

<!-- <div class="mobile article-tags hidden-sm hidden-md hidden-lg" data-query-key="category">
    <?php foreach ($merged_tags as $tag) : ?>
        <span class="<?php if(False &&  array_key_exists( $tag->slug, $seasonal_sub_tags ) && sizeof($seasonal_sub_tags[$tag->slug]) > 0 && $selected_category == $tag->slug ) echo 'seasonal-selected mobile'; ?> parent-tag-mobile">
          <a class="label <?php if(False && array_key_exists( $tag->slug, $seasonal_sub_tags ) && sizeof($seasonal_sub_tags[$tag->slug]) > 0 ) echo 'seasonal'; ?>" href="<?php echo get_home_category_url( $tag->slug ); ?>" data-query-value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></a>
        </span>
    <?php endforeach; ?>

    <?php if( False && array_key_exists( $selected_category, $seasonal_sub_tags ) && sizeof($seasonal_sub_tags[$selected_category]) > 0 ) : ?>
      <a class="label hidden-x" href="<?php echo get_home_category_url( "All" ); ?>"><span style="font-size:12px;"><?php _e( 'All', 'menapost-theme' ); ?></span></a>
        <ul class="article-tags sub list-unstyled ">
            <li>
              <?php foreach( $seasonal_sub_tags[$selected_category] as $key => $value ): ?>
                <a class="label <?php if( $selected_sub_category == $value->slug ) echo 'active'; ?>" href="<?php echo get_sub_category_url( $selected_category, $value->slug ); ?>"><?php echo $value->name; ?></a>
              <?php endforeach; ?>
            </li>
        </ul>
<?php endif; ?>
</div> -->

<nav class="navbar navbar-inverse article-nav hidden-xs" role="navigation">
  <li class="pull-right hidden-xs">
    <ul class="toolbar nav navbar-nav  ">
      <li class="article-toolbar hidden-xs hidden-sm switch-btn"><a data-nav="grid" class="grid hidden-xs "><i class="icon-grid mp-icon-xs"></i></a></li>
      <li class="article-toolbar hidden-xs hidden-sm switch-btn"><a data-nav="list" class="list hidden-xs "><i class="icon-list mp-icon-xs rotate"></i></a></li>
   </ul>
 </li>
 <li class="hidden-xs tags-container">
  <!--  <div class="article-tags" data-query-key="category">
    <?php foreach ( $merged_tags as $tag ) : ?>
      <span <?php if( False &&  array_key_exists( $tag->slug, $seasonal_sub_tags )  && sizeof($seasonal_sub_tags[$tag->slug]) > 0 && $selected_category == $tag->slug ) echo 'class="seasonal-selected desktop"'; ?>>
        <a class="label <?php if(False && array_key_exists( $tag->slug, $seasonal_sub_tags ) && sizeof($seasonal_sub_tags[$tag->slug]) > 0 ) echo 'seasonal'; ?>" href="<?php echo get_home_category_url( $tag->slug ); ?>" data-query-value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></a>
      </span>
    <?php endforeach; ?>
   </div> -->
 </li>   

<?php if( False && array_key_exists( $selected_category, $seasonal_sub_tags ) && sizeof($seasonal_sub_tags[$selected_category]) > 0 ) : ?>
 <li class="sub-filter">
      <ul class="article-tags sub">
          <li>
            <?php foreach( $seasonal_sub_tags[$selected_category] as $key => $value ): ?>
              <a class="label <?php if( $selected_sub_category == $value->slug ) echo 'active'; ?>" href="<?php echo get_sub_category_url( $selected_category, $value->slug ); ?>"><?php echo $value->name; ?></a>
            <?php endforeach; ?>
          </li>
      </ul>
 </li>
<?php endif; ?>
</nav>