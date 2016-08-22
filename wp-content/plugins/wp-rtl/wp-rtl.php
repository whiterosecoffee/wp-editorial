<?php
/*
Plugin Name: WP-RTL
Plugin URI: http://www.fadvisor.net/blog/2008/10/wp-rtl/
Description: Adds two buttons to the TinyMCE editor to enable writing text in Left to Right (LTR) and Right to Left (RTL) directions.
Version: 0.3
Author: Fahad Alduraibi
Author URI: http://www.fadvisor.net/blog/
*/
/*
  Developed 2008  Fahad Alduraibi  (email : fduraibi (at) gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( "init", "tinymce_bidi_addbuttons" );

function tinymce_bidi_addbuttons() {
	if( !current_user_can ( 'edit_posts' ) && !current_user_can ( 'edit_pages' ) ) {
		return;
	}
	if( get_user_option ( 'rich_editing' ) == 'true' ) {
		add_filter( "mce_external_plugins", "tinymce_bidi_plugin" );
		add_filter( "mce_buttons", "tinymce_bidi_buttons" );
	}
}
function tinymce_bidi_buttons($buttons) {
	array_push($buttons, "separator", "ltr", "rtl");
	return $buttons;
}

function tinymce_bidi_plugin($plugin_array) {
	if (get_bloginfo('version') < 3.9) {
		$plugin_array['directionality'] = includes_url('js/tinymce/plugins/directionality/editor_plugin.js');
	} else {
		$plugin_array['directionality'] = includes_url('js/tinymce/plugins/directionality/plugin.min.js');
	}
	return $plugin_array;
}

?>
