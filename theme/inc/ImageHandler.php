<?php

class ImageHandler {

	public static function save_image( $url, $type, $post_id, $only_save = false ) {
		
		if( $only_save ) {
			add_post_meta( $post_id, $type, $url, true );
			return $url;
		}	

		$path = self::get_abs_path( $url );
		
		$new_path = self::append_type( $path, $type );
		$new_url = self::append_type( $url, $type );
		$dimens = self::get_size( $type );

		$image_editor = wp_get_image_editor( $path );

		if( ! is_wp_error( $image_editor ) ) {
			$image_editor->resize( $dimens[0], $dimens[1], false );
			$image_editor->save( $new_path );
			update_post_meta( $post_id, $type, $new_url );
			return $new_url;
		}

		return $url;
	}

	public static function check_latest( $slug, $old_url, $new_url ) {
		$old_url = str_replace( '_' . $slug, '', $old_url );
		return $old_url == $new_url;
	}


	private static function get_abs_path( $url ) {
		
		return ABSPATH . substr( $url, strpos( $url, 'wp-content' ) );
	
	}

	private static function append_type( $path, $type ) {
		
		$new_path = substr( $path, 0, strrpos( $path, '.') ) . '_' . $type .  substr( $path, strrpos( $path, '.') );

		return $new_path;
	}

	private static function get_size( $type ) {

		$dimens = array();

		switch( $type ) {
			case 'polaroid-image' : 
				$dimens = array( 480, 320 );
				break;
			case 'header-image-medium':
				$dimens = array( 960, 320 );
				break;
			case 'header-image-small':
				$dimens = array( 480, 160 );
				break;
			case 'featured-image-medium':
				$dimens = array( 960, 640 );
				break;
			case 'featured-image-small':
				$dimens = array( 480, 320 );
				break;
		}

		return $dimens;
	}

	public static function resize_images( $attachment_id, $image ) {
		$meta_data = wp_get_attachment_metadata( $attachment_id );
		
		if( !$meta_data )
			return $image;

		$original_width = $meta_data[ 'width' ];
		$original_height = $meta_data[ 'height' ];

		$path = ABSPATH . 'wp-content/uploads/' . $meta_data[ 'file' ];
		$image_editor = wp_get_image_editor( $path );

		if( is_wp_error( $image_editor ) ) {
			return $image;
		}

		$sizes_array = array(
	        // #1 - Desktop/Retina
	        array ('width' => 1440, 'height' => 720, 'crop' => true),
	        // #2 - Tablets
	        array ('width' => 960, 'height' => 480, 'crop' => true),
	        // #3 - Mobiles
	        array ('width' => 580, 'height' => 290, 'crop' => true),
	        // #4 - Polaroid
	        array ('width' => 480, 'height' => 240, 'crop' => true)
	    );

		$resized_images = $image_editor->multi_resize( $sizes_array );

		$image_data = array();
		foreach ($resized_images as $resized_image) {
			$key = '';
			if( self::equal_tolerate( $resized_image, 1440, 720 ) ) {
				$key = 'desktop';
			} else if( self::equal_tolerate( $resized_image, 960, 480 ) ) {
				$key = 'tablet';
			} else if( self::equal_tolerate( $resized_image, 580, 290 ) ) { 
				$key = 'mobile';
			} else if( self::equal_tolerate( $resized_image, 480, 240 ) ) { 
				$key = 'polaroid';
			}
			$upload_dir = wp_upload_dir();
			$resized_image[ 'url' ] = Utilities::make_relative( $upload_dir['baseurl'] . '/' . substr( $meta_data[ 'file' ], 0, strrpos( $meta_data[ 'file' ], '/' ) + 1 ) . $resized_image[ 'file' ] );
			$image_data[ $key ] = $resized_image;
		}	

		$image_data[ 'attachment_id' ] = $attachment_id;
		$image_data[ 'original_image' ] = $image;
		return $image_data;
	}

	private static function equal_tolerate( $image_meta, $width, $height ) {
		$widthDiff = abs( $image_meta[ 'width' ] - $width );
		$heightDiff = abs( $image_meta[ 'height' ] - $height );

		return $widthDiff >= 0 && $widthDiff <= 6 && $heightDiff >= 0 && $heightDiff <= 6;
	}

}