<?php

class MPUrl {
	public static function get_page_link( $page_name ) {
		$slug = '';

		switch( $page_name ) {
			case 'our-team':
				$slug = 'فريق-كسرة';
				break;
			case 'our-story':
				$slug = 'قصة-كسرة';
				break;
			case 'our-rules':
				$slug = 'شروط-كسرة';
				break;
			case 'profile':
				$slug = 'profile';
				break;
		}

		return home_url( $slug );
	}
}