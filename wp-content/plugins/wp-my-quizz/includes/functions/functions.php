<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
global $my_quizz_options;
global $my_quizz_options_name;
$my_quizz_options_name='my_quizz_options';
global $my_quizz_post_meta_prefix;
$my_quizz_post_meta_prefix='_my_quizz_';
global $my_quizz_is_quizz;
$my_quizz_is_quizz=$my_quizz_post_meta_prefix.'_is_quizz';
global $my_quizz_quizz_format;
$my_quizz_quizz_format=$my_quizz_post_meta_prefix.'_my_answers_format'; 
global $my_quizz_final_result_keys;
$my_quizz_final_result_keys=$my_quizz_post_meta_prefix.'_final_results_keys';
global $my_quizz_final_results;
$my_quizz_final_results=$my_quizz_post_meta_prefix.'_results_';
global $my_quizz_questions_keys;
$my_quizz_questions_keys=$my_quizz_post_meta_prefix.'_question_keys';
global $my_quizz_questions;
$my_quizz_questions=$my_quizz_post_meta_prefix.'_question_';
global $my_quizz_answers_keys;
global $my_quizz_answers;
$my_quizz_answers_keys=$my_quizz_post_meta_prefix.'_answers_keys_';
$my_quizz_answers=$my_quizz_post_meta_prefix.'_answers_';
function my_quizz_resize_thumb($id,$size){
	$large_image_url = wp_get_attachment_image_src( $id, 'full');
	$large_image_url=$large_image_url[0];
	$path = str_replace(site_url('/'), ABSPATH, $large_image_url);
    
	$name_p = explode(".",$path);
    $ext = ".".end($name_p);
    /*echo 'Large image url '.$large_image_url;
	echo 'Name p '.$name_p;
	echo 'Ext '.$ext;
	echo 'Path'.$path;*/
    
    
	$new_file_name=str_replace($ext, '-'.implode("x", $size).$ext, $path);
	//echo $new_file_name;
	if(file_exists($new_file_name)){
		//echo 'Resized image exists';
		$thumb = str_replace(ABSPATH, site_url('/'), $new_file_name);
		return $thumb;
    	
	}
	
	//$my_image_meta_data=get_post_meta($v['image'],"_wp_attachment_metadata",true);
	/*if(!empty($my_image_meta_data)){
		$file=$my_image_meta_data['file'];
		$file_p=explode(".",$file);
		$file_ext=".".end($file_p);
		$file_new=str_replace($file_ext,'-'.implode("x", $size).$ext,$file);
		$my_size_str=implode("x",$size);
		$mime_type=$my_image_meta_data[]
		$my_image_meta_data['sizes']['my-'.$my_size_str]=array(
						'file'=>$file_new,
						'width'=>$size[0],
						'height'=>$size[1],
						'mime-type'=>$mime_type,
		
						
					);
	}*/
	//$image_1=wp_get_attachment_image_src($id);
	//$path=$image_1[0];
	 
	//$name_p = explode(".",$path);
    //$ext = ".".end($name_p);
    $thumbpath = str_replace($ext, '-'.implode("x", $size).$ext, $path);
    //if(file_exists($thumbpath)) return $thumbpath;
    $image = wp_get_image_editor( $path );
    if ( ! is_wp_error( $image ) ) {
        $image->resize( $size[0], $size[1], true );
        $image->save( $thumbpath );
    }
    $thumb=$thumbpath;
    //$thumb = sensitive_dynamic_thumb($path, $size);
    $thumb = str_replace(ABSPATH, site_url('/'), $thumb);
    
    return $thumb;

	
}
function my_quizz_get_options(){
	global $my_quizz_options;
	global $my_quizz_options_name;
	$my_quizz_options=get_option($my_quizz_options_name);
	if(empty($my_quizz_options['labels'])){
		$my_quizz_options['labels']=array(
			'i_got'=>__("I got","my_quizz_domain"),
			'you_got'=>__("You got","my_quizz_domain"),
			'share_results'=>__("SHARE YOUR RESULTS","my_quizz_domain")
		);
		//$arr['tweet_format']="";
	}
	if(empty($my_quizz_options['share_enabled'])){
		$my_quizz_options['share_enabled']=array(
			'facebook'=>1,
			'twiiter'=>1,
			'email'=>1
		);
	}
	return $my_quizz_options;
}
function my_quizz_update_option($arr){
	global $my_quizz_options_name;
	
	update_option($my_quizz_options_name, $arr);
}
function my_quizz_calculate_result($a,$post_id){
	global $my_quizz_answers_keys;
	global $my_quizz_answers;
	global $my_quizz_final_result_keys;
	global $my_quizz_final_results;
	$error=false;
	$final=get_post_meta($post_id,$my_quizz_final_result_keys,true);
	$count=count($final)-1;
	$res_key='';
	$count_res=array();
	$old=get_post_meta($post_id,$my_quizz_answers_keys,true);
	$choosed_answers=array();
	foreach($a as $k=>$v){
		if(!in_array($v, $old)){
			$error=true;
			break;
		}else {
			$new_key=$my_quizz_answers.$v;
			$answer=get_post_meta($post_id,$new_key,true);
			if(!in_array($answer['question'],$choosed_answers)){
				if(isset($answer['final_res'])){
					if(!isset($count_res[$answer['final_res']]))$count_res[$answer['final_res']]=0;
					$count_res[$answer['final_res']]++;
				}
				$choosed_answers[]=$answer['question'];
			}else {
				$error=true;
				break;
			}
			
		}	
	}
	
	if($error){
		$random=rand(0,$count);
		$new_key=$my_quizz_final_results.$random;
		$result=get_post_meta($post_id,$new_key,true);
			
	}else {
		/**
		 * Find max
		 */
		$max_val=0;
		$max_vals=array();
		$max_keys=array();
		if(!empty($count_res)){
			foreach($count_res as $k=>$v){
				if($v>$max_val){
					$max_val=$v;
					$max_vals=array($v);
					$max_keys=array($k);
					
				}else if($v==$max_val){
					$max_vals[]=$v;
					$max_keys[]=$k;
				}
			}
			if(count($max_keys)==1){
				$final_key=$max_keys[0];
			}else {
				$c=count($max_keys)-1;
				$final_res1234=rand(0, $c);
				$final_key=$max_keys[$final_res1234];
			}
			$new_key=$my_quizz_final_results.$final_key;
			$result=get_post_meta($post_id,$new_key,true);
		}
		
	}
	$image=$result['image'];
	$image_arr=wp_get_attachment_image_src($image,'my-180x180');
	$image_url=$image_arr[0];				
	$return['has_error']=$error;
	$return['max_keys']=$max_keys;
	$return['result']=$result;
	$file=WP_MY_QUIZZ_PLUGIN_VIEWS.'front/result.php';
	extract($result);
	ob_start();
	require $file;
	$html=ob_get_clean();
	$return['html']=$html;
	$return['facebook_link']=my_quizz_facebook_share($post_id, $image_url,$result);
	$return['email_link']=my_quizz_email_share($post_id,$result);
	$return['twiiter_link']=my_quizz_twitter_share($post_id, $image_url, $result);
	return $return;
	
}
function my_quizz_get_post_title($post_id){
	global $wpdb;
	$query="SELECT post_title FROM ".$wpdb->posts." WHERE ID=".$post_id;
	return $wpdb->get_var($query);
}
function my_quizz_facebook_share($post_id,$image_url,$final){
	$my_options_1234=my_quizz_get_options();
	$you_gout_1234=$my_options_1234['labels']['i_got'];
	$post_title=my_quizz_get_post_title($post_id);
	ob_start();?>https://www.facebook.com/dialog/feed?display=popup&app_id=<?php echo my_quizz_get_facebook_app_id();?>&link=<?php echo urlencode(get_permalink($post_id))?>&picture=<?php echo urlencode($image_url)?>&name=<?php  $str=$you_gout_1234.' : '.$final['title'].' '.$post_title; echo urlencode($str);?>&description=<?php echo urlencode($final['descr']);?>&redirect_uri=<?php echo urlencode(site_url().'/?my_redirect_facebook=1');?><?php $link=ob_get_clean();
	return $link;
}
function my_quizz_twitter_share($post_id,$image_url,$final){
	$tweet_len=140;
	$post_title=my_quizz_get_post_title($post_id);
	$my_options_1234=my_quizz_get_options();
	$you_gout_1234=$my_options_1234['labels']['i_got'];
	$text=$you_gout_1234.':'.$final['title'];
	$len=strlen($text);
	if($len>$tweet_len){
		$text=substr($text,0,100);
	}
	$text_1=$post_title;
	if($len+strlen($text_1)>$tweet_len){
		$rem=$tweet_len-$len-20;
		$text_1=substr($post_title,0,$rem);
		}
	$text.=' '.$text_1;	
	ob_start();?>https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink($post_id))?>&text=<?php echo urlencode($text);?>&via=<?php echo my_quizz_get_twiiter_account();?><?php 
	$link=ob_get_clean();
	return $link;
}
function my_quizz_email_share($post_id,$final){
	$my_options_1234=my_quizz_get_options();
	$you_gout_1234=$my_options_1234['labels']['i_got'];
	$post_title=my_quizz_get_post_title($post_id);
	ob_start();
	?>
	mailto:?body=<?php $str=$you_gout_1234.':'.$final['title'].' ';echo $str;//urlencode($str);?>! <?php echo get_permalink($post_id);?>&subject=<?php echo $post_title;?>
	<?php 
	$link=ob_get_clean();
	return $link;
}
function my_quizz_get_twiiter_account(){
	//return 'awtsomi';
	$options=my_quizz_get_options();
	return $options['twitter'];
}
function my_quizz_get_facebook_app_id(){
	//	return 'adbsamfbsdfgnjs';
	$options=my_quizz_get_options();
	return $options['facebook_id'];
}
function my_quizz_get_questions_answers($post_id=''){
	if(empty($post_id)){
		global $post;
		$post_id=$post->ID;
	}
	global $my_quizz_answers_keys;
	global $my_quizz_answers;
	global $my_quizz_questions_keys;
	global $my_quizz_questions;
	
	//$ret=array();
	$answers=array();
	$questions=array();
	$quest_keys=get_post_meta($post_id,$my_quizz_questions_keys,true);
	if(!empty($quest_keys)){
		foreach($quest_keys as $k=>$v){
			$new_key=$my_quizz_questions.$v;
			$arr=get_post_meta($post_id,$new_key,true);
			$questions[$v]=$arr;
			$questions[$v]['answers']=array();
		}
	}
	$ans_keys=get_post_meta($post_id,$my_quizz_answers_keys,true);
	if(!empty($ans_keys)){
		foreach($ans_keys as $k=>$v){
			$new_key=$my_quizz_answers.$v;
			$arr=get_post_meta($post_id,$new_key,true);
			$q_id=$arr['question'];
			if(isset($questions[$q_id])){
				$questions[$q_id]['answers'][$v]=$arr;
			}
		}
	}
	return $questions;
}
function my_quizz_delete_answer(){
	$id=@$_POST['id'];
	$post_id=@$_POST['post_id'];
	global $my_quizz_answers_keys;
	global $my_quizz_answers;
	$old=get_post_meta($post_id,$my_quizz_answers_keys,true);
	$has=false;
	if(!empty($old)){
		foreach($old as $k=>$v){
			if($v==$id){
				$has=true;
				unset($old[$k]);
				break;
			}
		}
	}
	if($has){
		update_post_meta($post_id, $my_quizz_answers_keys, $old);
		$new_key=$my_quizz_answers.$id;
		delete_post_meta($post_id, $new_key);
	}
}
function my_quizz_update_answer(){
	$id=@$_POST['id'];
	$title=@$_POST['title'];
	$color=@$_POST['color'];
	$image=@$_POST['image'];
	$final_res=@$_POST['final_res'];
	$question=@$_POST['question'];
	$post_id=@$_POST['post_id'];
	global $my_quizz_questions;
	global $my_quizz_questions_keys;
	global $my_quizz_answers_keys;
	global $my_quizz_answers;
	
	$old=get_post_meta($post_id,$my_quizz_questions_keys,true);
	$has=false;
	if(!empty($old)){
		foreach($old as $k=>$v){
			if($v==$question){
				$has=true;
				break;
			}
		}
	}
	if($has){
		$has_1=false;
		//$quizz_keys=$my_quizz_answers_keys.$question;
		//$old_1=get_post_meta($post_id,$quizz_keys,true);
		//if(empty($old_1))$old_1=array();
		$old=get_post_meta($post_id,$my_quizz_answers_keys,true);
		if(!empty($old)){
			foreach($old as $k=>$v){
				if($v==$id){
					$has_1=true;
					break;
				}
			}
		}
		//$new_id++;
		//$old[]=$new_id;
		//$old_1[]=$new_id;
		if($has_1){
			
			//$old_array=get_post_meta()
			$new_key=$my_quizz_answers.$id;
			$arr['title']=$title;
			$arr['color']=$color;
			$arr['image']=$image;
			$arr['final_res']=$final_res;
			$arr['question']=$question;
		//update_post_meta($post_id, $quizz_keys, $old_1);
		//update_post_meta($post_id, $my_quizz_answers_keys, $old);
		update_post_meta($post_id, $new_key, $arr);
		return $new_id;
		}
	}
	
}
function my_quizz_add_answer(){
	$title=@$_POST['title'];
	$color=@$_POST['color'];
	$image=@$_POST['image'];
	$final_res=@$_POST['final_res'];
	$question=@$_POST['question'];
	$post_id=@$_POST['post_id'];
	global $my_quizz_questions;
	global $my_quizz_questions_keys;
	global $my_quizz_answers_keys;
	global $my_quizz_answers;
	
	$old=get_post_meta($post_id,$my_quizz_questions_keys,true);
	$has=false;
	if(!empty($old)){
		foreach($old as $k=>$v){
			if($v==$question){
				$has=true;
				break;
			}
		}
	}
	if($has){
		$new_id=0;
		$quizz_keys=$my_quizz_answers_keys.$question;
		//$old_1=get_post_meta($post_id,$quizz_keys,true);
		//if(empty($old_1))$old_1=array();
		$old=get_post_meta($post_id,$my_quizz_answers_keys,true);
		if(!empty($old)){
			foreach($old as $k=>$v){
				if($v>$new_id)$new_id=$v;
			}
		}else $old=array();
		$new_id++;
		$old[]=$new_id;
		//$old_1[]=$new_id;
		
		$new_key=$my_quizz_answers.$new_id;
		$arr['title']=$title;
		$arr['color']=$color;
		$arr['image']=$image;
		$arr['final_res']=$final_res;
		$arr['question']=$question;
		//update_post_meta($post_id, $quizz_keys, $old_1);
		update_post_meta($post_id, $my_quizz_answers_keys, $old);
		update_post_meta($post_id, $new_key, $arr);
		return $new_id;
	}
	return 0;
}
function my_quizz_get_final($post_id=''){
	global $post;
	if(empty($post_id)){
		$post_id=$post->ID;
	}
	$ret=array();
	
	global $my_quizz_final_result_keys;
	global $my_quizz_final_results;
	
	$old=get_post_meta($post_id,$my_quizz_final_result_keys,true);
	if(!empty($old)){
		foreach($old as $k=>$v){
			$new_key=$my_quizz_final_results.$v;
			$arr=get_post_meta($post_id,$new_key,true);
			$title=$arr['title'];
			if(strlen($title)>30){
				$title=substr($title, 0,30).'...';
			}		
			$ret[$v]=$title;
		}
	}
	return $ret;	
}
function my_quizz_get_question($post_id=''){
	global $post;
	if(empty($post_id)){
		$post_id=$post->ID;
	}
	$ret=array();
	global $my_quizz_questions_keys;
	global $my_quizz_questions;
	$old=get_post_meta($post_id,$my_quizz_questions_keys,true);
	if(!empty($old)){
		foreach($old as $k=>$v){
		$new_key=$my_quizz_questions.$v;
		$arr=get_post_meta($post_id,$new_key,true);
		$title=$arr['title'];
		if(strlen($title)>30){
			$title=substr($title, 0,30).'...';
		}
		$ret[$v]=$title;
		}
	}
	return $ret;
}
function my_quizz_show_questions(){
	global $my_quizz_questions_keys;
	global $my_quizz_questions;
	global $post;
	$post_id=$post->ID;
	$old_results=get_post_meta($post_id,$my_quizz_questions_keys,true);
	if(!empty($old_results)){
		foreach($old_results as $k=>$v){
			$new_id=$v;
			$new_key=$my_quizz_questions.$new_id;
			$arr=get_post_meta($post_id,$new_key,true);
			extract($arr);
			$total=($k+1);
			$file=WP_MY_QUIZZ_PLUGIN_VIEWS.'admin/elements/my_question.php';
			require $file;
		}
	}
}
function my_quizz_update_question(){
	global $my_quizz_questions_keys;
	global $my_quizz_questions;
	$title=@$_POST['title'];
	$image=@$_POST['image'];
	$post_id=@$_POST['post_id'];
	$id=@$_POST['id'];
	$arr['title']=$title;
	$arr['image']=$image;
	//$has=false;
	$old_results=get_post_meta($post_id,$my_quizz_questions_keys,true);
	$has=false;
	if(!empty($old_results)){
		foreach($old_results as $k=>$v){
			if($v==$id){
				$has=true;
				unset($old_results[$k]);
				break;
			}
		}
	}
	if($has){
		$new_key=$my_quizz_questions.$id;
		update_post_meta($post_id, $new_key, $arr);
	}
}

function my_quizz_add_question(){
	global $my_quizz_questions_keys;
	global $my_quizz_questions;
	$title=@$_POST['title'];
	$image=@$_POST['image'];
	$post_id=@$_POST['post_id'];
	$new_id=0;
	$old_results=get_post_meta($post_id,$my_quizz_questions_keys,true);
	if(!empty($old_results)){
		foreach($old_results as $k=>$v){
			if($v>$new_id)$new_id=$v;
		}
	}else $old_results=array();
	$new_id++;
	$old_results[]=$new_id;
	update_post_meta($post_id, $my_quizz_questions_keys, $old_results);
	$new_key=$my_quizz_questions.$new_id;
	$arr['title']=$title;
	$arr['image']=$image;
	update_post_meta($post_id, $new_key, $arr);
	return $new_id;
}
function my_quizz_delete_question(){
	$post_id=@$_POST['post_id'];
	$id=@$_POST['id'];
	global $my_quizz_questions_keys;
	global $my_quizz_questions;
	$old_results=get_post_meta($post_id,$my_quizz_questions_keys,true);
	$has=false;
	if(!empty($old_results)){
		foreach($old_results as $k=>$v){
			if($v==$id){
				$has=true;
				unset($old_results[$k]);
				break;
			}
		}
	}
	if($has){
		update_post_meta($post_id,$my_quizz_questions_keys,$old_results);
		$new_key=$my_quizz_questions.$id;
		delete_post_meta($post_id, $new_key);
		/**
		 * Delete all answers 
		 */
		global $my_quizz_answers_keys;
		global $my_quizz_answers;
		$old=get_post_meta($post_id,$my_quizz_answers_keys,true);
		$str_deleted='';
		if(!empty($old)){
			foreach($old as $k=>$v){
				$new_key=$my_quizz_answers.$v;
				$arr=get_post_meta($post_id,$new_key,true);
				if($arr['question']==$id){
					unset($old[$k]);
					delete_post_meta($post_id, $new_key);
					if(strlen($str_deleted))$str_deleted.=",";
					$str_deleted.=$v;
				}
			}
			
			
		}
		update_post_meta($post_id,$my_quizz_answers_keys,$old);
		
		return $str_deleted;
	}
	
}
//global $my_quizz_post_meta_data;
function my_quizz_add_final_result(){
	
	$title=@$_POST['title'];
	$descr=@$_POST['descr'];
	$post_id=@$_POST['post_id'];
	$image=@$_POST['image'];
	global $my_quizz_final_result_keys;
	global $my_quizz_final_results;
	$old_results=get_post_meta($post_id,$my_quizz_final_result_keys,true);
	$new_id=0;
	if(!empty($old_results)){
		foreach($old_results as $k=>$v){
			if($v>$new_id)$new_id=$v;
		}
	}else $old_results=array();
	$new_id++;
	$old_results[]=$new_id;
	update_post_meta($post_id, $my_quizz_final_result_keys, $old_results);
	$new_key=$my_quizz_final_results.$new_id;
	$arr['title']=$title;
	$arr['descr']=$descr;
	$arr['image']=$image;
	update_post_meta($post_id, $new_key, $arr);
	return $new_id;
}
function my_quizz_update_final_result(){

	$title=@$_POST['title'];
	$descr=@$_POST['descr'];
	$post_id=@$_POST['post_id'];
	$image=@$_POST['image'];
	$new_id=@$_POST['id'];
	global $my_quizz_final_result_keys;
	global $my_quizz_final_results;
	$old_results=get_post_meta($post_id,$my_quizz_final_result_keys,true);
	/*$new_id=1;
	if(!empty($old_results)){
		foreach($old_results as $k=>$v){
			if($v>$new_id)$new_id=$v;
		}
	}else $old_results=array();
	$old_results[]=$new_id;
	update_post_meta($post_id, $my_quizz_final_result_keys, $old_results);
	*/
	$has=false;
	if(!empty($old_results)){
		foreach($old_results as $k=>$v){
			if($v==$new_id){
				$has=true;
				break;
			}
		}
	}
	if($has){
		$new_key=$my_quizz_final_results.$new_id;
		$arr['title']=$title;
		$arr['descr']=$descr;
		$arr['image']=$image;
		update_post_meta($post_id, $new_key, $arr);
	}
	echo 'New id '.$new_id;
	return $new_id;
}
function  my_quizz_delete_final_result(){
	$post_id=@$_POST['post_id'];
	$id=@$_POST['id'];
	global $my_quizz_final_result_keys;
	global $my_quizz_final_results;
	$old_results=get_post_meta($post_id,$my_quizz_final_result_keys,true);
	$has=false;
	foreach($old_results as $k=>$v){
		if($v==$id){
			$has=true;
			unset($old_results[$k]);
			break;
		}
	}
	if($has){
		$new_key=$my_quizz_final_results.$id;
		delete_post_meta($post_id, $new_key);
		update_post_meta($post_id, $my_quizz_final_result_keys, $old_results);
		global $my_quizz_answers_keys;
		global $my_quizz_answers;
		$old=get_post_meta($post_id,$my_quizz_answers_keys,true);
		$str_deleted='';
		if(!empty($old)){
			foreach($old as $k=>$v){
				$new_key=$my_quizz_answers.$v;
				$arr=get_post_meta($post_id,$new_key,true);
				if($arr['final_res']==$id){
					unset($old[$k]);
					delete_post_meta($post_id, $new_key);
					if(strlen($str_deleted))$str_deleted.=",";
					$str_deleted.=$v;
				}
			}
				
				
		}
		update_post_meta($post_id,$my_quizz_answers_keys,$old);
		
		return $str_deleted;
		
		
		
	}
		
}
function my_quizz_get_final_results(){
	global $my_quizz_final_result_keys;
	global $my_quizz_final_results;
	global $post;
	$post_id=$post->ID;
	$old_results=get_post_meta($post_id,$my_quizz_final_result_keys,true);
	if(!empty($old_results)){
		foreach($old_results as $k=>$v){
			$new_key=$my_quizz_final_results.$v;
			$arr=get_post_meta($post_id,$new_key,true);
			extract($arr);
			$new_id=$v;
			$total=($k+1);
			$file=WP_MY_QUIZZ_PLUGIN_VIEWS.'admin/elements/my_final_result.php';
			require $file;
		}
	}
}

