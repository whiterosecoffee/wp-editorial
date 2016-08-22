<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
if(!class_exists("Class_Wp_My_Quizz_Backend_Class")){
 class Class_Wp_My_Quizz_Backend_Class{	
	private $controller;
 	function Class_Wp_My_Quizz_Backend_Class(){
		
	}
	function init(){
		add_action('admin_menu',array(&$this,'admin_menu'));
		add_action('admin_enqueue_scripts',array(&$this,'admin_scripts'));
		add_action('admin_head',array(&$this,'admin_head'));
		add_action('wp_ajax_wp_my_quizz_action',array(&$this,'ajax'));
		//add_filter( 'custom_menu_order', '__return_true' );
		//add_filter('menu_order',array(&$this,'menu_order'));
		/**
		 * New ajax action
		 */
		add_action('wp_ajax_wp_my_quizz_action_new',array(&$this,'ajax_new'));
		
		add_action('add_meta_boxes',array(&$this,'add_metaboxes'));
		//add_action('wp',array($this,'menu_order'));
	}/*
	function menu_order(){
		//print_r($order);
		global $menu,$submenu;
	if(isset($submenu['edit.php'])){
			$arr=$submenu['edit.php'];
			$curr=array();
			foreach($arr as $k=>$v){
				if($v[2]=='my-quizz-add-new'){
					$curr=$v;
					unset($submenu['edit.php'][$k]);
					echo 'remove from menu '.$k;
					echo '<pre>';
					print_r($submenu);
					echo '</pre>';
					break;
				}
			}
			$new_arr=array();
			foreach($arr as $k=>$v){
				if($v['2']=='my-quizz-add-new')continue;
				$new_arr[]=$v;
				if($v[2]=='post-new.php'){
					$new_arr[]=$curr;
				}
				
			}
			$submenu['edit.php']=$new_arr;
	}	
	}*/
	function ajax_new(){
		//echo 'Ajax new';
		$file=WP_MY_QUIZZ_PLUGIN_CONTROLLERS_DIR.'/class-wp-my-quizz-backend-controller.php';
		require $file;
		$this->controller=new Class_Wp_My_Quizz_Backend_Controller();
		$this->controller->init();
		$this->controller->ajax_new();
	}
	function ajax(){
		$file=WP_MY_QUIZZ_PLUGIN_CONTROLLERS_DIR.'/class-wp-my-quizz-backend-controller.php';
		require $file;
		$this->controller=new Class_Wp_My_Quizz_Backend_Controller();
		$this->controller->init();
		$this->controller->ajax();
	}
	private function is_option_page(){
		$page=@$_GET['page'];
		if(!empty($page)){
			if($page=='my-quizz-options')return true;
		}
		return false;
	}
	private function is_add_new_page(){
		$page=@$_GET['page'];
		if(!empty($page)){
			if($page=='my-quizz-add-new')return true;
		}
		return false;
	}
	function admin_menu(){
		$title=__("Quizz options","my_quizz_domain");
		add_menu_page($title, $title, 'administrator', 'my-quizz-options',array(&$this,'options'));
		$title=__("Add new Quiz","my_quizz_domain");
		add_submenu_page('edit.php', $title, $title, 'edit_posts', 'my-quizz-add-new',array($this,'add_new_quiz'));//_page("edit.php")
		//return;
		global $menu,$submenu;
		//$arr=array(__("Add new Quiz","my_quizz_domain"),'edit_posts','edit.php?page=my-quizz-add-new',__("Add new Quiz","my_quizz_domain"));
		 
                
		/*echo '<pre>';
		print_r($menu);
		echo '</pre>';
		*/
		/*echo '<pre>';
		print_r($submenu);
		echo '</pre>';*/
		/*if(isset($submenu['edit.php'])){
			$new_arr=array();
			foreach($submenu['edit.php'] as $k=>$v){
				$new_arr[]=$v;
				if($v[2]=='post-new.php'){
					$new_arr[]=$arr;	
				}
			}	
			$submenu['edit.php']=$new_arr;
		}
		
		/*$post_id='';
		foreach($menu as $k=>$v){
			if($v[2]=='edit.php'){
				$post_id=$k;
			}
		}
		echo 'Post id '.$post_id;
		*/
		if(isset($submenu['edit.php'])){
			$arr=$submenu['edit.php'];
			$curr=array();
			foreach($arr as $k=>$v){
				if($v[2]=='my-quizz-add-new'){
					$curr=$v;
					unset($submenu['edit.php'][$k]);
					//echo 'remove from menu '.$k;
					/*echo '<pre>';
					print_r($submenu);
					echo '</pre>';
					*/
					break;
				}
			}
			$new_arr=array();
			foreach($arr as $k=>$v){
				if($v[2]=='my-quizz-add-new')continue;
				$new_arr[]=$v;
				if($v[2]=='post-new.php'){
					$new_arr[]=$curr;
				}
				
			}
			$submenu['edit.php']=$new_arr;
		}
	}
	
	function admin_scripts(){
		if($this->is_option_page()){
			$my_preview=@$_GET['my_preview'];
			if(!empty($my_preview)&&$my_preview==1){
				wp_enqueue_script("jquery");
				wp_enqueue_script("jquery-touch-pounch");
				wp_enqueue_script("wp_my_quizz_plugin_front_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'front.js');
				wp_enqueue_style("wp_my_quizz_plugin_front_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'front.css');
			
				
			}else {
				wp_enqueue_script("jquery");
			
			//wp_enqueue_script("jquery-color");
				wp_enqueue_script("wp_my_quizz_plugin_metabox_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'metabox_options.js');
				wp_enqueue_style("wp_my_quizz_plugin_admin_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'admin.css');
				wp_enqueue_style("wp_my_quizz_plugin_metabox_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'metabox.css');
			
				wp_enqueue_script("wp_my_quizz_plugin_color_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/colorpicker.js');
				wp_enqueue_script("wp_my_quizz_plugin_color_eye_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/eye.js');
				wp_enqueue_script("wp_my_quizz_plugin_color_utils_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/utils.js');
			
			}	
				
			//wp_enqueue_script("wp_my_quizz_plugin_color_layout_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/layout.js');
				
			wp_enqueue_style("wp_my_quizz_plugin_color_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'color/colorpicker.css');
			
		}
		if($this->is_add_new_page()){
			wp_enqueue_script("jquery");
			/*wp_enqueue_script('wp');*/
			//wp_enqueue_script("media");
			//wp_enqueue_style("wp_my_quizz_plugin_front_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'front.css');
			wp_enqueue_style("wp_my_quizz_plugin_min_front_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'min_front.css');
			
			wp_enqueue_media();
			wp_enqueue_script("wp_my_quizz_jquery_ui",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'ui/jquery-ui-1.9.2.custom.js');
			wp_enqueue_style("wp_my_quizz_jquery_ui_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'smoothness/jquery-ui-1.9.2.custom.css');
			wp_enqueue_script("wp_my_quizz_plugin_admin_metabox_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'metabox_new.js');
			wp_enqueue_style("wp_my_quizz_plugin_metabox_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'metabox.css');
			wp_enqueue_style("wp_my_quizz_plugin_msgs_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'msgs.css');
				
			//Color plugin
			/*wp_enqueue_script("wp_my_quizz_plugin_color_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/colorpicker.js');
			wp_enqueue_script("wp_my_quizz_plugin_color_eye_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/eye.js');
			wp_enqueue_script("wp_my_quizz_plugin_color_utils_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/utils.js');
			
			
			//wp_enqueue_script("wp_my_quizz_plugin_color_layout_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/layout.js');
			
			wp_enqueue_style("wp_my_quizz_plugin_color_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'color/colorpicker.css');
				*/
		}
		if($this->is_post_page()){
			wp_enqueue_script("jquery");
			
			//wp_enqueue_script("jquery-color");
			wp_enqueue_script("wp_my_quizz_plugin_metabox_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'metabox.js');
			wp_enqueue_style("wp_my_quizz_plugin_admin_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'admin.css');
			
			wp_enqueue_script("wp_my_quizz_plugin_color_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/colorpicker.js');
			wp_enqueue_script("wp_my_quizz_plugin_color_eye_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/eye.js');
			wp_enqueue_script("wp_my_quizz_plugin_color_utils_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/utils.js');
				
				
			//wp_enqueue_script("wp_my_quizz_plugin_color_layout_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'color/layout.js');
				
			wp_enqueue_style("wp_my_quizz_plugin_color_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'color/colorpicker.css');
			//wp_enqueue_style("wp_my_quizz_plugin_color_layout_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'color/layout.css');
			wp_enqueue_style("wp_my_quizz_plugin_front_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'front.css');
				
		}
		
		
	}
	function admin_head(){
		if($this->is_add_new_page()){
			
			
			$options=my_quizz_get_options();
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
						var o={};
						o.colors=[];
						<?php 
						if(!empty($options['colors'])){
							foreach($options['colors'] as $k=>$v){
							?>
							o.colors[o.colors.length]='<?php echo $v;?>';
							<?php 
							}
						}
						?>
						
						o.max_res=<?php if(!empty($options['num_results']))echo $options['num_results'];else echo '9';?>;
						o.max_q=<?php if(!empty($options['num_questions']))echo $options['num_questions'];else echo '9';?>;
						o.ajax_url='<?php echo admin_url('admin-ajax.php');?>';
						o.ajax_timeout=20000;
						o.ajax_action='wp_my_quizz_action_new';
						o.msg_window_timeout=3000;
						o.ajax_nonce='<?php echo wp_create_nonce('wp_my_quizz_action_new');?>';
						o.msgs={};
						o.msgs.media_title="<?php echo __("Select Image for Quiz","my_quizz_domain")?>";
						o.msgs.media_no_image="<?php echo __("An Item has no image ,plese click to add new image.","my_quizz_domain");?>";
						o.msgs.step_2_error="<?php echo __("Some fields is required,please add them !","my_quizz_domain");?>";
						o.msgs.step_3_no_image="<?php echo __("Item {1} has no image please add image!","my_quizz_domain");?>";
						o.msgs.saving_quiz="<?php echo __("Saving Quiz , please wait !","my_quizz_domain");?>";
						o.msgs.my_generate_preview="<?php echo __("Generating preview , please wait !","my_quizz_domain");?>";
						
						o.msgs.empty_title="<?php echo __("Please Enter Quiz Title !","my_quizz_domain")?>"
						myQuizMetabox_instance=new myQuizMetabox(o);
					});
			</script>
			<?php 
		}
		if($this->is_option_page()){
			$my_preview=@$_GET['my_preview'];
			if(!empty($my_preview)&&$my_preview==1){
				?>
				<style type="text/css">
					body{
						background-color:white !important;
						height:auto !important;
					}
					.my_question_image{
						width:600px;
						margin:auto;
					} 
					.my_answers{
						width:630px !important;
					}
					.my_question_image img{
						/*margin:auto;*/	
					}
					.my_question_title{
						line-height:30px !important;
						width:600px;
					}
				</style><!--  -->
				<?php 
				die('<body></body>');
			}
		}
		/*	?>
		jQuery(document).ready(function($){
					var o={};
					o.admin_ajax_url="<?php echo admin_url("admin-ajax.php");?>";
					o.nonce="<?php echo wp_create_nonce("wp_my_get_quizz_action")?>";
					o.ajax_action="wp_my_get_quizz_action";
					o.test='Testing';
					myQuizzFront_instance=new myQuizzFront(o);
					});
			<?php 
		}		}*/
		if($this->is_post_page()){
			?>
			<script type="text/javascript">
				my_quizz_admin_ajax="<?php echo admin_url("admin-ajax.php");?>";
				my_quizz_admin_nonce="<?php echo wp_create_nonce('wp_my_quizz_action');?>";
				
			</script>
			<?php 
		}
		
	}
	private function is_post_page(){
		global $pagenow;
		if(!empty($pagenow)&&($pagenow=='post-new.php'||$pagenow=='post.php'))return true;
		return false;
	}
	function add_new_quiz(){
		$file=WP_MY_QUIZZ_PLUGIN_CONTROLLERS_DIR.'/class-wp-my-quizz-backend-controller.php';
		require $file;
		$this->controller=new Class_Wp_My_Quizz_Backend_Controller();
		$this->controller->init();
		$this->controller->add_new_quiz();
	}
	function add_metaboxes(){
		$id="my_quizz_metabox";
		$title=__("Add quizz data","my_quizz_domain");
		add_meta_box($id, $title, array(&$this,'show_metabox'),'post','advanced','high');
	}
	function options(){
		$file=WP_MY_QUIZZ_PLUGIN_CONTROLLERS_DIR.'/class-wp-my-quizz-backend-controller.php';
		require $file;
		$this->controller=new Class_Wp_My_Quizz_Backend_Controller();
		$this->controller->init();
		$this->controller->options();	
	}
	function show_metabox(){
		$file=WP_MY_QUIZZ_PLUGIN_CONTROLLERS_DIR.'/class-wp-my-quizz-backend-controller.php';
		require $file;
		$this->controller=new Class_Wp_My_Quizz_Backend_Controller();
		$this->controller->init();
		$this->controller->metabox();
	}
	
}
}