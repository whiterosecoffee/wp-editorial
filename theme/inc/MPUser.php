<?php

class MPUser {

	private $id;
	private $nickname;
	private $email;

	private $articles_count;

	private $profile_picture_type;
	private $profile_picture_thumbnail;
	private $profile_picture_author_page;
	private $profile_picture_team_page;

	public function __construct( $id ) {
		$this->id = $id;
	}

	public static function get_current_user() {
		if( is_user_logged_in() )
			return $user = new MPUser( get_current_user_id() );
		else
			return False;
	}
	
	public function get_nickname() {
		if( !isset( $this->nickname ) )
			$this->nickname = get_user_meta( $this->id, 'nickname', true );
		return $this->nickname;
	}

	public function get_avatar( $size = 'thumbnail' ) {
		if( !isset( $this->profile_picture_type ) )
			$this->profile_picture_type = get_user_meta( $this->id, 'profile_picture', true );

		if( $this->profile_picture_type == 'custom' ) {
			switch ( $size ) {
				case 'author-page':
					if( !isset( $this->profile_picture_author_page ) )
						$this->profile_picture_author_page = get_user_meta( $this->id, 'profile_picture_author_page', true );
					$result = $this->profile_picture_author_page;
					break;
				case 'team-page':
					if( !isset( $this->profile_picture_team_page ) )
						$this->profile_picture_team_page = get_user_meta( $this->id, 'profile_picture_team_page', true );
					$result = $this->profile_picture_team_page;
					break;
				default:
					if( !isset( $this->profile_picture_thumbnail ) )
						$this->profile_picture_thumbnail = get_user_meta( $this->id, 'profile_picture_thumbnail', true );						
					$result = $this->profile_picture_thumbnail;
					break;
			}

			return $result;
		} 

		// Remove the cache, because they will have to updated for the next subsequent call
		unset( $this->profile_picture_type );
		unset( $this->profile_picture_author_page );
		unset( $this->profile_picture_team_page );
		unset( $this->profile_picture_thumbnail );

		return ProfileImageHandler::get_image( $this->id, $size );
	}

	public function get_articles_count() {

		if( !isset( $this->articles_count ) ) {
			$this->articles_count = count_user_posts( $this->id );
		}

		return $this->articles_count;
	}

	public function get_articles_count_str() {
		$result = $this->get_articles_count() ;
		return $result;
	}

	public function get_email() {
		if( !isset( $this->email ) ) {
			$email = get_user_meta( $this->id, 'pref_email', true );
			
			if( empty( $email ) ) {
				$email = get_userdata( $this->id )->user_email;
			}

			$this->email = $email;
		}
		return $this->email;
	}

	public static function get_user_avatar( $user_id, $size = 'thumbnail' ) {
		$user = new MPUser( $user_id );
		return $user->get_avatar( $size );
	}
	
	public static function get_current_user_nick_name() {
		$user = self::get_current_user();
		return $user->get_nick_name();
	}
}
