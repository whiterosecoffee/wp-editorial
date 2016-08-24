<?php
// Template Name: phpinfo

get_header();


if(current_user_can('manage_options')) {
	phpinfo();
} else {
	wp_redirect('/');
}
