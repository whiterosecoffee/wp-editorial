<?php

/**
 * Plugin Name: Menapost Custom
 * Plugin URI: http://www.menapo.com
 * Description: Menapost Custom Plugin
 * Version: 0.1
 * Author: Ahmed
 * Author URI: http://www.menapo.com
 * License: Private
 */
if( !class_exists('Mobile_Detect') ) {
    require_once( dirname(__FILE__) . '/inc/Mobile_Detect.php' );
}

require_once( dirname(__FILE__) . '/inc/utils.php' );
require_once( dirname(__FILE__) . '/admin.php' );
require_once( dirname(__FILE__) . '/inc/MPCustomEditor.php' );
require_once( dirname(__FILE__) . '/inc/MPExtendedProfile.php' );
require_once( dirname(__FILE__) . '/inc/MPReadingList.php' );
require_once( dirname(__FILE__) . '/inc/MPFilterPlugin.php' );
require_once( dirname(__FILE__) . '/inc/MPActivityValue.php' );
require_once( dirname(__FILE__) . '/inc/MPTrending.php' );
require_once( dirname(__FILE__) . '/inc/MPMood.php' );
require_once( dirname(__FILE__) . '/inc/MPSeasonal.php' );
require_once( dirname(__FILE__) . '/inc/MPTitleRepository.php' );

require_once( dirname(__FILE__) . '/inc/newTaxonomies.php' );

require_once( dirname(__FILE__) . '/inc/MPSuggestCorrection.php' );
require_once( dirname(__FILE__) . '/inc/MPContactForm.php' );

class MenapostCustom {

	private $ajax_actions;

	function __construct() {
		add_action( 'init', array( &$this, 'init' ) );

		add_filter( 'is_mobile_device', array(  &$this, 'is_mobile_device' ) );

		add_action( 'auth_cookie_expiration', array( &$this, 'extend_auth_expiration' ) );
	}

	/**
	*
	*/
	function extend_auth_expiration() {
		return 12*30*24*60*60;
	}

	/**
	* Prepends social icons to the content.
	* @deprecated since Alpha Release
	*/
	function prepend_social_links( $content, $post ) {

		$social_sharing = '<div class="social-container">';
		// Holder Div Start
		$social_sharing .= '<div id="social-sharing" data-perma-link="' . get_permalink( $post->ID ) . '">';
		// Facebook Share
		$social_sharing .= '<a class="social-link" data-activity-name="facebook-share" href="https://www.facebook.com/sharer/sharer.php?app_id=' . get_option( 'facebook_app_id' ) . '&sdk=joey&display=popup&u={URL}" target="_blank"><img src="' . plugins_url( 'images/facebook.png' , __FILE__ ) . '" alt="Facebook" /></a>';
		// Twitter
		$social_sharing .= '<a class="social-link" data-activity-name="twitter" href="https://twitter.com/share?url={URL}&text=' . get_the_title( $post->ID ) . '&via=' . get_option( 'twitter_handle' ) . '" target="_blank"><img src="' . plugins_url( 'images/twitter.png' , __FILE__ ) . '" alt="Tweet" /></a>';
		// Google Plus
		$social_sharing .= '<a class="social-link" data-activity-name="google-plus" href="https://plus.google.com/share?hl=ar&url={URL}"><img src="' . plugins_url( 'images/googleplus.png' , __FILE__ ) . '" alt="Google+" /></a>';
		// Pinterest
		$social_sharing .= '<a class="social-link" data-activity-name="pininterest" href="//www.pinterest.com/pin/create/button/?url={URL}&description=' . get_the_title( $post->ID ) . '&media=' . get_option( 'logo_image' ) . '" data-pin-do="buttonBookmark" ><img src="' . plugins_url( 'images/pinterest.png' , __FILE__ ) . '" alt="Pin It" /></a>';
		// LinkedIn
		$social_sharing .= '<a class="social-link" data-activity-name="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url={URL}&title=' . get_the_title( $post->ID ) . '&summary=' . substr( strip_tags( $content ), 0, 256 ) . '"><img src="' . plugins_url( 'images/linkedin.png' , __FILE__ ) . '" alt="LinkedIn" /></a>';
		// Email
		$social_sharing .= '<a class="social-link nopopup" data-activity-name="email" href="mailto:?subject=' . get_the_title( $post->ID ) . '&body={URL}"><img src="' . plugins_url( 'images/email.png' , __FILE__ ) . '" alt="Email" /></a>';
		// Holder Div End
		$social_sharing .= '</div>';
		$social_sharing .= '<div class="fblike" data-activity-name="facebook-like"><iframe src="//www.facebook.com/plugins/like.php?href='. urlencode( get_permalink( $post->ID ) ) . '&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=65&amp;appId=437766999690927&amp;locale=ar_AR" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:88px; height:21px;" allowTransparency="true"></iframe></div>';  //<div class="fb-like" data-href="' . get_permalink( $post->ID ) . '" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div></div>';

		return $social_sharing . $content;
	}

	function load_front_end_scripts() {
		wp_enqueue_script( 'menapost-script', plugins_url( 'menapost-script.min.js' , __FILE__ ), array( 'jquery' ), NULL, true );
		wp_localize_script( 'menapost-script', 'backend_object',
			array(
				'logged_in_id'                   => get_current_user_id(),
				'ajax_url'                       => admin_url( 'admin-ajax.php' ),
				'home_url'                       => home_url(),
				'facebook_app_id'                => get_option( 'facebook_app_id' ),
				'bitly_active'                   => get_option( 'bitly_active' ),
				'bitly_api_key'                  => get_option( 'bitly_api_key' ),
				'bitly_login'                    => get_option( 'bitly_login' ),
				'mp_nonce_increment_views'       => wp_create_nonce( 'increment_views' ),
				'mp_nonce_get_views'             => wp_create_nonce( 'get_views' ),
				'mp_nonce_add'                   => wp_create_nonce( 'add_to_reading_list' . get_current_user_id() ),
				'mp_nonce_remove'                => wp_create_nonce( 'remove_from_reading_list' . get_current_user_id() ),
				'mp_nonce_get_bookmark_count'    => wp_create_nonce( 'get_bookmark_count' . get_current_user_id() ),
				'mp_nonce_google_plus_count'     => wp_create_nonce( 'google_plus_count' . get_current_user_id() ),
				'mp_nonce_update_activity_value' => wp_create_nonce( 'update_activity_value' . get_current_user_id() ),
				'mp_nonce_load_more'             => wp_create_nonce( 'load_more' ),
				'sharing_enabled'                => apply_filters( 'sharing_enabled', '' ),
				'newsletter_success_message'     => __( 'Thank you for registering your email. We will be in touch.', 'menapost-custom' ),
				'newsletter_error_message'       => __( 'It looks like you\'ve already given us your email address before.', 'menapost-custom' ),
				'email_address_invalid'          => __( 'Please enter a valid email address.', 'menapost-custom' ),
				'email_address_required'          => __( 'The email address is required.', 'menapost-custom' ),
			)
		);
	}

	function load_admin_panel_plugins() {
		if( get_option( 'mp_custom_editor_active' ) ) {
			$mp_custom_editor = new MPCustomEditor();
		}
		$extend_profile = new MPExtendedProfile();
	}

	function load_front_end_plugins() {
		if( get_option( 'mp_reading_list_active' ) ) {
			$mp_reading_list = new MPReadingList();
		}

		$mp_filter_list = new MPFilterPlugin();
		$mp_activity_value = new MPActivityValue();
		$mp_trending = new MPTrending();
	}

	function init() {
		load_plugin_textdomain( 'menapost-custom', false, dirname( plugin_basename( __FILE__ ) ) );

		$mp_mood = new MPMood();
		$mp_seasonal = new MPSeasonal();

		$mp_mediaTypeTaxonomy = new mediaTypeTaxonomy();

		$mp_title_repository = new MPTitleRepository();

		$mp_suggestion_correction = new MPSuggestCorrection();

        if( !is_admin() ) {
			$this->load_front_end_scripts();
			$this->load_front_end_plugins();
		} else {
			$this->load_admin_panel_plugins();
		}
	}

	public static function plugin_disable() {
		wp_clear_scheduled_hook( 'trending_table_cleanup' );
	}

	public function is_mobile_device() {
		$mobile_detect = new Mobile_Detect();
		return $mobile_detect->isMobile();
	}

}

/*
* 19/8/2014 - Based on discussion with Doha Team
* Remove cookies created by AB Title plugin, the AB Title plugin creates
* cookies with numeric keys (the ids of articles) so this deletes for the
* cookies with numeric keys.
*/
// function remove_cookies() {

// 	foreach ($_COOKIE as $key => $value) {
// 		if( is_numeric($key) ) {

// 			setcookie($key, '', time()-3600);

// 		}
// 	}
// }

// remove_cookies();

new MenapostCustom();

register_deactivation_hook( __FILE__, array( 'menapost-custom', 'plugin_disable' ) );
