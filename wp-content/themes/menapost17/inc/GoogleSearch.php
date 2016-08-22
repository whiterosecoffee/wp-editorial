<?php

class GoogleSearch {
	
	const API_KEY = 'AIzaSyAiKOhk-CESmbb3G3RQSh_7Ve8qam5ozsU';
	const SEARCH_ENGINE_ID = '016156008496609840568:tgxbqs07qho';
	//const API_URL = 'https://www.googleapis.com/customsearch/v1?key={API_KEY}&cx={SEARCH_ENGINE_ID}&q={QUERY}&start={START}&filter=0';
	const API_URL = 'http://www.google.com/cse?start={START}&num={LIMIT}&q={QUERY}&client=google-csbe&output=xml_no_dtd&cx={SEARCH_ENGINE_ID}&filter=0';
	const LIMIT = 10;

	private static function get_api_url() {
		return str_replace( array( '{API_KEY}', '{SEARCH_ENGINE_ID}', '{LIMIT}' ), array( self::API_KEY, self::get_search_engine_id(), self::LIMIT ), self::API_URL ); 
	}

	private static function get_search_engine_id() {
		$search_engine_id = get_option( 'google_search_engine_id' );
		if( !$search_engine_id ) 
			$search_engine_id = self::SEARCH_ENGINE_ID;
		return $search_engine_id;
	}

	private static function make_request_url( $query, $start ) {
		$url =  str_replace( array( '{QUERY}', '{START}' ), array( urlencode( $query ), $start ), self::get_api_url() );
		return $url;
	}

	public static function get_results( $query, $page ) {
		$start = ( ( $page - 1 ) * self::LIMIT );
		$parsed_query = self::parse_query( trim( $query ) );
		
		$parsed_query = str_replace( ' ', '+', $parsed_query );

		$response = self::make_request( self::make_request_url( $parsed_query, $start ) );

		$results = self::parse_response( $response );		

		$total_results = self::get_total_count( self::make_request( self::make_request_url( $parsed_query, 990 ) ) );

		return array( 'search_results' => $results, 'query' => $query, 'total_results' => $total_results);
	}

	public static function get_total_count( $response ) {
		$response = new SimpleXMLElement( $response );

		return (int) $response->RES->M;
	}

	public static function ar2en( $str ) {
	    $ends = array('0','1','2','3','4','5','6','7','8','9');
	    $ards = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');

	    $str = str_replace( $ards, $ends, $str );
	    return $str;
	}

	private static function parse_response( $response ) {
		$response = new SimpleXMLElement( $response );
		$result = array();

		$result[ 'count' ] = (int) $response->RES->M;
		if( $result[ 'count' ] == 0 ) 
			throw new Exception( 'Zero results returned.' );

		$items = $response->RES->R;

		$result[ 'offset' ] = (int) $response->RES[ 'SN' ];

		$page = ceil( $result[ 'offset' ] / self::LIMIT );

		$result[ 'page' ]          = $page;
		$result[ 'limit' ]         = self::LIMIT;
		$result[ 'total_pages' ]   = ceil( $result[ 'count' ] / self::LIMIT );

		if( isset( $response->RES->NB->NU ) )		
			$result[ 'next_page' ]     = (string) $response->RES->NB->NU;
		if( isset( $response->RES->NB->PU ) )		
			$result[ 'previous_page' ] = (string) $response->RES->NB->PU;

		$result_items = array();
		foreach ( $items as $item ) {
			
			$search_result = new SearchResult();
			$xpath = $item->xpath( 'PageMap/DataObject[@type="metatags"]/Attribute[@name="og:title"]/@value' );
			if( isset( $xpath[0]['value'] ) )
				$search_result->title         = (string) $xpath[0]['value'];
			else 
				$search_result->title         = (string) $item->T;

			$xpath = $item->xpath( 'PageMap/DataObject[@type="cse_image"]/Attribute[@name="src"]/@value' );
			if( isset( $xpath[0]['value'] ) )
				$search_result->thumbnail     = (string) $xpath[0]['value'];
			
			$search_result->url           = (string) $item->U;
			$search_result->description   = strip_tags( (string) $item->S, '<b>' );

			$xpath = $item->xpath( 'PageMap/DataObject[@type="metatags"]/Attribute[@name="og:type"]/@value' );
			$search_result->type          = (string) $xpath[0]['value'];
			
			if( $search_result->type == 'article' ) {
				$xpath = $item->xpath( 'PageMap/DataObject[@type="metatags"]/Attribute[@name="article:published_time"]/@value' );
				$search_result->modified_time = (string) $xpath[0]['value'];
				$xpath = $item->xpath( 'PageMap/DataObject[@type="metatags"]/Attribute[@name="article:author"]/@value' );
				$search_result->set_author_slug( (string) $xpath[0]['value'] );
			}

			$result_items[] = $search_result;
		}

		
		$result[ 'items' ] = $result_items;
		
		return $result;

	}

	private static function parse_query( $search_query ) {

		$search_query = preg_replace_callback( '/([^\s]+)/', function( $matches ) {
			$match = $matches[0];
			
			$result = preg_match( '/^([١٢٣٤٥٦٧٨٩٠]+)$/u', $match);
			
			if( $result == 1 ) {
				return $match . ' OR ' . GoogleSearch::ar2en( $match );
			}

			$result = preg_match( '/[١٢٣٤٥٦٧٨٩٠]+/u', $match);

			if( $result == 1 ) {
				return $match . ' OR ' . GoogleSearch::ar2en( $match );
			}

			return $match;
		}, $search_query );

		return $search_query;
	} 

	private static function make_request( $url ) {

		$response = wp_remote_get( $url, array('timeout' => 100) );
		
		if( is_wp_error( $response ) ) {
			throw new Exception( 'Unable to fetch the results.' );
		}
		
		$response_code = wp_remote_retrieve_response_code( $response );

		if( $response_code != 200 ) 
			throw new Exception( 'Invalid http response code.' );

		return wp_remote_retrieve_body( $response );
	}

}

class SearchResult {

	private $title;
	private $description;
	private $url;
	private $thumbnail;
	private $modified_time;
	private $type;
	private $author_slug;
	private $author;

	public function __construct() {
		$this->author = FALSE;
	}

	public function __get( $property ) {
		if ( property_exists( $this, $property ) ) {
			return $this->$property;
		}
	}

	public function __set( $property, $value ) {
		if ( property_exists( $this, $property ) ) {
			$this->$property = $value;
		}

		return $this;
	}

	public function set_author_slug( $url ) {
		$matches = array();
		preg_match( '/editor\/(.*)\//', $url, $matches );
		if( isset( $matches[1] ) )
			$this->author_slug = $matches[1];
	}

	public function get_author() {
		if( $this->type == 'article' ) {
			if( $this->author ) {
				return $this->author;
			} else {
				$user = get_user_by( 'slug', $this->author_slug );
				if( $user )
					$this->author = $user->display_name;
				return $this->author;
			}
		}

		return FALSE;
	}

	public function get_date() {
		if( $this->type == 'article' ) {
			return date( get_option( 'date_format' ), strtotime( $this->modified_time ) );
		}
		return FALSE;
	}

}