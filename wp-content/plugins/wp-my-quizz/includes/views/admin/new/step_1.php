<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
?>
<div class="my_step">
	<?php echo __("Step 1:Add a new Quizz","my_quizz_domain");?>
</div>
<form id="my_step_1">
<div class="my_form_item">
	<div class="my_title"><?php echo __("Quiz Title","my_quizz_domain").':';?></div>
	<div class="my_element">
		<input name="quiz_title" type="text" class="my_text my_quizz_title" value=""/>
	</div>
</div>
<div class="my_form_item">
	<div class="my_title"><?php echo __("Select number of results/outcomes","my_quizz_domain").':';?></div>
	<div class="my_element">
		<select name="my_num_results" class="my_text my_select">
		<?php 
		$options=my_quizz_get_options();
		$num=9;
		if(!empty($options['num_results']))$num=$options['num_results'];
		for($i=1;$i<=$num;$i++){
			?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
			<?php 
		
		}
		?>
		</select>
	</div>
</div>
<div class="my_form_item">
	<div class="my_title"><?php echo __("Select number of questions","my_quizz_domain").':';?></div>
	<div class="my_element">
		<select name="my_num_question" class="my_text my_select">
		<?php 
		$num=9;
		if(!empty($options['num_questions']))$num=$options['num_questions'];
		for($i=1;$i<=$num;$i++){
			?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
			<?php 
		
		}
		?>
		</select>
	</div>
</div>
<div class="my_form_item">
	<div class="my_title"><?php echo __("Select format of answers","my_quizz_domain").':';?></div>
	<div class="my_element">
		<ul class="my_radio_list">
			<li><input type="radio" name="my_answers_format" value="1"/><div class="my_label"><?php echo __("Images","my_quizz_domain");?></div></li>
			<li><input type="radio" name="my_answers_format" value="2" checked="checked"/><div class="my_label"><?php echo __("Color Blocks","my_quizz_domain");?></div></li>
			
			
		</ul>
		
	</div>
</div>
</form>
<div class="my_form_item">
	<input type="button" class="my_next my_button" my_step="1" value="<?php echo __("Next","my_quizz_domain");?>"/>
</div>
