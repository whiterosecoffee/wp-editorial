<div class="container" style="padding: 70px 0;">
	<div class="no-articles well">
		<?php if( isset( $message ) ) : ?>
	    	<h4 class="text-center"><?php echo $message; ?></h4>
		<?php else: ?>
			<h4 class="text-center"><?php _e( 'No articles in this list.', 'menapost-theme' ); ?></h4>
		<?php endif; ?>
	</div>
</div>
<div class="fill-gap"></div>