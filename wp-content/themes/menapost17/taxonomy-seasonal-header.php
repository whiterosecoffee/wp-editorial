<?php

?>


<div class="profile-header" data-page="post-tag-landing">
	<h1 class=""><?php echo $parent_tag->name; ?></h1>
</div>	

<?php if( !is_tax( 'seasonal', 'world-cup' ) ) : ?>
<header class="seasonal profile-header" >
	<div>
		<?php if( array_key_exists( $selected_tag, $sub_tags ) && sizeof($sub_tags[$selected_tag]) > 0 ) : ?>
      <ul class="article-tags list-unstyled sub">
          <li>
            <?php foreach( $sub_tags[$selected_tag] as $key => $value ): ?>
              <a class="label <?php if( $selected_sub_tag && $selected_sub_tag->slug == $value->slug ) echo 'active'; ?>" href="<?php echo get_sub_tag_url( $selected_tag, $value->slug ); ?>"><?php echo $value->name; ?></a>
            <?php endforeach; ?>
          </li>
      </ul>
	<?php endif; ?>
	</div>
</header>
<?php endif; ?>