<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
$options=my_quizz_get_options();
//print_r($options);
if(empty($options['num_results'])){
	$options['num_results']=9;
};
if(empty($options['num_questions'])){
	$options['num_questions']=9;
};
//print_r($options);
?>
<div class="wrap">
	<h2><?php echo __("Quizz options","my_quizz_domain");?></h2>
	<?php 
	if(!empty($_POST['my_submit'])){
		foreach($options['share_enabled'] as $k=>$v){
			$post_key='my_share_'.$k;
			$value=@$_POST[$post_key];
			if(empty($value))$value=0;
			else $value=1;
			$options['share_enabled'][$k]=$value;
			
		}
		foreach ($options['labels'] as $k=>$v){
			$post_key='my_labels_'.$k;
			$value=@$_POST[$post_key];
			if(!empty($value)){
				$options['labels'][$k]=$value;
			}
		}
		$facebook=@$_POST['facebook'];
		if(!empty($facebook)){
			$options['facebook_id']=$facebook;
		}
		$twitter=@$_POST['twitter'];
		if(!empty($twitter)){
			$options['twitter']=$twitter;
		}
		$num_results=@$_POST['num_results'];
		if(!empty($num_results)){
			$options['num_results']=$num_results;
		}
		$num_questions=@$_POST['num_questions'];
		if(!empty($num_questions)){
			$options['num_questions']=$num_questions;
		}
		$colors=@$_POST['colors'];
		//print_r($colors);
		if(!empty($colors)){
			$options['colors']=$colors;
		}
		my_quizz_update_option($options);
		?>
		<div stle="color:blue;font-size:18px;"><?php echo __("You have updated options !","my_quizz_domain");?></div>
		<?php 
		$options=my_quizz_get_options();
	}
	?>
	<form method="post">
	<ul>
		<li><label><?php echo __("Facebook Application ID","my_quizz_domain");?></label></li>
		<li><input type="text" class="my_text my_width_200" name="facebook" value="<?php if(!empty($options['facebook_id']))echo $options['facebook_id'];?>"/></li>
		<li><label><?php echo __("Twiiter User","my_quizz_domain");?></label></li>
		<li><input type="text" class="my_text my_width_200" name="twitter" value="<?php if(!empty($options['twitter']))echo $options['twitter'];?>"/></li>
		<li><label><?php echo __("Max results/outcomes","my_quizz_domain");?></label></li>
		<li><input type="text" class="my_text" name="num_results" value="<?php if(!empty($options['num_results']))echo $options['num_results'];?>"/></li>
		<li><label><?php echo __("Max questions","my_quizz_domain");?></label></li>
		<li><input type="text" class="my_text" name="num_questions" value="<?php if(!empty($options['num_questions']))echo $options['num_questions'];?>"/></li>
		
		
		<!--  <li><input type="submit" name="my_submit" value="<?php echo __("Save","my_quizz_domain");?>" class="button button-primary button-large"/></li>
		-->
	</ul>
	<div class="my_labels_chooser_div">
		<h1><?php echo __("Enable shares buttons","my_quizz_domain");?></h1>
		<ul>
		<?php if(!empty($options['share_enabled'])){?>
			<?php foreach($options['share_enabled'] as $k=>$v){?>
				<li><label>
				<?php switch($k){
					case 'facebook':
						echo __("Share via facebook","my_quizz_domain");
					break;
					case 'twiiter':
						echo __("Share via twiiter","my_quizz_domain");
					break;
					case 'email':
						echo __("Share via email","my_quizz_domain");
					break;
					
						
				};?></label></li>
				<li><input type="checkbox" value="1" name="my_share_<?php echo $k;?>" <?php if(!empty($v))echo 'checked="checked";'?>/></li>
			<?php }?>
		<?php }?>
		</ul>
	</div>
	<div class="my_labels_chooser_div">
		<h1><?php echo __("Frontend labels","my_quizz_domain");?></h1>
		<ul>
	<?php 
		if(!empty($options['labels'])){
			foreach($options['labels'] as $k=>$v){
		$label=str_replace("_"," ",$k);
		?>
		<li><label><?php echo ucfirst($label)?></label></li>
		<li><input type="text" class="my_text my_width_200" name="my_labels_<?php echo $k;?>" value="<?php if(!empty($v))echo esc_attr($v);?>"/></li>
		<?php }
		}
		?>
		</ul>
	</div>
	<div class="my_color_chooser_div">
		<h1><?php echo __("Color Block Chooser","my_quizz_domain");?></h1>
		<p>
		<?php echo __("The folowing color blocks will be assigned to each answers corresponding to each question.","my_quizz_domain").':';?>
		</p>	
	<?php 
		$niz=array('','','');
		$num=$options['num_results'];
		for($i=1;$i<=$num;$i++){
			$a=($i-1)%3;
			ob_start();
			$c=$i-1;
			if(!empty($options['colors'][$c])){
				$color=$options['colors'][$c];
			}
			?>
			<div class="my_answer" style="margin-bottom:20px">
				<div class="my_float_left my_title">
				<?php 
				echo __("Answer","my_quizz_domain").' '.$i;
				?>
				</div>
				<div class="my_float_left my_margin_left_15">
					<input name="colors[]" id="my_color_text_<?php echo $i;?>" class="my_width_100 my_text_new my_color_picker" value="<?php if(!empty($color))echo $color;?>" style="<?php if(!empty($color))echo 'background-color:'.$color;?>"/>
				</div> 
				<div class="my_clear"></div>
			</div>
			<?php
			$html=ob_get_clean();
			$col=($i-1)%3;
			$niz[$col].=$html;
			 
		}
		foreach($niz as $k=>$v){
			?>
			<div class="my_row_33" style="width:33%">
			<?php echo $v;?>
			</div>
			<?php 
		}
		?>
		<div class="my_clear"></div>
	</div>
	<input type="submit" name="my_submit" value="<?php echo __("Save","my_quizz_domain");?>" class="button button-primary button-large"/>	
	</form>
</div>