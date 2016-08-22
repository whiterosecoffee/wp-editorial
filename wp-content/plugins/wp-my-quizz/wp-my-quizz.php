<?php
if (basename ( __FILE__ ) == basename ( $_SERVER ['SCRIPT_FILENAME'] ))
	die ( 'This page cannot be called directly.' );
/*
Plugin Name: Quizz Plugin
Plugin URI: http://pure-cms.com/wp-my-quizz/
Description: Quizz Plugin.Create quizzes like one on buzzfeed.com.
Version: 1.0
Author: Dragan Jordanov
Author URI: http://pure-cms.com/about-me/
*/
if(!class_exists("Wp_My_Quizz_Main")){
	/**
	 * Main plugin class
	 * @author
	 *
	 */
	class Wp_My_Quizz_Main{
		private $plugin_url;
		private $plugin_dir;
		private $backend_class;
		private $plugin_version='1.0';
		function Wp_My_Quizz_Main(){
			$this->plugin_url=plugin_dir_url(__FILE__);
			$this->plugin_dir=plugin_dir_path(__FILE__);
		}
		
		function init(){
			add_action('after_setup_theme',array(&$this,'add_image_sizes'));
			//add_action('quizk_edit_custom_box',array($this,'quick_metabox'));
			define('WP_MY_QUIZZ_PLUGIN_DIR',$this->plugin_dir);
			define('WP_MY_QUIZZ_PLUGIN_CLASS_DIR',$this->plugin_dir.'includes/class/');
				
			define('WP_MY_QUIZZ_PLUGIN_CONTROLLERS_DIR',$this->plugin_dir.'includes/controllers/');
			define('WP_MY_QUIZZ_PLUGIN_FUNCTIONS_DIR',$this->plugin_dir.'includes/functions/');
			define('WP_MY_QUIZZ_PLUGIN_VIEWS',$this->plugin_dir.'includes/views/');
			define('WP_MY_QUIZZ_PLUGIN_URL',$this->plugin_url);
			define('WP_MY_QUIZZ_PLUGIN_CSS_URL',$this->plugin_url.'assets/css/');
			define('WP_MY_QUIZZ_PLUGIN_IMAGES_URL',$this->plugin_url.'assets/images/');
				
			define('WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL',$this->plugin_url.'assets/jscript/');
			$file=WP_MY_QUIZZ_PLUGIN_FUNCTIONS_DIR.'functions.php';
			add_action('wp_ajax_wp_my_get_quizz_action',array(&$this,'ajax'));
			add_action('wp_ajax_nopriv_wp_my_get_quizz_action',array(&$this,'ajax'));
			
			require_once $file;
			add_action('wp',array($this,'wp'));
			add_filter('the_content',array($this,'quizz_page'));
			add_action('save_post',array(&$this,'save_post'));
			//add_action('admin_menu',array(&$this,'admin_menu'));
			add_action('wp_enqueue_scripts',array(&$this,'wp_scripts'));
			add_action('wp_head',array(&$this,'wp_head'));
			
			if(is_admin()){
				$file=WP_MY_QUIZZ_PLUGIN_CLASS_DIR.'class-wp-my-quizz-backend-class.php';
				//echo $file;
				require_once $file;
				$this->backend_class=new Class_Wp_My_Quizz_Backend_Class();
				$this->backend_class->init();
			}
			global $pagenow;
			if($pagenow=='edit.php'){
				add_action('restrict_manage_posts',array(&$this,'my_restrict_manage_posts'));
				add_filter('posts_join',array(&$this,'my_posts_join'));
				add_filter('posts_where',array(&$this,'my_posts_where'));
				
			}
				
				
				
				
				
			
		}
		function my_restrict_manage_posts(){
			$val=@$_GET['my_filter_quizz_post_1234'];
			if(empty($val))$val=0;
			else if($val!=1)$val=0;
			?>
			<select name="my_filter_quizz_post_1234">
					<option value=""><?php echo __("--- Filter Quiz posts / All posts now ---","my_quizz_domain");?></option>
					<option <?php if($val==1)echo 'selected="selected"';?> value="1"><?php echo __("Only Quiz Posts","my_quizz_domain");?></option>
				</select>	
					
				
			<?php 
		}
		function my_posts_join($join){
			global $wpdb;
			$val=@$_GET['my_filter_quizz_post_1234'];
			if(empty($val))$val=0;
			else if($val!=1)$val=0;
			if($val==1){
				$join.=" LEFT JOIN ".$wpdb->postmeta." AS my_meta ON $wpdb->posts.ID=my_meta.post_id";
				
			}
			return $join;
		}
		function my_posts_where($where){
			global $wpdb;
			$val=@$_GET['my_filter_quizz_post_1234'];
			if(empty($val))$val=0;
			else if($val!=1)$val=0;
			if($val==1){
				global $my_quizz_is_quizz;
				$where.=" AND my_meta.meta_key='".$my_quizz_is_quizz."' AND my_meta.meta_value='1'";		
				
			
			}
			return $where;
		}
		function wp(){
			$my_redirect_facebook=@$_GET['my_redirect_facebook'];
			if(isset($my_redirect_facebook)){
			echo __("You have shared results to facebook !","my_quizz_domain");
			?>
			<script type="text/javascript">
				setTimeout(function(){
				this.close();},3000);
			</script>
			<?php 
			die('');
				
			}
		}
	function ajax(){
		$ret=array();
		$ret['error']=0;
		$ret['msg']='';
		$nonce=@$_POST['nonce'];
		$post_id=@$_POST['post_id'];
		if(!wp_verify_nonce($nonce,"wp_my_get_quizz_action")){
			$ret['error']=1;
			echo json_encode($ret);
			die('');
		}
		global $my_quizz_is_quizz;
		$val=get_post_meta($post_id, $my_quizz_is_quizz, true);
		if($val!=1){
			$ret['error']=1;
			echo json_encode($ret);
			die('');
		}	
		$a=@$_POST['a'];
		$ret_1234=my_quizz_calculate_result($a, $post_id);
		$r=array_merge($ret,$ret_1234);
		echo json_encode($r);
		die('');
	}	
	function wp_head(){
		if($this->is_quizz_post()){
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					var o={};
					o.admin_ajax_url="<?php echo admin_url("admin-ajax.php");?>";
					o.nonce="<?php echo wp_create_nonce("wp_my_get_quizz_action")?>";
					o.ajax_action="wp_my_get_quizz_action";
					o.test='Testing';
					myQuizzFront_instance=new myQuizzFront(o);
					});
			</script>
			<?php 
		}
	}	
	function wp_scripts(){
		if($this->is_quizz_post()){
			wp_enqueue_script("jquery");
			wp_enqueue_script("jquery-touch-pounch");
			wp_enqueue_script("wp_my_quizz_plugin_front_jscript",WP_MY_QUIZZ_PLUGIN_JSCRIPT_URL.'front.js');
			wp_enqueue_style("wp_my_quizz_plugin_front_css",WP_MY_QUIZZ_PLUGIN_CSS_URL.'front.css');
				
		}
	}	
	private function is_quizz_post(){
		global $wp_query;
		$post_id=$wp_query->get_queried_object_id();
		if(is_single()){
			global $my_quizz_is_quizz;
			$val=get_post_meta($post_id, $my_quizz_is_quizz, true);
			if(!empty($val)&&$val==1){
				return true;
			}
		}
		return false;
	}
	function quizz_page($content){
		global $wp_query;
		$post_id=$wp_query->get_queried_object_id();
		if(is_single()){
			global $my_quizz_is_quizz;
			$val=get_post_meta($post_id, $my_quizz_is_quizz, true);
			if(!empty($val)&&$val==1){
				ob_start();
				$file=WP_MY_QUIZZ_PLUGIN_VIEWS.'front/quizz_content.php';
				require $file;
				$html=ob_get_clean();
				$content.=$html;
			}
			
		}
		return $content;
	}
	function save_post($post_id){
		$action=@$_POST['action'];
		if(!empty($action)&&$action=='inline-save')return;
		global $my_quizz_is_quizz;
		$value=@$_POST['my_is_quiz_post'];
		if(empty($value))$val=0;
		else $val=1;
		update_post_meta($post_id, $my_quizz_is_quizz, $val);
		$file=WP_MY_QUIZZ_PLUGIN_VIEWS.'/log.txt';
		ob_start();
		echo 'Post id '.$post_id.' value '.$val;
		$str=ob_get_clean();
		$fp=fopen($file,'w');
		fwrite($fp,$str);
		fclose($fp);
	}
	function add_image_sizes(){
		add_image_size('my-180x180',180,180,array('center','center'));
		add_image_size('my-600x200',600,200,array('center','center'));
		
	}
	}
}	
if(class_exists("Wp_My_Quizz_Main")){
	global $Wp_My_Quizz_Main;
	$Wp_My_Quizz_Main=new Wp_My_Quizz_Main();
	$Wp_My_Quizz_Main->init();
}