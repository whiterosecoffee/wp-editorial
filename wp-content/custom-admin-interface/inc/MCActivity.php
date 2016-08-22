<?php

if( class_exists('MCActivity') )
	return;

class MCActivity {
	private $post_id;
	private $comment;
	private $facebook_like;
	private $facebook_share;
	private $linkedin;
	private $pininterest;
	private $googleplus;
	private $email;
	private $twitter;
	private $bookmark;
	private $inlinecomment;
	private $other;

	public function __construct( $post_id = 0 ) {
		$this->post_id = $post_id;

		$this->comment = $this->facebook_like = $this->facebook_share = $this->linkedin = $this->pininterest = $this->googleplus = 
			$this->email = $this->twitter = $this->bookmark = $this->other = $this->inlinecomment = 0;
	}


	public function get_total_count() {
		$total = 0;
		foreach($this as $key => $value) {
			if( $key != "post_id" )
           		$total += intval( $value );
        }
        return $total;
	}

	public function get_total_facebook_count() {
		return $this->facebook_share + $this->facebook_like;
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}

	public function init( $array ) {
		foreach ($this as $key => $value) {
			if( array_key_exists( $key, $array ) )
				$this->{$key} = intval( $array[$key] );
		}
	}

}