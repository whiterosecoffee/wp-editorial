<?php
/*
Plugin Name:  Title Split Testing
Author: Wholegrain Digital
Author URI: http://www.wholegraindigital.com
Description: This plugin is designed to help you improve readership of your website by showing you which headlines or page titles get your readers attention and make them want to read more.

Now you can split test different titles and headlines to find out which has the best Click Through Rate (CTR) just like leading news corporations do, all from the comfort of your WordPress control panel.

Version: 1.0.4

*/



/**
 * START OF ADMIN POST PAGE SECTION
 */

/* Small plugin hack to move metabox under title */
add_action( 'admin_head', 'split_title_admin_head' );

function split_title_admin_head()
{
    ?>
   
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var split_title = jQuery('#title_split_sectionid');
            jQuery('#titlediv #titlewrap').after(split_title.show());
            
        });
        </script>

    <style type="text/css">
    	#title_split_sectionid
    	{
    		display:none;
    	}
        #titlewrap{
           /* display:none;*/
        }
    </style>
    <?php   
}
	 
 
 
/* Define the title inputs box */
add_action( 'add_meta_boxes', 'title_split_add_custom_box',0);


/* Add action for save */
add_action( 'save_post', 'title_split_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function title_split_add_custom_box() {
    global $post_type;
    
    
    
    $tst_enabled_for_str = get_option('tst_enabled_for');
    $tst_enabled_for = explode(',',$tst_enabled_for_str);

    if(in_array($post_type, $tst_enabled_for)){
        add_meta_box( 
            'title_split_sectionid',
            'Alternative Titles',
            'title_split_inner_custom_box',
            $post_type
        );
  }
    
}



/* Prints the box content */
function title_split_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'title_split_noncename' );
  
  $meta = get_post_meta($post->ID, 'titles');
  
  $additional_titles_count = get_option('tst_title_count');
  
  $tId = get_post_meta($post->ID,'default_title', '-1');
  $activated = get_post_meta($post->ID, 'is_activated');
  $activated = $activated[0];
  if($tId!=null){$activated=1;}
  if($activated){
      echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#title").attr("readonly", "readonly");});</script>';
  }
  

   if($tId[0]<1) {

        echo '<p><input name="default_title" type="radio" value="0" '.($tId[0]=='0'?'checked="checked"':"").'/>&nbsp;Title 1&nbsp;&nbsp;
            <em>Views:&nbsp;'.(isset($meta[0][0]['views'])?$meta[0][0]['views']:"0").'&nbsp;&nbsp;
            Clicks:&nbsp;'.(isset($meta[0][0]['clicks'])?$meta[0][0]['clicks']:"0").'&nbsp;&nbsp;
            CTR:&nbsp;'.round(isset($meta[0][0]['views'])&&$meta[0][0]['views']!=0?($meta[0][0]['clicks']/$meta[0][0]['views'])*100:0,2).'%</em></p>';
            
    } else {
    
        echo '<p><input name="default_title" type="radio" value="0" '.($tId[0]=='0'?'checked="checked"':"").'/>&nbsp;Title 1:
  
            <input type="text" name="title[0]" id="title0" size="70" '.($activated?"readonly":"").' '.(isset($meta[0][0]['title'])?"value='".$meta[0][0]['title']."'":"").'/>
         
		    <em>Views:&nbsp;'.(isset($meta[0][0]['views'])?$meta[0][0]['views']:"0").'&nbsp;&nbsp;
            Clicks:&nbsp;'.(isset($meta[0][0]['clicks'])?$meta[0][0]['clicks']:"0").'&nbsp;&nbsp;
            CTR:&nbsp;'.round(isset($meta[0][0]['views'])&&$meta[0][0]['views']!=0?($meta[0][0]['clicks']/$meta[0][0]['views'])*100:0,2).'%</em></p>';
 
    }
  
    for($i=1;$i<$additional_titles_count;$i++){
         echo '<input name="default_title" type="radio" value="'.$i.'" '.($tId[0]==$i?'checked="checked"':"").'
         '.(!isset($meta[0][$i]['title']) || $meta[0][$i]['title']==""?"disabled=\"disabled\"":"").'/>&nbsp;<label for="title1">Title '.($i+1).':</label>
       
        <input type="text" name="title['.$i.']" id="title'.$i.'" size="70" '.($activated?"readonly":"").' '.(isset($meta[0][$i]['title'])?"value='".$meta[0][$i]['title']."'":"").'/>
       
	    <em>Views:&nbsp;'.(isset($meta[0][$i]['views'])?$meta[0][$i]['views']:"0").'&nbsp;&nbsp;
        Clicks:&nbsp;'.(isset($meta[0][$i]['clicks'])?$meta[0][$i]['clicks']:"0").'&nbsp;&nbsp;
        CTR:&nbsp;'.round(isset($meta[0][$i]['views'])&&$meta[0][$i]['views']!=0?($meta[0][$i]['clicks']/$meta[0][$i]['views'])*100:0,2).'%</em></p>';
  
    }
  
    echo '<p class="submit"><input class="cancel button-secondary" type="submit" name="reset_stats" value="Reset" /></p>';
 
}



/* When the post is saved, saves our custom data */
function title_split_save_postdata( $post_id ) {
  // check if $post_id is just a revision id and if so get the parent id
  if($parent_id = wp_is_post_revision($post_id)){
    $post_id = $parent_id;
  }  
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['title_split_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
 
  if ( !current_user_can( 'edit_post', $post_id ) )
	  return;
  
  
  // OK, we're authenticated: we need to find and save the data
  if(isset($_POST['reset_stats']) and $_POST['reset_stats']=='reset'){
      $meta = array();
  }else{
      $meta = get_post_meta($post_id,'titles');
  }
  
  foreach($_POST as $index=>$post)
  {
  	$_POST[$index] = str_replace(array("'",'"'),"`",$post);
  }
 
    $additional_titles_count = get_option('tst_title_count');
  
    $titles = array();
    
    if(strlen($_POST['title'][0])>0){
        $titles[0]['title']= $_POST['title'][0];
    }else{
        $titles[0]['title'] = $_POST['post_title'];
    }
    
    $titles[0]['clicks'] = isset($meta[0][0]['clicks'])?$meta[0][0]['clicks']:0;
    $titles[0]['views'] = isset($meta[0][0]['views'])?$meta[0][0]['views']:0;
 
    for($i=1;$i<$additional_titles_count;$i++){
        $titles[$i]['title'] = $_POST['title'][$i];
        $titles[$i]['clicks'] = isset($meta[0][$i]['clicks'])?$meta[0][$i]['clicks']:0;
        $titles[$i]['views'] = isset($meta[0][$i]['views'])?$meta[0][$i]['views']:0;
    }
  	
  	
  //Fix for the weird bug in update_post_meta() 
  if (get_post_meta($post_id,'titles') == "")
  {
  	add_post_meta($post_id, 'titles', $titles,false);
  }
  else
  {
  	delete_post_meta($post_id,'titles');
	add_post_meta($post_id, 'titles', $titles,false);
  }
  
}

add_action('save_post', 'wp_title_intercept', 10, 2);
function wp_title_intercept($post_id, $post)
{
  
  //get post and update it with the new default title
  //also added conditions so this doesn't get in endless loop
  if (isset($_POST['default_title']) && $_POST['default_title']>=0 && $post->post_type == "post")
  {
      if (!isset($plugin_has_updated)){static $plugin_has_updated = false;}
      if (!$plugin_has_updated)
	  {
          
          
                if(!isset($_POST['reset_stats'])){
                    
                    if($_POST['title'][0]==''){
                        $_POST['title'][0]=$post->post_title;
                    }
                    $post->post_title = $_POST['title'][$_POST['default_title']];

                    delete_post_meta($post_id,'default_title');
                    add_post_meta($post_id, 'default_title', $_POST['default_title'],false);
                    delete_post_meta($post_id,'title_saved');
                    add_post_meta($post_id, 'title_saved', "saved",false);

                    delete_post_meta($post_id,'is_activated');
                    add_post_meta($post_id, 'is_activated', "1",false);

                }else{
                    if($_POST['title'][0]!=''){
                        $_POST['post_title']=$_POST['title'][0];
                        $post->post_title = $_POST['title'][0];
                    }
                    delete_post_meta($post_id,'is_activated');
                    delete_post_meta($post_id,'title_saved');
                    delete_post_meta($post_id,'default_title');
                }

                $plugin_has_updated = true;



                wp_update_post($post);
          }
	 	  
  }
  
}
/**
 * END OF ADMIN POST PAGE SECTION
 */

/**
 * START OF FRONTPAGE SECTION
 */

add_action('init','start_session');
function start_session(){
	
   		 session_start();
 
}

add_action('init','set_title_cookie');
function set_title_cookie()
{

	if (isset($_SESSION['titles']) && count($_SESSION['titles'])>0 && !is_admin())
	{
		foreach($_SESSION['titles'] as $id => $title)
		{
			setcookie($id,$title,time()+3600*24*30);
		}
	}
}


add_action('mp_the_post','title_hook');
function title_hook($post)
{
    global $wpdb;
	//extract meta values
	$meta = get_post_meta($post->id,'titles');
	$saved = get_post_meta($post->id,'title_saved');
        
       
        
	//&& $meta[0][0]['title']!=""
	if (count($meta[0])>0  && $saved[0]!="saved" && (strpos($_SERVER['HTTP_USER_AGENT'],"Mozilla")!==false || 
                strpos($_SERVER['HTTP_USER_AGENT'],"Opera")!==false || strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")!==false || 
                strpos($_SERVER['HTTP_USER_AGENT'],"AppleWebKit")!==false) && !is_admin() )
	{
		
                         
		if (isset($_COOKIE[$post->id]))
		{
			$changed = 1;
			foreach($meta[0] as $newmeta)
			{
				if ($newmeta['title'] == $_COOKIE[$post->id])
				{
					$changed = 0;
				}
			}
			if ($changed == 1) 
			{
				//setcookie($post->id,'',time()-3600);
				unset($_COOKIE[$post->id]); 
				unset($_SESSION['titles'][$post->id]);
			}
		}
		
		$titles = $meta[0];
                
		if (isset($_COOKIE[$post->id]) )
		{
			$post->title = $_COOKIE[$post->id];
		}
		elseif (isset($_SESSION['titles'][$post->id]))
		{
			$post->title = $_SESSION['titles'][$post->id];
		}
		else {
			shuffle($meta[0]);
			//var_dump($meta);
			foreach($meta[0] as $ses)
			{
				if($ses['title']!="")
				{
					$post->title=$ses['title'];
					
					$_COOKIE[$post->id] = $ses['title'];
					$_SESSION['titles'][$post->id] = $ses['title'];
										
				}
			}
		}
		
		//incrementing clicks and views
		if ( isset($_COOKIE[$post->id]) && !isset($_SESSION['titles'][$post->id]) )
		{
			$_SESSION['titles'][$post->id] = $_COOKIE[$post->id];
		}
                
                
		
		foreach($titles as $id=>$title)
		{
			if ($_SESSION['titles'][$post->id]==$title['title'])
			{
				
				//echo $post->id;
				//echo $_SESSION['refresh_hash']." title:".md5($title['title']);
				//if( !is_single())
				if( !is_single($post->id))
				{
					//echo "in";
					$titles[$id]['views']=$titles[$id]['views']+1;
				}
				
				if (array_key_exists('HTTP_REFERER', $_SERVER) && 
					strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])!==false &&
					is_single($post->id) &&
					$_SESSION['refresh_hash']!=md5($title['title']))
				{
					//echo "CREARE";
				//	echo $post->id;
					//echo $_SESSION['refresh_hash']." title:".md5($title['title']);
					$titles[$id]['clicks']=$titles[$id]['clicks']+1;
					$_SESSION['refresh_hash'] = md5($title['title']);
					$_SESSION['last_referer'] = $_SERVER['HTTP_REFERER'];
				}
				elseif($_SESSION['last_referer']!=$_SERVER['HTTP_REFERER']) {
					if (array_key_exists('refresh_hash', $_SESSION))
					{
						// echo "DISTRUGERE";
						//echo $_SESSION['refresh_hash']." Dtitle:".md5($title['title']);
						unset($_SESSION['refresh_hash']);
					}
				}
				//if(is_single($post->id)) echo "yeeeeeee";
				
			}
			
		}
		delete_post_meta($post->id,'titles');
		add_post_meta($post->id, 'titles', $titles,false);
		
	}
	elseif($saved[0]=="saved")
	{
		//do nothing about it
	}
	
	
}
 
 
/**
 * END OF FRONTPAGE SECTION
 */

if(is_admin())
{
	add_action('admin_menu', 'tst_init');
        define("TITMP", 'title_menu_page');
}
function tst_init(){
        
		add_menu_page( 'Title Split Testing', 'Title Split Testing', 'administrator','titlepage', TITMP, plugins_url( 'title-split-testing-for-wordpress/icon.png' ) );
 
			

        add_submenu_page('titlepage' , 'FAQ', 'FAQ', 'administrator', 'titlepage', TITMP);
        add_submenu_page('titlepage','Settings', 'Settings', 'manage_options', 'titlepage_settings', 'title_menu_settings'); 
        
   
}



function title_menu_settings(){
    if(isset($_POST['tst_submit']) and $_POST['tst_submit']=='submit'){
        update_option("tst_enabled_for", implode(',', $_POST['tst_enabled_for']));
        update_option("tst_title_count",  $_POST['tst_title_count']);
    }
    
    $args=array(  
    'public'   => true,  
    '_builtin' => false  
    );  
    $output = 'objects'; // names or objects, note names is the default  
    $operator = 'and'; // 'and' or 'or'  
    $post_types = get_post_types($args,$output,$operator);  
    
    $tst_enabled_for_str = get_option('tst_enabled_for');
    $tst_enabled_for = explode(',',$tst_enabled_for_str);
    
    $tst_title_count = get_option('tst_title_count');
    ?>
    <div class="wrap">
    	
    	<div id="tstp-icon" style="background: url(' <?php echo plugins_url( "title-split-testing-for-wordpress/icon32.png" ) ?> ') no-repeat;" class="icon32"></div>
    	
		<h2>Title Split Testing: Settings</h2>
    	
		<form action="" method="POST">
    	
    		<p>This plugin enables you to test different page and post titles to find out which ones achieve the best click-through rates and are therefore more interesting for your readers.</p>
    		
    		<p>Use the options below to choose how you would like to use the plugin:</p>
   		
    		<table class="form-table">
    		
    			<tbody>
    			
    				<tr valign="top">
    					<th scope="row">
    						<label for="tst_title_count">Headings per post</label>
    					</th>
    					<td>
    						<select name="tst_title_count" style="width:120px">
    						
    							<?php for ($i=1; $i<=10; $i++){?>
    							<option value="<?php echo $i;?>" <?if($tst_title_count[0] == $i){echo "selected";}?>><?php echo $i;?></option>
    							<?php }?>
    						</select>
    						<p class="description">How many headings would you like to be able to test on each page? (We recommend 2 or 3.)</p>
    					</td>
    				</tr>
    				
    				<tr valign="top">
    					<th scope="row">
    						<label for="tst_enabled_for[]">Post types</label>
    					</th>
    					<td>
    						<select name="tst_enabled_for[]" multiple="" style="height: 150px;">
    						
    							<optgroup label="Native post types">
   							
    								<option value="post" <?php if(in_array('post', $tst_enabled_for)){echo "selected";}?>>Posts</option>
    								<option value="page" <?php if(in_array('page', $tst_enabled_for)){echo "selected";}?>>Pages</option>
    							</optgroup>
    							
    							<?php if (sizeof($post_types)>0){?>
    							
    							<optgroup label="Custom post types">
    							
    								<?php foreach ($post_types as $post_type ) {  
    								
    									$active = '';
    									if(in_array($post_type->name, $tst_enabled_for)){$active= " selected";}
    									echo '<option value="'.$post_type->name.'"'.$active.'>'.$post_type->labels->name.'</option>';
    								} ?>
    								
    							</optgroup>
    							
    							<?php }?>
    						</select>
    						<p class="description">Enable testing on the following post types. Hold down CTRL to select more than one.</p>
    					</td>
    				</tr>
    				
    			</tbody>
    		
    		</table><!-- .form-table -->
            
            <div class="submit">
            	<input class="button-primary" type="submit" value="Save Settings" />
            	<input type="hidden" name="tst_submit" value="submit" />
            </div>
            
          </form>    
	</div><!-- .wrap -->  
    <?php
}

function title_menu_page(){
      echo '
   
   <div class="wrap">
   
   		<div id="tstp-icon" style="background: url(' . plugins_url( "title-split-testing-for-wordpress/icon32.png" ) . ') no-repeat;" class="icon32"></div>
 

<h2>Title Split Testing: FAQ</h2>

		<h3 class="title">How does it work?</h3>

		<p>You set up multiple competing titles for a page or post.  Then when someone visits your website, it randomly shows different titles to different visitors and records the percentage of people that click on each title.  When enough people have visited your website, you can compare the data to see which title had the highest percentage of people clicking through to read the full post/page.</p>

		<p>You can then choose the winning title and the plugin will display that title to all visitors in future.
		
		<p>If you want to repeat a test, you can simply click the Reset Data button inside the post/page editor and start the testing all over again.</p>
		
		<h3 class="title">How many different titles can I test at one time?</h3>
		
		<p>You can test choose how many title variations you want to eb able to test in the plugin settings.  We recommend 2 or 3 different titles but you can test more if you like.</p>
		
		<h3 class="title">Can I edit the titles that I am testing part way through a test?</h3>
		
		<p>Yes you can, but in order that the data is accurate you should click the Reset Data button to start the test again.</p>
		
		<h3 class="title">Does it update the post URL?</h3>
		
		<p>No.  The post URL does not change when you test different titles, or when you select your final title.  This is because you may have links pointing to that page/post and you may not want to change the URL for SEO reasons.  If you want to change the post/page URL, you can do so manually just like normal in WordPress.</p>
		
		<h3 class="title">Will readers get confused if the titles keep changing?</h3>
		
		<p>No, the plugin displays different titles to different visitors, but each visitor will always see the same set of titles, so they will not be aware that you are split testing.  Only after you have completed testing and chosen your winning title can readers see that the title changed.  This will only happen if they previously saw a title that was NOT selected and they refresh their browser.</p>

		<h3 class="title">Will Google get confused by the changing titles?</h3>
		
		<p>No.  The plugin only displays alternative titles to real website visitors using common web browsers. Google only sees the main title of your post/page and is not affected by the spit testing process until you choose the winning title following your test.  When you do this, Google will just see this as if you updated the title manually and will index the new title.</p>
		
		<h3 class="title">Why does the number of views appear higher than my actual site traffic?</h3>
		
		<p>The plugin measures the number of "Views" based on the number of times a link to the target page is displayed on pages viewed by your visitors.  Sometimes there can be multiple links to a post or page on the same page (such as footer and sidebar links), aswell as links that do not include the actual post title such as "Read More" links.   All of these links are counted as "Views".</p>
		
		<p>We have found that for the majority of websites this does not have any siginifcant effect on determining the winning headline.</p>
		
		<h3 class="title">Can the plugin update navigation links?</h3>
		
		<p>No. The plugin is not able to update links unless they are dynamically generated by the post/page title.  We ask that you use your judgement to decide whether your theme will allow accurate split testing.</p>
		
		<h3 class="title">Who created this plugin?</h3>
		
		</p>This plugin has been developed by Wholegrain Digital and donated to the WordPress community.  If you have suggestions for the improvement of this plugin, please email <a href="mailto:plugins@wholegraindigital.com" target="_blank">plugins@wholegraindigital.com</a>.</p>
	
	</div>';}


?>