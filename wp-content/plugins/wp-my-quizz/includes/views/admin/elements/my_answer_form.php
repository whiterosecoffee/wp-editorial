<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
?>
<div id="my_add_answer" class="my_form" action="<?php echo admin_url('admin-ajax.php');?>" method="post" enctype="multipart/form-data">
			
	<ul my_id="<?php echo $new_id;?>">
				<li><label><?php echo __("Answer","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="my_quiz_title"><?php echo esc_textarea($title);?></textarea></li>
				<?php /*<li><label><?php echo __("Final result description","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="descr"></textarea></li>
				*/ ?>
				<!--  <li><label><?php echo __("If you have planned to use color add color value here.Leave blank if you using image instead.","my_quizz_domain");?></label></li>-->
				<li><label><?php echo __("Will answers have images or solid colors ?","my_quizz_domain");?></label></li>
				
				<li>
					<input type="radio" value="image" <?php if(empty($color))echo 'checked="checked"'?> name="my_answer_type_id123462"/><?php echo __("Image","my_quizz_domain");?><br/>
					<input type="radio" value="color" <?php if(!empty($color))echo 'checked="checked"'?> name="my_answer_type_id123462"/><?php echo __("Color","my_quizz_domain");?><br/>
				</li>
				
				<li class="my_color_li" <?php if(empty($color))echo 'style="display:none"';?>><label><?php echo __("Pick a color","my_quizz_domain");?></label></li>
				
				<li class="my_color_li" <?php if(empty($color))echo 'style="display:none"';?>>
				<?php if(!empty($color)){?>
				<input id="my_color_picker_two" type="text" class="my_color_picker" value="<?php echo $color;?>" style="background-color:<?php echo $color;?>"/></li>
				
				<?php }else {?>
				<input id="my_color_picker_two" type="text" class="my_color_picker" value=""/></li>
				<?php }?>
				<li class="my_image_li" <?php if(!empty($color))echo 'style="display:none"'?>><label><?php echo __("Question picture","my_quizz_domain");?></label></li>
				
				<li class="my_image_li" <?php if(!empty($color))echo 'style="display:none"'?>>
				<input type="button" class="my_get_selected_image button button-primary button-large" value="<?php echo __("Get selected image","my_quizz_domain")?>"/>
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
				<li>
				<label><?php echo __("Question","my_quizz_domain");?></label>
				</li>
				<li>
				<select id="my_question_answer_1" class="my_question_answer">
					<option value=""><?php echo __("-------- Select Question --------","my_quizz_domain");?></option>
				<?php
				$my_c1234=1; 
				$ret=my_quizz_get_question($post_id);
					if(!empty($ret)){
						foreach($ret as $k=>$v){
							?>
							<option <?php if(!empty($question)&&$question==$k)echo 'selected="selected"'?> value="<?php echo $k;?>"><?php echo $my_c1234.'.'; echo $v;$my_c1234++;?></option>
							<?php 
						}
					}
				?>
				</select>
				</li>
				<li>
				<label><?php echo __("Final result","my_quizz_domain");?></label>
				</li>
				<li>
				<select id="my_final_result_answer_1" class="my_final_result_answer">
					<option value=""><?php echo __("-------- Select Final Result --------","my_quizz_domain");?></option>
				<?php 
				$ret=my_quizz_get_final($post_id);
				$my_c1234=1;
					if(!empty($ret)){
						foreach($ret as $k=>$v){
							?>
							<option <?php if(!empty($final_res)&&$final_res==$k) echo 'selected="selected"'?> value="<?php echo $k;?>"><?php echo $my_c1234.'.'; echo $v;$my_c1234++;?></option>
							<?php 
						}
					}
				?>
				</select>
				</li>
				
				<li><input type="button" class="my_edit_answer button button-primary button-large" value="<?php echo __("Edit answer","my_quizz_domain")?>"/></li>
				
				
			</ul>
</div>			