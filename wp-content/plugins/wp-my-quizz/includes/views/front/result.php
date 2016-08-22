<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
$my_options_1234=my_quizz_get_options();
$you_got_1234=$my_options_1234['labels']['you_got'];

?>
<h2><?php echo $you_got_1234;echo __(":","my_quizz_domain")?><?php echo $title;?></h2>
			<div class="my_result_div">			
<p><?php echo $descr;?></p>
				<?php 
				$image_1=wp_get_attachment_image_src($image,'my-180x180');
				//print_r($image_1);
				?>
				<div class="my_image_result">
					<?php 
					if(($image_1[1]!=180)||($image_1[2]!=180)){
							$url=my_quizz_resize_thumb($image,array(180,180));
							//echo $url;
								?>
								<img src="<?php echo $url;?>"/>
					<?php 	
					}else {
					?>
					<img  src="<?php echo $image_1[0]?>"/>
					<?php 
					}
					/*<span><?php $my_post=get_post($image);echo $my_post->post_title;?></span>*/ ?>
				</div>
				
				
				<div class="my_clear"></div>
				</div>
				