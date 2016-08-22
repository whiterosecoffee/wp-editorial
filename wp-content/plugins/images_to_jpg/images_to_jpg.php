<?php

/*
  Plugin Name: Convert Images to jpg
  Plugin URI: http://www.abcdefg.net
  Description: Convert Images types to jpg, specifically png to jpg
  Author: Omer Kalim
  Version: 1.0
  Author URI: http://www.omerkalim.com
 */

function images_to_jpg_init(){
    // Localization
    load_plugin_textdomain('images_to_jpg', false, dirname( plugin_basename( __FILE__ ) ));
}
//Add actions
add_action('init', 'images_to_jpg_init');

function images_to_jpg_admin() {
    include('images_to_jpg_admin.php');
}
function images_to_jpg_actions() {
    //Adding Options Page to the Admin Panel
    add_options_page("Images to JPG", "Images to JPG", 1, "images_to_jpg", "images_to_jpg_admin");
}
add_action('admin_menu', 'images_to_jpg_actions');


function image_size_prevent($file) {

    //Get Image Type of Upload
    $type = $file['type'];

    if ( $type != 'image/jpeg' && $type != 'image/jpg' && $type != 'image/gif' ) { 
        //Check if Image Type is allowed?
        $file['error'] = 'Image must be jpeg/jpg or gif.';
    }
    return $file;
}
//Hook to invoke when an Image is uploaded via Media Editor
add_filter('wp_handle_upload_prefilter', 'image_size_prevent');