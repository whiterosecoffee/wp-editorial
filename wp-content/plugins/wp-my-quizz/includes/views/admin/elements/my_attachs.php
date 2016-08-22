<?php
$my_attachs=get_children("post_type=attachment&post_parent=".$post_id);
		
		/*if(!empty($my_attachs)){
			?>
			<select id="my_attachments">
				<option value=""><?php echo __("----- Select Attachment for item of current section ----","my_quizz_domain");?>
			<?php foreach($my_attachs as $k=>$v){
				if(strpos($v->post_mime_type,'image')!==false){
				?>
				<option value="<?php echo $v->ID;?>"><?php echo $v->post_title;?></option>
			<?php }}?>
			</select>
			<?php 
		}else echo __("There is no attachments uploadded !","my_quizz_domain");
		*/
		?>
		<?php if(!isset($my_is_ajax_call)){?>
		<a href="#javascript" class="my_get_media"><?php echo __("Get attachment from media,")?></a>
		<div id="my_media_attachment"></div>
		<div id="my_new_media" style="display:none"></div>
		<?php }?>
		<?php 
		if(!empty($my_attachs)){
			?>		
			<?php if(!isset($my_is_ajax_call)){?>
		
					<ul>
						<li><a href="#javascript" class="my_show_att_next" my_dir="next"><?php echo __("Next attachments","my_quizz_domain");?></a>
						</li>
						<li><a href="#javascript" class="my_show_att_next" my_dir="prev"><?php echo __("Previous attachments","my_quizz_domain");?></a>
						</li>
					</ul>	
					
					<div id="my_post_atts">
		<?php }?>
					<?php 
						$showed=0;
						
						foreach($my_attachs as $k=>$v){
						if(strpos($v->post_mime_type,'image')!==false){
						
						?>
					<div id="my_attach_<?php echo $v->ID;?>" class="my_attach" style="<?php if($showed>=4)echo 'display:none;'?>" my_id="<?php echo $v->ID;?>">
						<div class="my_attach_inner">
						<h4><?php $str=$v->post_title;if(strlen($str)>10)echo substr($str,0,10).'...';else echo $str;?></h4>
						<?php 
						$image=wp_get_attachment_image_src($v->ID,'thumbnail');
						$showed++;
						?>
						<img src="<?php echo $image[0]?>" width="50px" height="50px"/><br/>
						</div>
						<div class="my_sel_att">
							<input type="radio" name="my_attachments_1234" id="my_attachments" value="<?php echo $v->ID?>"/>
						</div>
					</div>
			<?php }
					?>
					
					<?php 
			}?>		
			<?php if(!isset($my_is_ajax_call)){?>
		
			</div>
			<div class="my_clear"></div>
			<?php }?>
		<?php }else {
			if(!isset($my_is_ajax_call)){?>
					
								<ul>
									<li><a href="#javascript" class="my_show_att_next" my_dir="next"><?php echo __("Next attachments","my_quizz_domain");?></a>
									</li>
									<li><a href="#javascript" class="my_show_att_next" my_dir="prev"><?php echo __("Previous attachments","my_quizz_domain");?></a>
									</li>
								</ul>	
								
								<div id="my_post_atts">
								</div>
								<div class="my_clear"></div>
			
					
			<?php }
		}		