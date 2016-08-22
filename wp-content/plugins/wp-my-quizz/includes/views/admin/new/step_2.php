<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
$title='Quiz test title new title ?';
$results=9;

		
?>
<div class="my_step">
	<?php echo __("Step 2:Add Quiz outcome information","my_quizz_domain");?>
</div>
<div class="my_form_item">
	<div class="my_title my_float_left"><?php echo __("Quiz title","my_quizz_domain").':';?></div>
	<div class="my_font my_float_left my_margin_left_10" id="my_quizz_title_2"></div>
	<div class="my_clear"></div>
</div>
<div class="my_form_item">
	<div class="my_padding_top_7 my_title my_float_left"><?php echo __("Outcome information","my_quizz_domain").':';?></div>
	<div class="my_float_left my_margin_left_10"><input type="button" class="my_add_more_results my_button" value="<?php echo __("Add more outcomes","my_quizz_domain");?>"/></div>
	<div class="my_clear"></div>
</div>
<form id="my_step_2">
	<div id="my_outcomes">

	</div>
</form>	
<div id="my_images_res" style="display:none">
	<?php for($i=1;$i<=$my_num_results;$i++){?>
		<div class="my_tooltip_content" my_id="<?php echo $i;?>">
			<h4><?php echo __("An Item has no image ,plese click to add new image.","my_quizz_domain");?></h4>
		</div>
	
	<?php }?>
</div>
<div class="my_bare_tooltip" style="display:none">
		<h4><?php echo __("An Item has no image ,plese click to add new image.","my_quizz_domain");?></h4>
		
</div>
<div id="my_dialog_html" style="display:none">
	<div class="my_dialog">
		<div class="my_dialog_header">
			<div class="my_float_left  my_dialog_title">
			<?php echo __("Add More Outcomes","my_quizz_domain");?>
			</div>
			<div class="my_float_right my_close_dialog">
			</div>
			<div class="my_clear"></div>
		</div>
		<div class="my_dialog_inner">
		<div class="my_dialog_q">
		<?php echo __("How many additional outcomes do you want to add ?","my_quizz_domain");?>			
		</div>
			<div style="margin-top:15px">
				<div class="my_float_left" id="my_out_12345">
					<select class="my_add_more_outcomes">
						<?php for($i=1;$i<9;$i++){?>
						<option value="<?php echo $i;?>"><?php echo $i;?></option>
						<?php }?>
					</select>
				</div>
				<div class="my_float_left my_margin_left_10">
				<input type="button" class="my_button my_add_outcomes" value="<?php echo __("Add","my_quizz_domain");?>"/>
				</div>
				
			<div class="my_clear"></div>
			</div>
		</div>
	</div>
</div>
<?php 
/*for($i=0;$i<$results;$i++){
	$c=$i+1;*/
	?>
	<div id="my_html_pattern" style="display:none">
	<div class="my_outcome" my_id="{id}">
		<input type="hidden" name="res_image_id_{id}" value=""/>
		<table cellpadding=0 class="my_table">
			<tr>
				<td><div class="my_title">{id}:</div></td>
				<td style="width:95px"><div class="my_title"><?php echo __("Result","my_quizz_domain").':'?></div></td>
				<td><input type="text" name="res_title_{id}" class="my_text my_width_200"/></td>
				
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="width:95px"><div class="my_title"><?php echo __("Image","my_quizz_domain").':'?></div></td>
				<td><input type="text" name="res_image_{id}" class="my_text my_width_200"/><div my_id="{id}" class="my_inline_block my_margin_left_15"><input my_id="{id}" my_tooltip="my_images_res" my_name="res_image_id_" type="button" class="my_tooltip my_add_image my_button_1" value="<?php echo __("Add Image","my_quizz_domain");?>"/></div></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="width:95px"><div class="my_title"><?php echo __("Description","my_quizz_domain").':'?></div></td>
				<td><textarea name="res_descr_{id}" class="my_text my_q_descr"></textarea></td>
				
			</tr>
		</table>
	</div>
	</div>
	<?php 
//}
?>

<div class="my_form_item">
	<div class="">
		<input type="button" class="my_add_more_results my_button" value="<?php echo __("Add more outcomes","my_quizz_domain");?>"/>
	</div>
	<div class="my_clear"></div>
</div>
<div class="my_form_item">
	<div class="my_float_left">
		<input type="button" class="my_prev my_button" my_step="2" value="<?php echo __("Back","my_quizz_domain");?>"/>
	</div>
	<div class="my_float_left my_margin_left_15">
		<input type="button" class="my_next my_button" my_step="2" value="<?php echo __("Next","my_quizz_domain");?>"/>
	</div>
	<div class="my_clear"></div>
	
</div>
	