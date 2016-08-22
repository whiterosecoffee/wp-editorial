<?php

if( class_exists( 'FacebookMetaTags' ) )
	return;

class FacebookMetaTags {

	const DESCRIPTION_TAG = 'http://ogp.me/ns#description';
	const LOCALE_TAG      = 'http://ogp.me/ns#locale';
	const URL_TAG         = 'http://ogp.me/ns#url';
	const IMAGE_TAG       = 'http://ogp.me/ns#image';

	public static function update_tags( $tags ) {

		$tags[ self::DESCRIPTION_TAG ] = self::update_description( isset( $tags[ self::DESCRIPTION_TAG ] ) ? $tags[ self::DESCRIPTION_TAG ] : "" );
		$tags[ self::LOCALE_TAG ]      = self::update_locale( $tags[ self::LOCALE_TAG ] );

		if( is_single() )
			$tags[ self::IMAGE_TAG ]       = self::update_image( isset( $tags[ self::IMAGE_TAG ] ) ? $tags[ self::IMAGE_TAG ] : array() );
		else {
			$tags[ self::IMAGE_TAG ]       = apply_filters( 'kasra_logo_header', '' );
		}
		return $tags;
	}

	private static function update_description( $description ) {
		$tagline = __( 'Tagline', 'menapost-theme' );

		if( $tagline == "" )
			$tagline = $description;

		return $tagline;
	}

	private static function update_locale( $locale ) {
		return "ar_AR";
	}

	private static function update_image( $images ) {
		global $post;

		$image = array();
		$image[ 'url' ] = apply_filters( 'get_article_header_image', $post->ID, 'desktop' );

		return $image;

	}

}
