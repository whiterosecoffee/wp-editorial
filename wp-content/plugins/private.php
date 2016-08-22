<?php

/**
 * Plugin Name: Private
 * Plugin URI: http://www.menapo.com
 * Description: To make the blog private.
 * Version: 0.1
 * Author: Ahmed
 * Author URI: http://www.kasra.co
 * License: Private
 */


function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

function private_only () {
	$location = get_site_url() . "/";
	$status = 404;
	if (!is_user_logged_in() && !is_login_page()) {
			wp_redirect( $location, $status );
		exit();
	} 
}


add_action('init','private_only');
