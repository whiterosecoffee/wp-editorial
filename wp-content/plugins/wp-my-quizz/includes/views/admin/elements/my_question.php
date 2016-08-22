<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
?>
<div class="my_form_item_1" id="my_final_results" my_id="<?php echo $new_id;?>">
		<div class="my_header_1"><span><?php echo $total.'.';if(strlen($title)>30)echo substr($title,0,30).'...';else echo $title;?></span>&nbsp;&nbsp;<a href="#javascript" class="my_edit_item"><?php echo __("Edit Question","my_quizz_domain");?></a>&nbsp;&nbsp;<a href="#javascript" class="my_delete_question"><?php echo __("Delete Question","my_quizz_domain");?></a></div>
		<div class="my_inner">
			<div id="my_add_final" class="my_form" action="<?php echo admin_url('admin-ajax.php');?>" method="post" enctype="multipart/form-data">
				<ul>
				<?php /*<li><label><?php echo __("Final result title","my_quizz_domain");?></label></li>
				<li><input class="my_input_text" type="text" name="title" value="<?php echo esc_attr($title);?>"/></li>
				<li><label><?php echo __("Final result description","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="descr"><?php echo esc_textarea($descr);?></textarea></li>
				<li><label><?php echo __("Final result picture","my_quizz_domain");?></label></li>
				*/ ?>
					<ul>
				<li><label><?php echo __("Question","my_quizz_domain");?></label></li>
				<li><textarea class="my_textaraea" name="my_quiz_title"><?php echo esc_textarea($title);?></textarea></li>
				
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
				<li><input type="button" class="my_update_question button button-primary button-large" value="<?php echo __("Update Question","my_quizz_domain")?>"/></li>
				
				
			</ul>
			</div>
		</div>
		<a href="#javascript" class="my_show_answers"><?php echo __("Show answers","my_quizz_domain");?></a>
		<div class="my_added_answers my_inner_4" my_id="<?php echo $new_id?>">
		<?php 
		global $my_quizz_answers_keys;
		global $my_quizz_answers;
		$quizz_keys=$my_quizz_answers_keys.$new_id;
		
		global $post;
		if(isset($post)){
		$post_id=$post->ID;
		$answers=get_post_meta($post_id,$my_quizz_answers_keys,true);
		$question=$new_id;
		$my_c1=1;
		if(!empty($answers)){
			foreach($answers as $k=>$v){
			$new_key=$my_quizz_answers.$v;
			$arr=get_post_meta($post_id,$new_key,true);
			if($arr['question']==$question){
			$title=$arr['title'];
			$new_id=$v;
			//$file=$template_dir.'elements/my_answer.php';
				
			//require $file;
			$total_1=$my_c1;//($k+1);
			?>
			<div class="my_form_item_2" id="my_answers" my_q="<?php echo $question;?>" my_id="<?php echo $new_id;?>">
				<div class="my_header_2"><span><?php echo $total_1.'.';if(strlen($title)>30)echo substr($title,0,30).'...';else echo $title;?></span>&nbsp;&nbsp;<a href="#javascript" class="my_edit_item_anwser"><?php echo __("Edit Answer","my_quizz_domain");?></a>&nbsp;&nbsp;<a href="#javascript" class="my_delete_answer"><?php echo __("Delete Answer","my_quizz_domain");?></a></div>
			</div>
			<?php 
			$my_c1++;
			}
			}
		}}
		?>
		
		</div>
	</div>