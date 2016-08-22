<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
$options=my_quizz_get_options();
$my_num_results=9;
if(!empty($options['num_results']))$my_num_results=$options['num_results'];
$my_num_questions=9;
if(!empty($options['num_questions']))$my_num_questions=$options['num_questions'];
		
	
?>
<div class="wrap">
	<div class="my_windows_1">
		<div class="my_windows">
			<div class="my_window" my_id="1">
			<?php 
			$file=$template_dir.'new/step_1.php';
			require $file;
			?>
			</div>
		
			<div class="my_window" my_id="2">
			<?php 
			$file=$template_dir.'new/step_2.php';
			require $file;
			?>
			</div>
			<div class="my_window" my_id="3">
			<?php 
			$file=$template_dir.'new/step_3.php';
			require $file;
			?>
			</div>
			<div class="my_clear"></div>
		</div>
	</div>
</div>
