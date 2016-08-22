<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
$my_options_1234=my_quizz_get_options();	
$my_q=my_quizz_get_questions_answers($post_id);
$share_results_1234=$my_options_1234['labels']['share_results'];
?>
<?php /*
<pre><?php //print_r($my_q);?></pre>
*/ ?>
<?php 
global $wp_query;
$post_id=$wp_query->get_queried_object_id();
//echo my_quizz_resize_thumb(31,array(600,200));
//echo my_quizz_resize_thumb(31,array(180,180));

?>
<br/><br/><br/>
<input type="hidden" name="my_post_quizz_id" value="<?php echo $post_id;?>"/>
<?php 

if(!empty($my_q)){
	$my_q_1234=0;
 foreach($my_q as $k=>$v){
	$obj=$my_q[$k];
	$v=$obj;
	?>
	<?php /*<pre><?php //print_r($v);?></pre>*/ ?>
	<?php 
	
	
	if(!empty($v['answers'])){
?>
	<div class="my_question" my_id="<?php echo $k;?>">
		<div class="my_question_image">
			<?php 
			$image=wp_get_attachment_image_src($v['image'],'my-600x200');
			//print_r($image);
			if(($image[1]!=600)||($image[2]!=200)){
				$url=my_quizz_resize_thumb($v['image'],array(600,200));
				//echo $url;
				
				
				?>
				<img src="<?php echo $url;?>"/>
			
				<?php 
			}else {
			?>
			<img src="<?php echo $image[0];?>"/>
			<?php }?>
			<div class="my_question_title"><?php echo $v['title'];?></div>
		</div>
		<?php 
		$answ=$v['answers'];
		$my_c_answer_1234=$my_q_1234;
		if(!empty($answ)){
			//$my_c_answer_1234++;
			?>
			<div class="my_answers" my_q="<?php echo $k;?>">
				<?php foreach($answ as $k1=>$v1){?>
					<div class="my_answer" my_id="<?php echo $k1;?>">
					<?php if(empty($v1['color'])){?>
						<div class="my_answer_image">
						<?php 
							$image=wp_get_attachment_image_src($v1['image'],'my-180x180');
							//print_r($image);
							if(($image[1]!=180)||$image[2]!=180){
							
								$url=my_quizz_resize_thumb($v1['image'],array(180,180));
							//echo $url;
								?>
								<img src="<?php echo $url;?>"/>
			
							<?php 
							}else {
							?>
							
							<img src="<?php echo $image[0];?>"/>	
							<?php }?>
						</div>
						<div class="my_check"><span class="my_check_span" my_val="0" my_id="<?php echo $k1;?>"></span></div>
						<div class="my_title"><?php echo $v1['title'];?></div>
						
					<?php }else {?>
						<?php 
						$my_color_1234='white';
						if(!empty($my_options_1234['colors'][$my_c_answer_1234])){
							$my_color_1234=$my_options_1234['colors'][$my_c_answer_1234];
						}
						?>
						<div class="my_answer_image" style="background-color:<?php echo $my_color_1234;?>">
							<div class="my_question_title_1"><?php echo $v1['title'];?></div>
						</div>
						<div class="my_check"><span class="my_check_span" my_val="1" my_id="<?php echo $k1;?>"></span></div>
						<div class="my_clear"></div>
					<?php }?>
					</div>
				<?php 
				//$my_c_answer_1234++;
				}?>
			</div>
			<?php
			$my_q_1234++; 
		}
		?>
	</div><?php }
 }
} 
?>
	<?php 
		$file=WP_MY_QUIZZ_PLUGIN_VIEWS.'/front/my_quizz_result.php';
		require $file;
	/*<div class="my_share_results">
		<div class="my_quizz_title"><?php the_title();?></div>
		<div id="my_result">
			<h2><?php ?></h2>
		</div>	
	</div>*/ ?>
