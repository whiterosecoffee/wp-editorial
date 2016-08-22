<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

$uploadedfile = $_FILES['image'];
$allowed_file_types = array('jpg' =>'image/jpg','jpeg' =>'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png');
$upload_overrides = array( 'test_form' => false, 'mimes' => $allowed_file_types );
$movefile = wp_handle_upload( $uploadedfile, $upload_overrides, current_time( 'Y/m' ) );

if ( $movefile ) {
	if( isset( $movefile[ 'error' ] ) ) {
		wp_send_json_error( 'Either file size is too big or invalid file type.' );
	} else {

		list( $width, $height )  = getimagesize( $movefile['file'] );
		
		$movefile[ 'width' ]  = $width;
		$movefile[ 'height' ] = $height;

		wp_send_json_success( $movefile );
	}
} else {
    wp_send_json_error( 'Failed, seems like a hacking attempt.' );
}
