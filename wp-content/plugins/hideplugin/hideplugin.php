<?php
/*
Plugin Name: Hide Widgets
Plugin URI: http://venturedive.com/
Description: Hides certain widgets if user is not admin.
Version: 0.1
Author: Tamas
Author URI: http://venturedive.com/
*/

function remove_boxes() {
 if(!current_user_can('manage_options') ) {
  remove_meta_box('tagsdiv-post_tag', 'post', 'normal');
  remove_meta_box('test-tagsdiv', 'post', 'normal');
  remove_meta_box('test-categorydiv', 'post', 'normal');
 }
}

add_action( 'admin_menu', 'remove_boxes' );

?>