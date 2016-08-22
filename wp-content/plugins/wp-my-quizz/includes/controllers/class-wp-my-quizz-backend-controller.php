<?php if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
if(!class_exists("Class_Wp_My_Quizz_Backend_Controller")){
class Class_Wp_My_Quizz_Backend_Controller{
		private $data;	
		function Class_Wp_My_Quizz_Backend_Controller(){
			$this->data['template_dir']=WP_MY_QUIZZ_PLUGIN_VIEWS.'admin/';
			
		}
		function init(){
		
		}
		function add_new_quiz(){
			extract($this->data);
			$file=$template_dir.'/new/metabox_new.php';
			require_once $file;
		}
		function metabox(){
			extract($this->data);
			$file=$template_dir.'/metabox.php';
			require_once $file;
			
		}
		function options(){
			$my_preview=@$_GET['my_preview'];
			if(!empty($my_preview)&&$my_preview==1){
				
			}else {
				extract($this->data);
				$file=$template_dir.'/options.php';
				require_once $file;
			}
		}
		function ajax_new(){
			$action=@$_POST['my_action'];
			$nonce=@$_POST['nonce'];
			//echo 'Action = '.$action;
			if(!wp_verify_nonce($nonce,'wp_my_quizz_action_new'))die('security');
			$supported=array('get_image_size','save_post');
			//echo 'nonce ok';
			if(!in_array($action, $supported))die('not supported');
			//echo 'Call action';
			$this->$action();
			die('');
		}
		private function save_post(){
			$_POST['my_is_quiz_post']=1;
			$data=@$_POST['data'];
			wp_parse_str($data,$data_arr);
			
			$options=my_quizz_get_options();
			if(empty($options['num_results'])){
				$options['num_results']=9;
				};
			if(empty($options['num_questions'])){
				$options['num_questions']=9;
			};
			$max_results=$options['num_results'];
			$max_questions=$options['num_questions'];
			$post_id=@$_POST['post_id'];
			$post_title=$data_arr['quiz_title'];
			$num_results=$data_arr['my_num_results'];
			$num_q=$data_arr['my_num_question'];
			$ret['error']=0;
			$send_num_results=$num_results;
			$send_num_questions=$num_q;
			
			if($num_results>$max_results)$send_num_results=$max_results;
			if($num_q>$max_questions)$send_num_results=$max_questions;
			$has_post=false;
			if(!empty($post_id))$has_post=true;
			/**
			 * Save post
			 */
			if(empty($post_id)){
				$post_id=wp_insert_post(array(
					'post_title'=>$post_title,
					'post_status'=>'draft'
				)
				);
			}else {
				wp_update_post(array(
					'ID'=>$post_id,
					'post_title'=>$post_title,
					
				));
			}
			global $my_quizz_is_quizz;
			update_post_meta($post_id,$my_quizz_is_quizz,1);
			$prev_num_results='';
			$prev_num_questions='';
			$prev_num_answers='';
			$prev_num_results_arr=array();
			$prev_num_questions_arr=array();
			$prev_num_answers_arr=array();
			$ret_debug=array();
			global $my_quizz_final_result_keys;
			global $my_quizz_questions_keys;
			global $my_quizz_answers_keys;
			$ret_debug['meta_data']=array(
				'num_q'=>$num_q,
				'num_res'=>$num_results,
				'send_q'=>$send_num_questions,
				'send_r'=>$send_num_results
			);			
			if($has_post){
				$prev_num_results_arr=get_post_meta($post_id,$my_quizz_final_result_keys,true);
				$prev_num_results=max($prev_num_results_arr);
				$prev_num_questions_arr=get_post_meta($post_id,$my_quizz_questions_keys,true);
				$prev_num_questions=max($prev_num_questions_arr);
				$prev_num_answers_arr=get_post_meta($post_id,$my_quizz_answers_keys,true);
				$prev_num_answers=max($prev_num_answers_arr);
				$ret_debug['pre_post']=array(
					'questions_arr'=>$prev_num_questions_arr,
					'questions'=>$prev_num_questions,
					'results_arr'=>$prev_num_results_arr,
					'results'=>$prev_num_results,
					'answers_arr'=>$prev_num_answers_arr,
					'answers'=>$prev_num_answers
				);
				
			}
			/**
			 * add format
			 */
			$format=$data_arr['my_answers_format'];
			if(!in_array($format,array(1,2)))$format=2;
			global $my_quizz_quizz_format;
			update_post_meta($post_id,$my_quizz_quizz_format,$format);
			/**
			 * Add results
			 * @var unknown_type
			 */
			global $my_quizz_final_results;
			$old_results=array();
			for($i=1;$i<=$send_num_results;$i++){
				$arr=array();
				$old_results[]=$i;
				$title=$data_arr['res_title_'.$i];
				$descr=$data_arr['res_descr_'.$i];
				$image=$data_arr['res_image_id_'.$i];
				$arr['title']=$title;
				$arr['descr']=$descr;
				$arr['image']=$image;
				$new_key=$my_quizz_final_results.$i;
				update_post_meta($post_id,$new_key,$arr);
			}
			/**
			 * Delete unused keys
			 */
			if($has_post){
				if($prev_num_results>$send_num_results){
					
					$start=$send_num_results+1;
					$deleted_reults=array();
					$ret_debug['delete_results']=array(
						'do'=>'Yes',
						'start'=>$start,
						
					);
					for($i=$start;$i<=$prev_num_results;$i++){
						$deleted_reults[]=$i;
						$new_key=$my_quizz_final_results.$i;
						delete_post_meta($post_id,$new_key);
					}
					$ret_debug['deleted_results']=$deleted_reults;
				}
				
			}
			update_post_meta($post_id, $my_quizz_final_result_keys, $old_results);
	
			/**
			 * Add questions
			 * @var unknown_type
			 */
			global $my_quizz_questions;
			global $my_quizz_answers;
			$old_results=array();
			$old_results_1=array();
			$c_a=1;
			for($i=1;$i<=$num_q;$i++){
				$old_results[]=$i;
				$title=$data_arr['q_title_'.$i];
				$image=$data_arr['q_image_id_'.$i];
				$arr=array();
				$new_key=$my_quizz_questions.$i;
				$arr['title']=$title;
				$arr['image']=$image;
				update_post_meta($post_id, $new_key, $arr);
				for($j=1;$j<=$num_results;$j++){
					$old_results_1[]=$c_a;
					$image='';
					$color='';
					$arr=array();
					if($format==2){
						$color_c=$j-1;
						$color=$options['colors'][$color_c];
					}else {
						$image=$data_arr['a_image_id_'.$i.'_'.$j];	
					}
					$title=$data_arr['a_'.$i.'_'.$j];
					$final_res=$j;
					$question=$i;
					$arr['title']=$title;
					$arr['color']=$color;
					$arr['image']=$image;
					$arr['final_res']=$final_res;
					$arr['question']=$question;
					$new_key=$my_quizz_answers.$c_a;
					update_post_meta($post_id, $new_key, $arr);
		
					$c_a++;
				}
			}
			update_post_meta($post_id, $my_quizz_questions_keys, $old_results);
			update_post_meta($post_id, $my_quizz_answers_keys, $old_results_1);
			/**
			 * Delete unused question answers
			 */
			if($has_post){
				if($prev_num_questions>$send_num_questions){
					$deleted_questions=array();
					$start=$send_num_questions+1;
					for($i=$start;$i<=$prev_num_questions;$i++){
						$new_key=$my_quizz_questions.$i;
						delete_post_meta($post_id,$new_key);
						$deleted_questions[]=$i;
					}
					$ret_debug['deleted_questions']=$deleted_questions;
					
				}
				$total_prev_answers=$prev_num_results*$prev_num_questions;
				$total_answers=$send_num_results*$send_num_questions;
				$ret_debug['answers']=array(
					'prev_total_answers'=>$total_prev_answers,
					'new_total_answers'=>$total_answers
				);
				$deleted_answers=array();
				if($total_prev_answers>$total_answers){
					$start=$total_answers+1;
					for($i=$start;$i<=$total_prev_answers;$i++){
						$new_key=$my_quizz_answers.$i;
						delete_post_meta($post_id,$new_key);
						$deleted_answers[]=$i;
					}
					$ret_debug['answers']['deleted']=$deleted_answers;
				
				}
			}
	
			$ret['post_id']=$post_id;
			$ret['debug']=$ret_debug;
			/**
			 * Add results
			 * @var unknown_type
			 */
			$ret['msg']=__("Quiz has been saved !","my_quizz_domain");
			echo json_encode($ret);
		}
		private function get_image_size(){
			$size=@$_POST['size'];
			$id=@$_POST['id'];
			//echo 'Action '.$size.' id '.$id;
			$ret['url']='';
			if($size=='small'){
				$image=wp_get_attachment_image_src($id,'my-180x180');
				if(($image[1]!=180)||($image[2]!=180)){
					$url=my_quizz_resize_thumb($id,array(180,180));
					$ret['url']=$url;		
				}else $ret['url']=$image[0];			
			}else {
				$image=wp_get_attachment_image_src($id,'my-600x200');
				if(($image[1]!=600)||($image[2]!=200)){
					$url=my_quizz_resize_thumb($id,array(600,200));
					$ret['url']=$url;
				}
				else $ret['url']=$image[0];
				
			}
			echo json_encode($ret);
		}
		private function get_step_2(){
			
		}
		function ajax(){
			$action=@$_POST['my_action'];
			$nonce=@$_POST['nonce'];
			if(!wp_verify_nonce($nonce,'wp_my_quizz_action'))die('');
			$supported=array('add_multiple_answer','get_final_html','delete_answer','update_answer','edit_answer','add_answer','update_question','delete_question','add_question','add_final_result','update_final_result','delete_final_result','get_attachs');
			if(!in_array($action, $supported))die('');
			$this->$action();
			die('');
		}
		private function add_multiple_answer(){
			$titles=@$_POST['titles'];
			$images=@$_POST['images'];
			$final_ress=@$_POST['final_ress'];
			$question=@$_POST['question'];
			$post_id=@$_POST['post_id'];
			if(!empty($titles)){
				if(strpos($titles,"\r\n")!==false){
					$arr=explode("\r\n", $titles);
					$arr_1=explode("\r\n",$images);
					$arr_2=explode("\r\n",$final_ress);
				}else {
					$arr=explode("\n", $titles);
					$arr_1=explode("\n",$images);
					$arr_2=explode("\n",$final_ress);
					
					
				}
				$new_arr=array();
				$new_arr_1=array();
				$new_arr_2=array();
				foreach ($arr as $k=>$v){
					if(!empty($v))$new_arr[]=$v;
				}
				foreach ($arr_1 as $k=>$v){
					if(!empty($v))$new_arr_1[]=$v;
				}
				foreach ($arr_2 as $k=>$v){
					if(!empty($v))$new_arr_2[]=$v;
				}
				extract($this->data);
				$file=$template_dir.'elements/my_answer.php';
				global $my_quizz_answers_keys;
				$total=0;
				$c1234=get_post_meta($post_id,$my_quizz_answers_keys,true);
				global $my_quizz_answers;
				if(!empty($c1234)){
					foreach($c1234 as $k=>$v){
						$new_key=$my_quizz_answers.$v;
						$arr=get_post_meta($post_id,$new_key,$arr);
						if($arr['question']==$question)$total++;
					}
				}
				//require_once $file;
				$html='';
				foreach($new_arr as $k=>$v){
					$title=$v;
					$p=$new_arr_1[$k];
					if(strpos($p,"#")!==false){
						$color=$p;
						$image=0;
					}else {
						$color="";
						$image=$p;
					}
					$final_res=$new_arr_2[$k];
					$_POST['title']=$title;
					$_POST['image']=$image;
					$_POST['color']=$color;
					$_POST['final_res']=$final_res;
					$new_id=my_quizz_add_answer();
					$total++;
					ob_start();
					require $file;
					$html.=ob_get_clean();
				}
				echo $html;
			}
		}
		private function get_final_html(){
			$post_id=@$_POST['post_id'];
			$id=@$_POST['id'];
			global $my_quizz_final_results;
			$new_key=$my_quizz_final_results.$id;
			$arr=get_post_meta($post_id,$new_key,true);
			global $my_quizz_final_result_keys;
			$total=count(get_post_meta($post_id,$my_quizz_final_result_keys,true));
			extract($arr);
			?>
			<div class="my_form_item">
				<div class="my_header"><?php echo $total.'.';if(strlen($title)>30)echo substr($title,0,30).'...';else echo $title;?></div>
				<div class="my_inner">
			<?php 	
			$file=WP_MY_QUIZZ_PLUGIN_VIEWS.'front/result.php';
			require $file;
			?>
				</div>
			</div>
				
			<?php 
		}
		private function update_answer(){
			my_quizz_update_answer();
		}
		private function edit_answer(){
			$id=@$_POST['id'];
			$post_id=@$_POST['post_id'];
			global $my_quizz_answers_keys;
			$has=false;
			$old=get_post_meta($post_id,$my_quizz_answers_keys,true);
			if(!empty($old)){
				foreach($old as $k=>$v){
					if($v==$id){
						$has=true;
						break;
					}
				}
			}
			if($has){
				global $my_quizz_answers;
				$new_key=$my_quizz_answers.$id;
				$arr=get_post_meta($post_id,$new_key,true);
				extract($arr);
				extract($this->data);
				$new_id=$id;
				$file=$template_dir.'elements/my_answer_form.php';
			}
			require_once $file;
		}
		private function add_answer(){
			$title=@$_POST['title'];
			$color=@$_POST['color'];
			$image=@$_POST['image'];
			$final_res=@$_POST['final_res'];
			$question=@$_POST['question'];
			$post_id=@$_POST['post_id'];
			$new_id=my_quizz_add_answer();
			extract($this->data);
			$file=$template_dir.'elements/my_answer.php';
			global $my_quizz_answers_keys;
			$total=0;
			$c1234=get_post_meta($post_id,$my_quizz_answers_keys,true);
			global $my_quizz_answers;
			if(!empty($c1234)){
				foreach($c1234 as $k=>$v){
				 $new_key=$my_quizz_answers.$v;
				 $arr=get_post_meta($post_id,$new_key,$arr);
				 if($arr['question']==$question)$total++;
				}
			}
			require_once $file;
		}
		private function update_question(){
			my_quizz_update_question();
		}
		private function delete_question(){
			$str=my_quizz_delete_question();
			echo $str;
		}
		private function add_question(){
			extract($this->data);
			$title=@$_POST['title'];
			$image=@$_POST['image'];
			$post_id=@$_POST['post_id'];
			$new_id=my_quizz_add_question();
			global $my_quizz_questions_keys;
			$total=count(get_post_meta($post_id,$my_quizz_questions_keys,true));
			//$total++;
			$file=$template_dir.'elements/my_question.php';
			require_once $file;
			
		}
		private function set_quizz(){
			
		}
		private function update_final_result(){
			my_quizz_update_final_result();
		}
		private function delete_answer(){
			my_quizz_delete_answer();
		}
		private function delete_final_result(){
			$post_id=@$_POST['post_id'];
			$id=@$_POST['id'];
			$str=my_quizz_delete_final_result();
			echo $str;	
			//echo 'Delete '.$id.' '.$post_id;
		}
		private function add_final_result(){
			extract($this->data);
			$title=@$_POST['title'];
			$descr=@$_POST['descr'];
			$post_id=@$_POST['post_id'];
			$image=@$_POST['image'];
			$new_id=my_quizz_add_final_result();
			$file=$template_dir.'elements/my_final_result.php';
			global $my_quizz_final_result_keys;
			$total=count(get_post_meta($post_id,$my_quizz_final_result_keys,true));
			
			require_once $file;
				
			
		}
		private function get_attachs(){
			extract($this->data);
			$post_id=@$_POST['post_id'];
			$my_is_ajax_call=true;
			$file=$template_dir.'elements/my_attachs.php';
			require_once $file;
				
		}
	}
}
	