<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' ); 
$title="Indie drama";
$image=30;
$my_options_1234=my_quizz_get_options();
$you_gout_1234=$my_options_1234['labels']['you_got'];
?>
<div class="my_share_results">
		<div class="my_quizz_title"><?php the_title();?></div>
		<div id="my_result">
			<h2><?php echo __("You got:","my_quizz_domain")?><?php echo $title;?></h2>
			<div class="my_result_div">
				
				<p>
				<?php 
				echo 'fjdsklf jgsdklgjsdklgjdkl fhklsdh fskldhg
		dfjsklfj sfkls fjdslkfjsd; fjlsd ;fs jfklds;fjs; fjs d;jfs kfks;
		fjsd;fjs;d fjsd;fj;sdfjs fjdsljfs;jdfs'
				?>
				
				
				</p>
				<?php 
				$image=wp_get_attachment_image_src($image,'my-180x180');
				
				?>
				<div class="my_image_result">
					<img  src="<?php echo $image[0]?>"/>
					<span><?php $my_post=get_post($image);echo $my_post->post_title;?></span>
				</div>
				
				
				<div class="my_clear"></div>
			</div>
		</div>
			<div class="my_share_results_div">
				<div class="my_share_text"><?php echo $my_options_1234['labels']['share_results'];//echo __("SHARE YOUR RESULTS");?></div>
				<div class="my_share_icons">
					<?php 
					if($my_options_1234['share_enabled']['facebook']){
					?>
					<a class="my_share_facebook" href=""><img src="<?php echo WP_MY_QUIZZ_PLUGIN_IMAGES_URL.'facebook-share.jpg'?>"/></a>
					&nbsp;
					<?php }?>
					<?php if($my_options_1234['share_enabled']['twiiter']){
					?>
					<a class="my_share_twitter" href=""><img src="<?php echo WP_MY_QUIZZ_PLUGIN_IMAGES_URL.'twitter-share.jpg'?>"/></a>
					&nbsp;
					<?php }?>
					<?php if($my_options_1234['share_enabled']['email']){
					?>
					<a class="my_share_email" href=""><img src="<?php echo WP_MY_QUIZZ_PLUGIN_IMAGES_URL.'email-share.jpg'?>"/></a>
					<?php }?>
					
					
				</div>
				<div class="my_clear"></div>
			</div>
		
			<div class="my_clear"></div>
	</div>