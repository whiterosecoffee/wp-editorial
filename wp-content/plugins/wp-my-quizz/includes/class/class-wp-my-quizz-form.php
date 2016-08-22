<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
if(!class_exists("Class_Wp_My_Quizz_Form")){
	class Class_Wp_My_Quizz_Form{

		static function render_form($fields,$field_tmp,$view_dir){
			if(!empty($fields)){
				
				foreach($fields as $name=>$obj){
					//wp_my_pro_events_can_view_field($obj);
					$func=$obj['type'];
					if($obj['type']=='html'){
						$html='';
						$file_html=$view_dir.$obj['file'];
						ob_start();
						
						if(!empty($file_html)){
							if(file_exists($file_html)){
								if(!empty($obj['values'])){
									extract($obj['values']);	
								}
								require $file_html; 	
							}
						}
						$html=ob_get_clean();
					}
					else $html=Class_Wp_My_Quizz_Form::$func($name,$obj);
					$title=$obj['title'];
					$descr=$obj['tooltip'];
					require $field_tmp;
					
				}
			}
			
			
			
		}
		static function password($name,$field){
			if(!isset($field['value'])){
				$value=$field['default'];
			}else $value=$field['value'];
			ob_start();
			?>
			<input type="password" name="<?php echo $name;?>" id="<?php echo $name.'_id';?>" value="<?php if(!empty($value))echo esc_attr($value);?>"/>
			<?php 
			$html=ob_get_clean();
			return $html;	
		}
		static function text($name,$field){
			if(!isset($field['value'])){
				$value=$field['default'];
			}else $value=$field['value'];
			ob_start();
			?>
					<input type="text" name="<?php echo $name;?>" id="<?php echo $name.'_id';?>" value="<?php if(!empty($value))echo esc_attr($value);?>"/>
					<?php 
					$html=ob_get_clean();
					return $html;	
				}
		static function select($name,$field){
			if(!isset($field['value'])){
				$value=$field['default'];
			}else $value=$field['value'];
			ob_start();
			//print_r($field);
			?>
			<select name="<?php echo $name;?>" id="<?php echo $name.'_id';?>" class="<?php echo $name.'_class';?>">
			<?php 
			if(isset($field['values'])){
			foreach($field['values'] as $k=>$v){
			?>
			<option <?php if($value==$k)echo 'selected="selected"';?> value="<?php echo $k;?>"><?php echo $v;?></option>
			<?php }
			}else {

			$start=$field['min'];$end=$field['max'];
			for($i=$start;$i<=$end;$i++){
			?>
			<option <?php if($value==$i)echo 'selected="selected"';?> value="<?php echo $i;?>"><?php echo $i;?></option>
			<?php 
			}
			?>
			<?php }?>
			</select>
			<?php 
			$html=ob_get_clean();
			return $html;	
		}
		static function radio_list($name,$field){
			if(!isset($field['value'])){
				$value=$field['default'];
			}else $value=$field['value'];
			ob_start();
			?>
			<ul class="my_radio_list">
				<?php 
				if(!empty($field['values'])){
					foreach($field['values'] as $key=>$obj){
					?>
					<li>
						<input  <?php if($key==$value) echo 'checked="checked"';?> class="<?php echo $name.'_class';?>"  type="radio" value="<?php echo $key;?>" name="<?php echo $name;?>"/>
						<label for="<?php echo $name;?>"><?php echo $obj;?></label>
					</li>
					<?php 
					}
				}
				?>
			</ul>
			<?php 
			$html=ob_get_clean();
			return $html;
		}
		static function textarea($name,$field){
			if(!isset($field['value'])){
				$value=$field['default'];
			}else $value=$field['value'];
			ob_start();
			?>
			<textarea class="my_textarea" name="<?php echo $name;?>" id="<?php echo $name.'_id';?>"><?php if(!empty($value))echo esc_textarea($value);?></textarea>
			<?php 
			$html=ob_get_clean();
			return $html;
			}
	static function checkbox_list($name,$field){
		if(!isset($field['value'])){
			$value=$field['default'];
		}else $value=$field['value'];
		ob_start();
		?>
				<ul class="my_checkbox_list">
					<?php 
					if(!empty($field['values'])){
						foreach($field['values'] as $key=>$obj){
						?>
						<li>
							<input  <?php if(in_array($key,$value)) echo 'checked="checked"';?> class="<?php echo $name.'_class';?>"  type="checkbox" value="<?php echo $key;?>" name="<?php echo $name;?>[]"/>
							<label for="<?php echo $name;?>[]"><?php echo $obj;?></label>
						</li>
						<?php 
						}
					}
					?>
				</ul>
				<?php 
				$html=ob_get_clean();
				return $html;
			}
		}
}