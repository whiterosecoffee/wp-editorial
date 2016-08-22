<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
?>
<div class="my_form_item_1" id="my_final_results" my_id="<?php echo $new_id;?>" my_id_1="<?php echo $total;?>">
		<div class="my_header_1"><span><?php echo $total.'.';if(strlen($title)>30)echo substr($title,0,30).'...';else echo $title;?></span>&nbsp;&nbsp;<a href="#javascript" class="my_edit_item"><?php echo __("Edit Final Result","my_quizz_domain");?></a>&nbsp;&nbsp;<a href="#javascript" class="my_delete_final_result"><?php echo __("Delete Final Result","my_quizz_domain");?></a></div>
		<div class="my_inner">
			<div id="my_add_final" class="my_form" action="<?php echo admin_url('admin-ajax.php');?>" method="post" enctype="multipart/form-data">
			<ul>
				<li><label><?php echo __("Final result title","my_quizz_domain");?></label></li>
				<li><input class="my_input_text" type="text" name="my_quiz_title" value="<?php echo esc_attr($title);?>"/></li>
				<li><label><?php echo __("Final result description","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="my_quiz_descr"><?php echo esc_textarea($descr);?></textarea></li>
				<li><label><?php echo __("Final result picture","my_quizz_domain");?></label></li>
				<li>
				
				<input type="button" class="my_get_selected_image button button-primary button-large" value="<?php echo __("Change image","my_quizz_domain")?>"/>
				<div>
				<?php 
				if(!empty($image)){
					$new_post=get_post($image);
					?>
					<h4><?php echo $new_post->post_title;?></h4>
						<?php 
						$image_1=wp_get_attachment_image_src($image,'thumbnail');
						
						?>
						<img src="<?php echo $image_1[0]?>" width="50px" height="50px"/>
				
			 	<input type="hidden" class="my_selected_attachment" value="<?php echo $image;?>"/>';
			
					<?php 
				}
				?>
				</div>
				</li>
				<li><input type="button" class="my_update_final_result button button-primary button-large" value="<?php echo __("Update final result","my_quizz_domain")?>"/></li>
				
				
			</ul>
			</div>
		</div>
	</div>