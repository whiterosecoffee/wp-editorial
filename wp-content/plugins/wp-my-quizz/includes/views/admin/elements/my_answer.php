<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
?>
<div class="my_form_item_2" id="my_answers" my_q="<?php echo $question;?>" my_id="<?php echo $new_id;?>" my_id_1="<?php echo $total;?>">
	<div class="my_header_2"><span><?php echo $total.'.';if(strlen($title)>30)echo substr($title,0,30).'...';else echo $title;?></span>&nbsp;&nbsp;<a href="#javascript" class="my_edit_item_anwser"><?php echo __("Edit Answer","my_quizz_domain");?></a>&nbsp;&nbsp;<a href="#javascript" class="my_delete_answer"><?php echo __("Delete Answer","my_quizz_domain");?></a></div>
</div>