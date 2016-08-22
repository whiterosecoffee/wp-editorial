<?php

/**
 * Plugin Name: Kasra Eid Greetings
 * Plugin URI: http://www.kasra.co
 * Description: Kasra Eid Greetings
 * Version: 1.0
 * Author: VentureDive
 * Author URI: http://www.venturedive.com
 * License: Private
 */


define('PAGE_SLUG', 'عيد');
define('FACEBOOK_OBJECT_TYPE', 'kasraco:eidcard');
define('FACEBOOK_ACTION_TYPE', 'kasraco:create');

// Check if the class doesn't exists
if( !class_exists('Mobile_Detect') ) {
    require_once('Mobile_Detect.php');
}

/**
*   Callback that executes for shortcode to render. If the device is mobile, it renders the error page,
*   otherwise the eid greetings page.
*/
function register_shortcode() {
    $image_path = plugins_url('images/', __FILE__);

    if(is_mobile_device()) {
        include(plugin_dir_path( __FILE__ ) . 'error-page.php');
    } else {
        include(plugin_dir_path( __FILE__ ) . 'page-eid-greetings.php');
    }
}

/**
*   Detects mobile device uses the Mobile_Detect Library
*   @return true if the device is mobile.
*/
function is_mobile_device() {
    $mobile_detect = new Mobile_Detect();
    return $mobile_detect->isMobile();
}

/**
*   Registers the scripts used in the app.
*/
function register_scripts() {
    wp_register_script( 'photobooth', plugins_url( '/photobooth_min.js' , __FILE__ ), array('jquery'), '1.0.0', true );
    wp_register_script( 'fabricjs', plugins_url( '/fabric.min.js' , __FILE__ ), array('jquery'), '1.0.0', true );
    wp_register_script( 'kasra-eid-script', plugins_url( '/script.min.js' , __FILE__ ), array('photobooth', 'fabricjs'), '1.0.0', true );

    wp_localize_script( 'kasra-eid-script', 'kasra_eid_greeting', array(
            'images_url' => plugins_url('/images/', __FILE__),
            'upload_handler' => plugins_url('upload-handler.php', __FILE__),
            'card_url' => plugins_url('/cards/', __FILE__),
            'upload_url' => plugins_url('/upload/', __FILE__),
            'ajax_url' => admin_url('admin-ajax.php'),
            'fb_action_type' => FACEBOOK_ACTION_TYPE,

            'take_a_photo' => __('take a photo', 'kasra-eid-greeting'),
            'use_this_photo' => __('use this photo', 'kasra-eid-greeting'),
            're_take_photo' => __('Re-take photo', 'kasra-eid-greeting')
        )
    );
}

/**
*   Sets the type meta to FACEBOOK_ACTION_TYPE if the url contains the eid greetings page slug.
*/
function facebook_meta( $tags ) {
    if(array_key_exists('http://ogp.me/ns#url', $tags) && strpos($tags['http://ogp.me/ns#url'], '/' . strtolower(urlencode(PAGE_SLUG)) . '/')) {
        $tags['http://ogp.me/ns#type'] = FACEBOOK_OBJECT_TYPE;
        $tags['http://ogp.me/ns#description'] = __('Create your own eid card', 'kasra-eid-greeting');
    }

    return $tags;
}

/**
*   Uploads the file and save it to uplaod directory
*/
function uploadFile() {
    $allowedExts = array("gif", "jpeg", "jpg", "png");

    $temp = explode(".", $_FILES["photo-file"]["name"]);
    $extension = strtolower(end($temp));

    $result = array();

    if ((($_FILES["photo-file"]["type"] == "image/gif")
        || ($_FILES["photo-file"]["type"] == "image/jpeg")
        || ($_FILES["photo-file"]["type"] == "image/jpg")
        || ($_FILES["photo-file"]["type"] == "image/pjpeg")
        || ($_FILES["photo-file"]["type"] == "image/x-png")
        || ($_FILES["photo-file"]["type"] == "image/png"))
        && ($_FILES["photo-file"]["size"] < 5242880) // 5MB Maximum file size
        && in_array($extension, $allowedExts)) {
        
        if ($_FILES["photo-file"]["error"] > 0) {

            $result['error'] = $_FILES["photo-file"]["error"];

        } else {

            $result['name'] = $_FILES["photo-file"]["name"];
            $result['type'] = $_FILES["photo-file"]["type"];
            $result['size'] = ($_FILES["photo-file"]["size"] / 1024);

            $fileName = time() . $_FILES["photo-file"]["name"];

            $result['dimensions'] = getDimensions();

            if( $result['dimensions']['width'] < 800 || $result['dimensions']['height'] < 600) {
                $result['error'] = "Image must be atleast 800x600";

                wp_send_json_error( $result );
            }
            
            // $result['fileName'] = $fileName;
            // $result['path'] = "upload/$fileName";

            $uploaded_file = wp_upload_bits($fileName, null, file_get_contents($_FILES["photo-file"]["tmp_name"]));

            if($uploaded_file['error']) {
                $result['error'] = $uploaded_file['error'];
                wp_send_json_error( $result );
            } else {
                $result['url'] = $uploaded_file['url'];
                wp_send_json_success($result);
            }

            
            // $result['url'] = $result['path'];
        }

    } else {
        $result['error'] = "Invalid file extension or file is greater than 5MB.";
    }

    // Send the repsonse
    wp_send_json_error( $result );
}


function saveFile() {
    $fileData = $_POST['imageData'];

    $data = str_replace('data:image/png;base64,', '', $fileData);

    $fileName = md5(time() + rand()) . '.png'; // Attempt to make a unique number


    $result = wp_upload_bits($fileName, null, base64_decode($data));

    $image_editor = wp_get_image_editor( $result['file'] );
    $image_editor->resize( 350, 350, true );
    $resized_image = $image_editor->save();
    $matches = array();
    preg_match('/^.*?(wp-content\/.*)$/', $resized_image['path'], $matches);

    $result['resized_image'] = home_url($matches[1]);

    if($result['error']) {
        wp_send_json_error($result);
    }
        
    wp_send_json_success($result);
}

/**
* Returns the dimensions of the image
*/
function getDimensions() {
    $imageSize = getimagesize($_FILES["photo-file"]["tmp_name"]);

    $result = array( 'width' => $imageSize[0], 'height' => $imageSize[1] );

    return $result;
}

/**
*   Loads the plugin language file.
*/
function init() {
    load_plugin_textdomain( 'kasra-eid-greeting', false, dirname( plugin_basename( __FILE__ ) ) );
}

add_shortcode( 'kasra-eid-greetings', 'register_shortcode' );

add_action( 'init', 'init');
add_action( 'wp_enqueue_scripts', 'register_scripts' );

add_action('wp_ajax_upload_file', 'uploadFile');
add_action('wp_ajax_nopriv_upload_file', 'uploadFile');

add_action('wp_ajax_save_file', 'saveFile');
add_action('wp_ajax_nopriv_save_file', 'saveFile');

add_filter( 'fb_meta_tags', 'facebook_meta', 11 );