<?php

class Utilities {

	const DOMAIN_MATCH_PATTERN = '/^(.*\/wp-content\/)/';

	public static function replace_parameter( $key, $value, $string ) {

		if( $key[0] !== '{' && substr( $key, -1 ) !== '}' )
			$key = '{' . $key . '}';
		return str_replace( $key, $value, $string);
	}

	public static function apply_pagination( $array, $page, $limit ) {

		$paginated = array();

		$paginated[ 'page' ] = $page;
		$paginated[ 'limit' ]  = $limit;
		$paginated[ 'total_pages' ]  = ceil( count( $array ) / $limit );
		$paginated[ 'count' ] = count( $array );
		$paginated[ 'offset' ] = ( $page - 1) * $limit;
		
		$paginated[ 'ids' ] = array_slice( $array, $paginated[ 'offset' ], $limit );

		return $paginated;

	}

	public static function flat_array( $results ) {
		$flat_array = array();

		if( !is_array( $results ) )
			return $results;
		
		array_walk_recursive($results, function($a) use (&$flat_array) { $flat_array[] = $a; });

		return $flat_array;
	}

	public static function circular_array_slice( $array, $start, $length, &$next_start = 0 ) {

		$sliced_array = array_slice( $array, $start, $length );
		$sliced_length = count( $sliced_array );

		$next_start = $start + $sliced_length;

		while( $sliced_length < $length ) {
			$start = 0;
			
			$remaining_length = $length - $sliced_length;
			$remaining_sliced_array = array_slice( $array, $start, $remaining_length );
			
			$sliced_array = array_merge( $sliced_array, $remaining_sliced_array );
			
			$next_start = $remaining_length;
			$sliced_length = count( $sliced_array );
		}

		return $sliced_array;
	}


	public static function sort_array( $unordered, $order ) {
		
		foreach ($order as $key => $value) {
			$result[] = self::get_element_by_id( $unordered, $value );
		}
		
		return $result;
	}

	private static function get_element_by_id( $posts, $value ) {
		for ($i=0; $i < count($posts); $i++) { 
			if( $posts[$i]->id == $value)
				return $posts[$i];
		}
	}

	public static function make_k_count( $count ) {
		if( $count < 1000 ) 
			return $count;
		else {
			$short = round( $count / 1000, 1 );
			$str_short = strval( $short );
			if( $str_short[ strlen( $str_short ) - 1] == '0' ) {
				return substr( $str_short, 0, strlen( $str_short ) - 2) . 'K';
			}
			
			return $short . 'K';
		}
	}

	public static function reading_time_string( $duration ) {
		$duration = intval( $duration );

		switch ( $duration ) {
			case 0:
				$duration = "دقيقة";
				break;
			case 1:
				$duration = "دقيقة";
				break;
			case 2:
				$duration = "دقيقتان";
				break;
			case 3: case 4: case 5: case 6: 
			case 7: case 8: case 9: case 10:
				$duration .= " دقائق";
				break;
			default:
				$duration .= " دقيقة";
				break;
		}

		return $duration;
	}

	public static function make_relative( $urls ) {
		if( is_array( $urls ) ) {
			foreach ($urls as $key => $value) {
				if( isset( $value[ 'url' ] ) ) {
					$value[ 'url' ] = home_url() . '/wp-content/' . preg_replace( self::DOMAIN_MATCH_PATTERN, '', $value[ 'url' ] );
					$urls[ $key ] = $value;
				}
			}
		} else {
			$urls = home_url() . '/wp-content/' . preg_replace( self::DOMAIN_MATCH_PATTERN, '', $urls ); 	
		}
		
		return $urls;
	}

	public static function remove_duplicate_tags( $tag_array1, $tag_array2 ) {
		$result = array();
		
		foreach ( array_merge( $tag_array1, $tag_array2 ) as $value ) {
			$result[ $value->slug ] = $value;
		}

		return $result;
	}

	public static function add_quotes_and_escape( $str ) {
	    return sprintf("'%s'", esc_sql( $str ) );
	}

}

add_filter( 'make_k_count', array( 'Utilities', 'make_k_count' ) );