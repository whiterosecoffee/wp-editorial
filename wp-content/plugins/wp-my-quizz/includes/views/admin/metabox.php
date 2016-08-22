<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
?>
<?php 
global $post;
$post_id=$post->ID;
global $my_quizz_is_quizz;
$my_v_1234=get_post_meta($post_id,$my_quizz_is_quizz,true);
//echo 'Value '.$my_v_1234;
if($my_v_1234==1){
	$my_is_quizz_post=1;
	global $my_quizz_questions_keys;
	global $my_quizz_final_result_keys;
	global $my_quizz_answers_keys;
	global $my_quizz_answers;
	//update_post_meta(27,$my_quizz_answers_keys,array(2));
	//update_post_meta(27,$my_quizz_answers_keys.'1',array(1));
	
	/*echo '<pre>';
	//print_r(get_post_custom($post_id));
	echo '</pre>';
	$q=my_quizz_get_questions_answers();
	echo '<pre>';
	print_r($q);
	echo '</pre>';
	//update_post_meta($post_id,$my_quizz_questions_keys, array());
	echo 'Question keys';
	print_r(get_post_meta($post_id,$my_quizz_questions_keys,true));
	echo 'Final result keys';
	print_r(get_post_meta($post_id,$my_quizz_final_result_keys,true));
	echo 'Answer keys ';
	print_r(get_post_meta($post_id,$my_quizz_answers_keys,true));
	*/
}

?>
<p>
<?php echo __("Add final results first then questions and aswers.You have normally to upload imges of quizz by normal wordpress function.","my_quizz_domain");?>
</p>
<ul class="my_options">
	<li><?php echo __("Is this a quiz post ?","my_quizz_domain");?></li>
	<li><input type="checkbox" name="my_is_quiz_post" value="1" <?php if(isset($my_is_quizz_post)&&($my_is_quizz_post==1))echo 'checked="checked"';?>/></li>
</ul>

<div id="my_quiz_data" <?php echo 'style="display:none"'?>>
	<div class="my_form_item" id="my_added_final_results_front">
		<div class="my_header"><?php echo __("Added Final results Front View","my_quizz_domain");?></div>
		<div class="my_inner">
		<?php 
		global $post;
		$my_post_id=$post->ID;
		global $my_quizz_final_result_keys;
		global $my_quizz_final_results;
		$my_f_1234=get_post_meta($my_post_id,$my_quizz_final_result_keys,true);
		$my_c=1;
		if(!empty($my_f_1234)){
		foreach($my_f_1234 as $k=>$v){
			$new_key=$my_quizz_final_results.$v;
			$arr=get_post_meta($my_post_id,$new_key,true);
			extract($arr);
			
			?>
			<div class="my_form_item">
				<div class="my_header"><?php echo $my_c.'.';if(strlen($title)>30)echo substr($title,0,30);else echo $title; $my_c++;?></div>
				<div class="my_inner">
				<?php 
				$file=WP_MY_QUIZZ_PLUGIN_VIEWS.'front/result.php';
				require $file;
				?>
				</div>
			</div>
			<?php 
		}}
		?>
		</div>
	</div>
	<div id="my_last_final_result">
	
	</div>
	<div class="my_form_item" id="my_uploaded_items_div">
		<p><?php echo __("Select a image to add to one of the options.","my_quizz_domain");?></p>
		<p><?php echo __("If you have uploaded new images plese refresh images :","my_quizz_domain");?>
		<input type="button" class="my_refresh_attachments button button-primary button-large" value="<?php echo __("Refresh attachments","my_quizz_domain");?>" />
		</p>
		
		<div id="my_uploaded_items">
		<?php 
		global $post;
		$post_id=$post->ID;
		require $template_dir.'elements/my_attachs.php';
		/*$my_attachs=get_children("post_type=attachment&post_parent=".$post_id);
		
		if(!empty($my_attachs)){
			?>
			<select id="my_attachments">
				<option value=""><?php echo __("----- Select Attachment for item of current section ----","my_quizz_domain");?>
			<?php foreach($my_attachs as $k=>$v){?>
				<option value="<?php echo $v->ID;?>"><?php echo $v->post_title;?></option>
			<?php }?>
			</select>
			<?php 
		}else echo __("There is no attachments uploadded !","my_quizz_domain");
		if(!empty($my_attachs)){
			?>
					<?php foreach($my_attachs as $k=>$v){?>
					<div id="my_attach_<?php echo $v->ID;?>" class="my_attach" style="display:none" my_id="<?php echo $v->ID;?>">
						<h4><?php echo $v->post_title;?></h4>
						<?php 
						$image=wp_get_attachment_image_src($v->ID,'thumbnail');
						
						?>
						<img src="<?php echo $image[0]?>" width="50px" height="50px"/>
					</div>
			<?php }?>		
		<?php }*/		
		?>
		</div>
	</div>
	<div class="my_form_item" id="my_add_final_result">
		<div class="my_header"><?php echo __("Add final result","my_quizz_domain");?></div>
		<div class="my_inner">
			<div id="my_add_final" class="my_form" action="<?php echo admin_url('admin-ajax.php');?>" method="post" enctype="multipart/form-data">
				
			<ul>
				<li><label><?php echo __("Final result title","my_quizz_domain");?></label></li>
				<li><input class="my_input_text" type="text" name="my_quiz_title" value=""/></li>
				<li><label><?php echo __("Final result description","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="my_quiz_descr"></textarea></li>
				<li><label><?php echo __("Final result picture","my_quizz_domain");?></label></li>
				<li>
				<input type="button" class="my_get_selected_image button button-primary button-large" value="<?php echo __("Get selected image","my_quizz_domain")?>"/>
				<div></div>
				</li>
				<li><input type="button" class="my_add_final_result button button-primary button-large" value="<?php echo __("Add final result","my_quizz_domain")?>"/></li>
				
				
			</ul>
			</div>
		</div>
	</div>
	<div class="my_form_item" id="my_add_question">
		<div class="my_header"><?php echo __("Add question","my_quizz_domain");?></div>
		<div class="my_inner">
			<div id="my_add_question" class="my_form" action="<?php echo admin_url('admin-ajax.php');?>" method="post" enctype="multipart/form-data">
					<ul>
				<li><label><?php echo __("Question","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="my_quiz_title"></textarea></li>
				<?php /*<li><label><?php echo __("Final result description","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="descr"></textarea></li>
				*/ ?>
				<li><label><?php echo __("Question picture","my_quizz_domain");?></label></li>
				<li>
				<input type="button" class="my_get_selected_image button button-primary button-large" value="<?php echo __("Get selected image","my_quizz_domain")?>"/>
				<div></div>
				</li>
				<li><input type="button" class="my_add_question button button-primary button-large" value="<?php echo __("Add question","my_quizz_domain")?>"/></li>
				
				
			</ul>
			</div>
		</div>
	</div>
	<div class="my_form_item" id="my_add_answer">
		<div class="my_header"><?php echo __("Add answer","my_quizz_domain");?></div>
		<div class="my_inner">
			<div id="my_add_answer" class="my_form" action="<?php echo admin_url('admin-ajax.php');?>" method="post" enctype="multipart/form-data">
					<ul>
				<li><label><?php echo __("Answer","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="my_quiz_title"></textarea></li>
				<?php /*<li><label><?php echo __("Final result description","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="descr"></textarea></li>
				*/ ?>
				<!--  <li><label><?php echo __("If you have planned to use color add color value here.Leave blank if you using image instead.","my_quizz_domain");?></label></li>-->
				<li><label><?php echo __("Will answers have images or solid colors ?","my_quizz_domain");?></label></li>
				
				<li>
					<input type="radio" value="image" checked="checked" name="my_answer_type_id1234"/><?php echo __("Image","my_quizz_domain");?><br/>
					<input type="radio" value="color" name="my_answer_type_id1234"/><?php echo __("Color","my_quizz_domain");?><br/>
				</li>
				<li class="my_color_li" style="display:none"><label><?php echo __("Pick a color","my_quizz_domain");?></label></li>
				<li class="my_color_li" style="display:none"><input id="my_color_picker_one" type="text" class="my_color_picker" value=""/></li>
				
				
				
				<li class="my_image_li"><label><?php echo __("Question picture","my_quizz_domain");?></label></li>
				
				<li class="my_image_li">
				<input type="button" class="my_get_selected_image button button-primary button-large" value="<?php echo __("Get selected image","my_quizz_domain")?>"/>
				<div></div>
				</li>
				<li>
				<label><?php echo __("Question","my_quizz_domain");?></label>
				</li>
				<li>
				<select id="my_question_answer" class="my_question_answer">
					<option value=""><?php echo __("-------- Select Question --------","my_quizz_domain");?></option>
				<?php
				$my_c=1; 
				$ret=my_quizz_get_question();
					if(!empty($ret)){
						foreach($ret as $k=>$v){
							?>
							<option value="<?php echo $k;?>"><?php echo $my_c.'.';echo $v;$my_c++;?></option>
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
				<select id="my_final_result_answer" class="my_final_result_answer">
					<option value=""><?php echo __("-------- Select Final Result --------","my_quizz_domain");?></option>
				<?php 
				$my_c=1;
				$ret=my_quizz_get_final();
					if(!empty($ret)){
						foreach($ret as $k=>$v){
							?>
							<option value="<?php echo $k;?>"><?php echo $my_c.'.';echo $v;$my_c++;?></option>
							<?php 
						}
					}
				?>
				</select>
				</li>
				<li>
				<label><?php echo __("Add muliple answers ","my_quizz_domain");?></label>
				</li>
				
				<li><input type="checkbox" value="1" class="my_add_multiple_1234"/></li>
				<li class="my_add_multiple_12345">
					<input type="button" class="my_add_new_item button button-primary button-large" value="<?php echo __("Add new item","my_quizz_domain")?>"/>
				</li>
				<li class="my_add_multiple_12345">
					<label><?php echo __("Answers","my_quizz_domain");?></label>
				</li>
				<li class="my_add_multiple_12345">
					<textarea class="my_textaraea" name="title_1234"></textarea></li>
				
				</li>
				<li class="my_add_multiple_12345">
					<label><?php echo __("Images/Colors","my_quizz_domain");?></label>
				</li>
				<li class="my_add_multiple_12345">
					<textarea class="my_textaraea" name="title_1234_colors"></textarea></li>
				
				</li>
				<li class="my_add_multiple_12345">
					<label><?php echo __("Final results","my_quizz_domain");?></label>
				</li>
				<li class="my_add_multiple_12345">
					<textarea class="my_textaraea" name="title_1234_final"></textarea></li>
				
				</li>
				
				<li class="my_add_multiple_1234">
				
				</li>
				<li><input type="button" class="my_add_answer button button-primary button-large" value="<?php echo __("Add answer","my_quizz_domain")?>"/></li>
				
				
			</ul>
			</div>
		</div>
	</div>
	<div class="my_form_item" id="my_final_results">
		<div class="my_header"><?php echo __("Added final results","my_quizz_domain");?></div>
		<div class="my_inner">
		<?php my_quizz_get_final_results();?>
		</div>
	</div>
	<div class="my_form_item" id="my_questions">
		<div class="my_header"><?php echo __("Added questions","my_quizz_domain");?></div>
		<div class="my_inner">
		<?php my_quizz_show_questions();?>
		</div>
	</div>
	<div class="my_form_item" id="my_edit_answer">
		<div class="my_header"><?php echo __("Edit answers","my_quizz_domain");?></div>
		<div class="my_inner">
		<?php //my_quizz_show_questions();?>
		</div>
	</div>
	<div id="my_check_data" style="display:none"></div>
	
</div>
