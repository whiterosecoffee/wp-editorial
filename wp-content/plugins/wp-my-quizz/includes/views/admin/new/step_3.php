<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
?>
<div class="my_step">
	<?php echo __("Step 3:Add Quiz questions","my_quizz_domain");?>
</div>
<?php /*
<div class="my_form_item">
	<div class="my_title my_float_left"><?php echo __("Quiz title","my_quizz_domain").':';?></div>
	<div class="my_font my_float_left my_margin_left_10"><?php echo $title;?></div>
	<div class="my_clear"></div>
</div>
*/ ?>
<div class="my_form_item">
	<div class="my_title my_float_left"><?php echo __("Quiz title","my_quizz_domain").':';?></div>
	<div class="my_font my_float_left my_margin_left_10" id="my_quizz_title_3"></div>
	<div class="my_clear"></div>
</div>
<form id="my_step_3">
	<div id="my_questions">
	</div>
</form>

<div id="my_front_outcome" style="display:none">
<div class="my_share_results" style="min-width:500px !important;">
		<div class="my_quizz_title">{quizz_title}</div>
		<div id="my_result">
			<h2><?php echo __("You got:","my_quizz_domain")?>{title}</h2>
			<div class="my_result_div">
				
				<p>
				{descr}
				</p>
				
				<div class="my_image_result">
					{image}
				</div>
				
				
				<div class="my_clear"></div>
			</div>
		</div>
		<?php /*
			<div class="my_share_results_div">
				<div class="my_share_text"><?php echo __("SHARE YOUR RESULTS");?></div>
				<div class="my_share_icons">
					<a class="my_share_facebook" href=""><img src="<?php echo WP_MY_QUIZZ_PLUGIN_IMAGES_URL.'facebook-share.jpg'?>"/></a>
					&nbsp;
					<a class="my_share_twitter" href=""><img src="<?php echo WP_MY_QUIZZ_PLUGIN_IMAGES_URL.'twitter-share.jpg'?>"/></a>
					&nbsp;
					<a class="my_share_email" href=""><img src="<?php echo WP_MY_QUIZZ_PLUGIN_IMAGES_URL.'email-share.jpg'?>"/></a>
					
					
					
				</div>
				<div class="my_clear"></div>
			</div>
			*/ ?>
		
			<div class="my_clear"></div>
	</div>
</div>

<div id="my_html_pattern_1" style="display:none">
	<div class="my_question_template">
		<div class="my_margin_bottom_15 my_step_3_question" my_id="{id_q}">
			<input type="hidden" name="q_image_id_{id_q}"/>
			<div class="my_title my_float_left my_margin_right_5">
			{id_q}:   <?php echo __("Question Title","my_quizz_domain").':';?>
			</div>
			<div class="my_float_left">
				<input type="text" name="q_title_{id_q}" class="my_text my_width_200"/>
			</div>
			<div class="my_float_left my_margin_left_15">
				<input my_id="{id_q}" my_tooltip="my_q_images_res" my_name="q_image_id_" type="button" class="my_tooltip my_add_image my_button_1" value="<?php echo __("Add Image","my_quizz_domain");?>"/>
			</div>
			<div class="my_clear"></div>
		</div>
	</div>
	<div class="my_answer_template" style="display:none">
		<div class="my_answers_div" my_q="{id_q}">
		<?php 
		/*$niz[1]='';
		$niz[2]='';
		$niz[3]='';
		for($i=1;$i<10;$i++){
			ob_start();
		*/
			?>
			<div class="my_answer" my_id="{id_q}_{id_a}" my_a="{id_a}" my_q="{id_q}">
				<input type="hidden" name="a_image_id_{id_q}_{id_a}" value=""/>
				<div class="my_float_left my_title my_margin_right_10">
				<?php echo __("Answer","my_quizz_domain").' {id_a}'.':';?>
				<br/>
				<span class="my_tooltip_span my_outcome_span" my_tooltip="my_a_images_res" my_id="{id_q}_{id_a}">
					<?php echo '( '.__("outcome","my_quizz_domain").'{id_a}'.' )'?>
				</span>
				</div>
				<div class="my_float_left">
					<input type="text" class="my_text max_width_120" name="a_{id_q}_{id_a}"/>
				</div>
				<div class="my_float_left my_margin_left_10 my_add_image_div">
					<div my_id="{id_a}" class="my_inline_block my_margin_left_15"><input my_id="{id_q}_{id_a}" my_tooltip="my_a_images_res" my_name="a_image_id_" type="button" class="my_tooltip my_add_image my_button_1" value="<?php echo __("Add Image","my_quizz_domain");?>"/></div>
				</div>
				<div class="my_float_left my_margin_left_10 my_color">
					<span style="color:{color_hex};">({color_name})</span>
				</div>
				
				<div class="my_clear"></div>
			
			</div>
			<?php 
			/*$html=ob_get_clean();
			$a=$i%3;
			if($a==0)$a=3;
			$niz[$a].=$html;
		}
		foreach($niz as $k=>$v){
			?>
			<div class="my_row_33">
				<?php echo $v;?>
			</div>
			
			<?php 
		}*/
		
		
		?>
		<!--  <div class="my_clear"></div>-->	
		</div>
	</div>
</div>
<div id="my_q_images_res" style="display:none">
	<?php for($i=1;$i<=$my_num_questions;$i++){?>
		<div class="my_tooltip_content" my_id="<?php echo $i;?>">
			<h4><?php echo __("An Item has no image ,plese click to add new image.","my_quizz_domain");?></h4>
		</div>
	
	<?php }?>
</div>
<div id="my_a_images_res" style="display:none">
	<?php for($i=1;$i<=$my_num_questions;$i++){?>
		<?php for($j=1;$j<=$my_num_results;$j++){?>	
		<div class="my_tooltip_content" my_id="<?php echo $i.'_'.$j;?>">
			<h4><?php echo __("An Item has no image ,plese click to add new image.","my_quizz_domain");?></h4>
		</div>
		
	<?php 
		}
		}?>
</div>
<div class="my_form_item">
	<div class="my_float_left">
		<input type="button" class="my_prev my_button" my_step="3" value="<?php echo __("Back","my_quizz_domain");?>"/>
	</div>
	<div class="my_float_left my_margin_left_15">
		<input type="button" class="my_preview my_button" my_step="3" value="<?php echo __("Preview Quiz","my_quizz_domain");?>"/>
	</div>
	<div class="my_float_left my_margin_left_15">
		<input type="button" class="my_save_quiz my_button" my_step="3" value="<?php echo __("Save as Draft","my_quizz_domain");?>"/>
	</div>
	<div class="my_clear"></div>
	
</div>
<div id="my_preview_question_html" style="display:none">
	<div class="my_question" my_id="{id_q}">
		<div class="my_question_image">
		{image}
		
		<div class="my_question_title">{title}</div>
		</div>
		{answers}
	</div>	
</div>
<div id="my_preview_answer_html" style="display:none">
	<div class="my_color_html">
		<div class="my_answer" my_id="{id_a}">
			<div class="my_answer_image" style="background-color:{color_hex}">
							<div class="my_question_title_1">{title}</div>
			</div>
			<div class="my_check"><span class="my_check_span" my_val="1" my_id="{id_q}"></span></div>
			<div class="my_clear"></div>
		</div>
	</div>
	<div class="my_image_html">
		<div class="my_answer" my_id="{id_a}">
			<div class="my_answer_image">
				{image}
			</div>
			<div class="my_check"><span class="my_check_span" my_val="0" my_id="{id_a}"></span></div>
			<div class="my_title">{title}</div>
						
		</div>
	</div>
</div>
<div id="my_preview_quiz_div">
	<h4><?php echo __("Quiz preview","my_quizz_domain")?></h4>
	<iframe border="0" src="<?php echo admin_url('admin.php?page=my-quizz-options&my_preview=1')?>"></iframe>
</div>