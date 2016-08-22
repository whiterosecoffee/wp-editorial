<?php

function user_is( $roles ) {	
	if( is_string( $roles ) ) {
		$roles = array( $roles );
	}

	$user = wp_get_current_user();

	return array_intersect($roles, $user->roles );
}