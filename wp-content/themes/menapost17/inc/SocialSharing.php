<?php

class SocialSharing {

	const FACEBOOK_SHARE   = 'https://www.facebook.com/sharer/sharer.php?app_id={FACEBOOK_APP_ID}&sdk=joey&display=popup&u={URL}';
	// const FACEBOOK_LIKE    = '<iframe src="//www.facebook.com/plugins/like.php?href={URL}&amp;width=55&amp;layout=button&amp;action=like&amp;show_faces=false&amp;locale=ar_AR&amp;share=false&amp;height=20&amp;appId={FACEBOOK_APP_ID}" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:55px; height:20px;" allowTransparency="true"></iframe>';
	const FACEBOOK_LIKE = '<div class="fb-like" data-width="450" data-href="{URL}" data-layout="{LAYOUT}" data-action="like" data-show-faces="false" data-share="false"></div>';
	const TWITTER          = 'https://twitter.com/share?url={URL}&text={TITLE}&via={TWITTER_HANDLE}';
	const GOOGLE_PLUS      = 'https://plus.google.com/share?url={URL}&hl=ar';
	const WHATSAPP         = 'whatsapp://send?text={TITLE}';

	const FACEBOOK_PAGE_LIKE_BUTTON = '<iframe src="//www.facebook.com/plugins/like.php?href={FACEBOOK_PAGE}&amp;width=55&amp;layout={LAYOUT}&amp;action=like&amp;show_faces={SHOW_FACES}&amp;locale=ar_AR&amp;share=false&amp;height=20&amp;appId={FACEBOOK_APP_ID}" scrolling="no" frameborder="0" style="border:none; overflow:hidden; {WIDTH} {HEIGHT}" allowTransparency="true"></iframe>';
	const FACEBOOK_PAGE_LIKE_BOX = '<iframe src="//www.facebook.com/plugins/likebox.php?href={FACEBOOK_PAGE}&amp;width&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId={FACEBOOK_APP_ID}&amp;locale=ar_AR" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:258px;" allowTransparency="true"></iframe>';
	const TWITTER_FOLLOW_BUTTON = '<iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/follow_button.html?screen_name={TWITTER_HANDLE}&show_count=true&lang=ar&show_screen_name=false" style="width:135px;"></iframe>';
	const GOOGLE_PLUS_ONE_BUTTON = '<div class="g-follow" data-annotation="bubble" data-height="20" data-href="{GOOGLE_PLUS_PAGE}" data-rel="author"></div>';

	public static function get_facebook_share_link( $article = False ) {
		$facebook_share = self::FACEBOOK_SHARE;

		$facebook_share = Utilities::replace_parameter( 'FACEBOOK_APP_ID', get_option( 'facebook_app_id' ), $facebook_share );

		if( $article )
			$facebook_share = Utilities::replace_parameter( 'URL', $article->get_short_link(), $facebook_share );

		$facebook_share = self::check_enabled( $facebook_share );

		return $facebook_share;
	}

	public static function get_facebook_share_link_for_page( $title, $url) {
		$facebook_share = self::FACEBOOK_SHARE;

		$facebook_share = Utilities::replace_parameter( 'FACEBOOK_APP_ID', get_option( 'facebook_app_id' ), $facebook_share );
		$facebook_share = Utilities::replace_parameter( 'URL', urlencode( $url ), $facebook_share );

		$facebook_share = self::check_enabled( $facebook_share );

		return $facebook_share;
	}

	public static function get_facebook_like_code( $article, $layout = "button" ) {
		$facebook_like = self::FACEBOOK_LIKE;

		$facebook_like = Utilities::replace_parameter( 'FACEBOOK_APP_ID', get_option( 'facebook_app_id' ), $facebook_like );
		$facebook_like = Utilities::replace_parameter( 'URL', $article->get_short_link(), $facebook_like );
		$facebook_like = Utilities::replace_parameter( 'LAYOUT', $layout, $facebook_like );

		$facebook_like = self::check_enabled( $facebook_like );

		return $facebook_like;
	}

	public static function get_twitter_link( $article, $add_url = FALSE ) {
		$twitter_link = self::TWITTER;

		$twitter_link = Utilities::replace_parameter( 'TWITTER_HANDLE', get_option( 'twitter_handle' ), $twitter_link );
		if( $article != '') {
			$twitter_link = Utilities::replace_parameter( 'TITLE', urlencode( $article->title ), $twitter_link );
		}

		if( $add_url ) {
			$twitter_link = Utilities::replace_parameter( 'URL', urlencode( $article->get_short_link() ), $twitter_link );
		}

		$twitter_link = self::check_enabled( $twitter_link );

		return $twitter_link;
	}

	public static function get_twitter_link_for_page( $title, $url ) {
		$twitter_link = self::TWITTER;

		$twitter_link = Utilities::replace_parameter( 'TWITTER_HANDLE', get_option( 'twitter_handle' ), $twitter_link );
		$twitter_link = Utilities::replace_parameter( 'URL', urlencode( $url ), $twitter_link );
		$twitter_link = Utilities::replace_parameter( 'TITLE', urlencode( $title ), $twitter_link );

		$twitter_link = self::check_enabled( $twitter_link );

		return $twitter_link;
	}

	public static function get_google_plus_link( $article = False ) {
		$google_plus = self::GOOGLE_PLUS;

		$google_plus = Utilities::replace_parameter( 'URL', urlencode( $article->get_short_link() ), $google_plus );

		return $google_plus;
	}

	public static function get_whatsapp_link( $article ) {
		$whatsapp_link = self::WHATSAPP;

		$whatsapp_link = Utilities::replace_parameter( 'TITLE', self::make_share_text_link( $article ), $whatsapp_link );

		$whatsapp_link = self::check_enabled( $whatsapp_link );

		return $whatsapp_link;
	}

	public static function get_facebook_like_button( $layout = "button_count", $show_faces = False, $width = "width: 100px;", $height = "") {
		$facebook_like_button = Utilities::replace_parameter( 'FACEBOOK_APP_ID', get_option( 'facebook_app_id' ), self::FACEBOOK_PAGE_LIKE_BUTTON );
		$facebook_like_button = Utilities::replace_parameter( 'FACEBOOK_PAGE', self::get_facebook_page(), $facebook_like_button );
		$facebook_like_button = Utilities::replace_parameter( 'SHOW_FACES', $show_faces, $facebook_like_button );
		$facebook_like_button = Utilities::replace_parameter( 'LAYOUT', $layout, $facebook_like_button );
		$facebook_like_button = Utilities::replace_parameter( 'WIDTH', $width, $facebook_like_button );
		$facebook_like_button = Utilities::replace_parameter( 'HEIGHT', $height, $facebook_like_button );
		return $facebook_like_button;
	}

	public static function get_facebook_like_box() {
		$facebook_like_box = Utilities::replace_parameter( 'FACEBOOK_APP_ID', get_option( 'facebook_app_id' ), self::FACEBOOK_PAGE_LIKE_BOX );
		$facebook_like_box = Utilities::replace_parameter( 'FACEBOOK_PAGE', self::get_facebook_page(), $facebook_like_box );
		return $facebook_like_box;
	}

	public static function get_twitter_follow_button() {
		return Utilities::replace_parameter( 'TWITTER_HANDLE', self::get_twitter_handle(), self::TWITTER_FOLLOW_BUTTON );
	}

	public static function get_google_plus_follow_button() {
		return Utilities::replace_parameter( 'GOOGLE_PLUS_PAGE', self::get_google_plus_page(), self::GOOGLE_PLUS_ONE_BUTTON );
	}

	public static function get_facebook_page() {
		return get_option( 'facebook_page', 'https://www.facebook.com/KasraOnline' );
	}

	public static function get_twitter_handle() {
		return get_option( 'twitter_handle', 'kasra' );
	}

	public static function get_google_plus_page() {
		return get_option( 'google_plus_page', 'https://plus.google.com/+KasraCoOnline' );
	}

	public static function get_twitter_page() {
		return 'http://twitter.com/' . self::get_twitter_handle();
	}

	private static function make_share_text_link( $article ) {
		$text = $article->title . urlencode( "\n\n" ) . $article->get_short_link();

		return $text;
	}

	private static function check_enabled( $link ) {
		if( !Constants::SHARING_ENABLED ) {
			$link = "";
		}
		return $link;
	}

}
