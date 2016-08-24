<?php

class ProfileImageHandler {

	/**
	 * The default avatar to use in case of absence of user avatar.
	 */
	private static function get_default_avatar() {
		return CHILD_URL . '/img/default_avatar.jpg';
	}

	/**
	 * Gets the image url, stores it on the server and creates its 3 sizes.
	 */
	public static function get_image( $user_id, $size ) {
		$image_url = False;
		
		try {
			$image_url = self::get_image_url( $user_id );
		} catch(Exception $e) {
			mp_log( $e ); 
		}

		if( !$image_url )
			return self::get_default_avatar();

		$uploaded_file = self::fetch_image( $image_url );

		if( !$uploaded_file )
			return $image_url;

		return self::save_image_sizes( $user_id, $uploaded_file, $size );
	}

	private static function save_image_sizes( $user_id, $file, $size ) {
		$result = array();

		$path = $file[ 'file' ];

		try {
			
			$result['thumbnail']   = self::save_image( $path, 40, 40 );
			$result['author-page'] = self::save_image( $path, 200, 200 );
			$result['team-page']   = self::save_image( $path, 630, 630 );

		} catch (Exception $e) {	
			return $file[ 'url' ];
		}

		update_user_meta( $user_id, 'profile_picture', 'custom' );
    	update_user_meta( $user_id, 'profile_picture_thumbnail', $result[ 'thumbnail' ] );
    	update_user_meta( $user_id, 'profile_picture_author_page', $result[ 'author-page' ] );
    	update_user_meta( $user_id, 'profile_picture_team_page', $result[ 'team-page' ] );

		return $result[ $size ];
	}

	private static function save_image( $path, $new_w, $new_h ) {
    	$image_editor = wp_get_image_editor( $path );

		if( is_wp_error( $image_editor ) ) {
			throw new Exception( 'Unable to save resized image.' );
		}	    	
		$size = $image_editor->get_size();
		
		$image_editor->resize( $new_w, $new_h, false );

		$saved = $image_editor->save();
		
		$matches = array();
    	preg_match('/^.*?(wp-content\/.*)$/', $saved['path'], $matches);
		$saved[ 'url' ] = home_url( $matches[1] );

		return $saved[ 'url' ];
    }

	private static function fetch_image( $url ) {
		$get = wp_remote_get( $url );

		if( is_wp_error( $get ) )
			return False;
		$response_code = wp_remote_retrieve_response_code( $get );

		if( $response_code == '404' || $response_code == '403' )
			return False;


		$filename = rawurldecode( basename( $url ) );
		if( strpos( $filename, '?' ) !== FALSE ) {
			$filename = substr( $filename, 0, strpos( $filename, '?' ) );
		}

		$type = wp_remote_retrieve_header( $get, 'content-type' );
		$uploaded_file = wp_upload_bits($filename, '', wp_remote_retrieve_body( $get ) );

		if( is_wp_error( $uploaded_file ) )
			return False;

		return $uploaded_file;
	}

	private static function get_image_url( $user_id ) {
		$image_url = False;
		/**
		 * Get the image tag
		 */
		$image_tag = get_avatar( $user_id );

		/**
		 * Extract the SRC element value from the image tag.
		 */
		$matches = array();
		preg_match('/src="(.+?)"/', $image_tag, $matches);
		if( count($matches) > 1 ) {
			$image_url = $matches[1];
		}

		/**
		 * Remove size 
		 */
		if( strpos($image_url, 'facebook') ) {
			// Add height and width to facebook images
			$image_url .= '&height=630&width=630&redirect=0';
			$image_url = str_replace( 'normal', 'large', $image_url );
			$image_url = self::get_facebook_url( $image_url ); 
		} else if( strpos( $image_url, 'twimg' ) ) {
			// Remove the size to get the original image
			$image_url = str_replace( array( '_normal', '_bigger', '_mini' ), array( '', '', '' ), $image_url ); 
		}

		return $image_url;
	}

	private static function get_facebook_url( $url ) {
		$get = wp_remote_get( $url );

		if( is_wp_error( $get ) )
			throw new Exception( "Error fetching url from facebook" );
		
		$json = json_decode( wp_remote_retrieve_body( $get ) );

		return $json->data->url;	
	}

}