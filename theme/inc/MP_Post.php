<?php 

add_filter( 'get_post_image', array( 'MP_Post', 'get_post_image' ), 10, 2 );

if( class_exists( 'MP_Post' ) )
	return;

class MP_Post implements IteratorAggregate {
	private $id;
	private $title;
	private $content;
	private $image;
	private $author;
	private $author_id;
	private $mp_author;
	private $date;
	private $read_duration;
	private $trending;
	private $activity_value;
	private $category;
	private $views;
	private $aggregate_count;

	public function __construct() {
		$this->views = -1;
		$this->aggregate_count = -1;
	}

	public function parse( $array ) {

		foreach ($array as $key => $value) {
			if( property_exists( "MP_Post", $key ) )
				$this->{$key} = $value;
		}

		$this->category = $this->get_category();
		$this->activity_value = apply_filters( 'parse_activity_value', $array );
		do_action( 'mp_the_post', $this );

		return $this;	
	}

	public function get_category() {
		$categories = get_the_category( $this->id );
		if(empty($categories)) {
			return "all";
		} else {
			return $categories[0]->slug;
		}

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

	public function get_permalink() {
		$permalink = get_permalink( $this->id );

		return $permalink;
	} 

	public function get_date() {
		$the_date = mysql2date( get_option('date_format'), $this->date, true );
        return $the_date;
	}

	public function get_excerpt( $offset = 150 ) {

		$tagged_content = wpautop( $this->content );
		$first_para = strip_tags( substr( $tagged_content, 0, strpos( $tagged_content, '</p>' ) ) );

		if( strlen( $first_para ) <= $offset )
			return $first_para;
		
		$first_para = substr( $first_para, 0, $offset );
		return substr( $first_para, 0, strrpos( $first_para, ' ' ) ) . ' ...';
	}

	/**
	 * Fallback case - This is here for backward compatibility
	 */
	public function image_fallback() {

		if( $this->image == "" ) {
			$default_article_image = get_option('default_article_image');
			$this->image = ($default_article_image ? $default_article_image : "http://placehold.it/1440x720");
		}

		return $this->image;
	}

	public function get_image( $size = FALSE ) {
		$image_attachment_id = get_post_meta( $this->id, 'image-attachment-id', true );

		$resized_images = get_post_meta( $image_attachment_id, 'resized_images', true );

		if( is_array( $resized_images ) && count( $resized_images ) > 2 ) {
			if( !$size ) {
				return Utilities::make_relative( $resized_images );
			} else {
				return Utilities::make_relative( $resized_images[ $size ]['url'] );
			}
		} else if( $image_attachment_id != "" && ( $resized_images == "" || count( $resized_images ) <= 2 ) ) {
			$resized_images = ImageHandler::resize_images( $image_attachment_id, $this->image );

			if( isset( $resized_images ) && is_array( $resized_images ) ) {
	    		update_post_meta( $image_attachment_id, 'resized_images', $resized_images );
	    	} 

	    	if( !$size ) {
				return Utilities::make_relative( $resized_images );
			} else {
				return Utilities::make_relative( $resized_images[ $size ]['url'] );
			}
		}

		$image_polaroid = get_post_meta( $this->id, 'polaroid-image', true);

		if( $image_polaroid != "" && ImageHandler::check_latest( 'polaroid-image', $image_polaroid, get_post_meta( $this->id, 'image', true ) ) ) {
			return $image_polaroid;
		} 

		if( $image_attachment_id != "" ) {

			$attachment_src = wp_get_attachment_image_src( $image_attachment_id, 'full' );	
			
			$image = ImageHandler::save_image( $attachment_src[0], 'polaroid-image', $this->id );

			return $image;

		} else {
			return $this->image_fallback();
		}

	}

	public function get_thumbnail() {
		$attachmentId = attachment_id_by_url( $this->image );
	    if ($attachmentId && ( $src = wp_get_attachment_image_src( $attachmentId, array( 300, 225 ) ) )) 
	    	echo $src[0]; 
	    else 
	    	echo "http://placehold.it/300X225";
	}

	public function get_read_duration() {
		$duration = Utilities::reading_time_string( $this->read_duration );
		return $duration;

	}

	public function in_reading_list() {

		$result = apply_filters( 'exists_in_reading_list', $this->id ); 
		$result = ($result == 1) ? "true" : "false";
		// mp_log( $result );
		return $result;
	}

	public function get_author_avatar() {
		if( isset( $this->author_id ) ) {
			
			if( !isset( $this->mp_author ) )
				$this->mp_author = new MPUser( $this->author_id );

			return $this->mp_author->get_avatar();
		}

		return False;
	}

	public function is_trending() {
		//return apply_filters( 'is_article_trending', $this->id );
		return false;
	}

	public function get_tags() {
		$terms = wp_get_post_terms( $this->id, 'post_tag', array( "fields" => "all" ) );
		$seasonal_terms = wp_get_post_terms( $this->id, 'seasonal', array( "fields" => "all", "parent" => 0 ) );
		foreach ($seasonal_terms as $seasonal_term) {
			if( $seasonal_term->parent === 0 )
				$terms[] = $seasonal_term;
		}
		return $terms;
	}

	public function get_mood_tags() {
		$terms = wp_get_post_terms( $this->id, 'mood', array( "fields" => "all" ) );
		return $terms;
	}


	public function get_category_tags() {
		$terms = wp_get_post_terms( $this->id, 'category', array( "fields" => "names" ) );
		return $terms;	
	}

	public function get_co_author() {
		$co_author = get_post_meta( $this->id, 'co-author', true );
		return $co_author;
	}

	public function get_author_page_link() {
		return get_author_posts_url( $this->author_id );
	}

	public function get_embedded_content() {
    	$this->content = str_replace( 'img class="', 'img class="img-responsive lazy-load ', $this->content );
    	$this->content = str_replace( '" src="', '" data-original="', $this->content );
    	$this->content = apply_filters( 'the_content', $this->content );
    	return $this->content;
	}

	public function get_short_link() {
		return wp_get_shortlink( $this->id ); 
	}

	public function has_multiple_authors() {
		$co_author = $this->get_co_author();
		return $co_author != '';
	}

	public function get_merged_authors() {
		$author = $this->author . ', ' . $this->get_co_author();
		return $author;
	}

	public function getIterator() {
		// mp_log( $this );
		$array = array(
			"id"                   => $this->id,
			"title"                => $this->title,
			"content"              => $this->content,
			"image"                => $this->get_image( 'polaroid' ),
			"author"               => $this->author,
			"author_page_link"     => $this->get_author_page_link(),
			"date"                 => $this->get_date(),
			"short_link"           => $this->get_short_link(),
			"permalink"            => $this->get_permalink(),
			"excerpt"              => $this->get_excerpt(),
			"in_reading_list"      => $this->in_reading_list(),
			"read_duration"        => $this->get_read_duration(),
			"author_avatar"        => $this->get_author_avatar(),
			"has_multiple_authors" => $this->has_multiple_authors(),
			"merged_authors"       => $this->get_merged_authors(),
			"trending"             => $this->is_trending(),
			"total_views"          => Utilities::make_k_count( $this->get_views() ),
			"total_activity_value" => Utilities::make_k_count( $this->activity_value->get_total_count() ),
			"facebook_total"       => Utilities::make_k_count( $this->activity_value->get_total_facebook_count() ),
			"twitter"              => Utilities::make_k_count( $this->activity_value->twitter ),
			"category"	           => $this->category,
			"is_video_article"     => $this->if_videos_tag_in_article(),
			"tag_string"           => $this->article_tags_string(),
			);

		return new ArrayIterator( $array );
	}

    public function if_videos_tag_in_article() {
        $tags = wp_get_post_tags( $this->id );
        foreach ($tags as $tag) {
            if($tag->slug == "videos"){
                return 1;
            }
        }
        return 0;
    }

    public function article_tags_string() {
        $attached_tags = array();
        $fetched_tags = $this->get_tags();
        $attached_tag = "";
        foreach ($fetched_tags as $fetched_tag) {
            $attached_tags[] = $fetched_tag->slug;
            $attached_tag = implode(" ", $attached_tags);
        }
        return $attached_tag;
    }
    
    //Suggesed Posts - on the basis of TAGS, MOOD and CATEGORY
    public function get_suggested_articles($id, $limit = 3){

        $post_tags = wp_get_post_tags($id);
        $post_mood = wp_get_post_terms($id, 'mood');
        $post_category = wp_get_post_terms($id, 'category');

        $arr = array_merge($post_tags, $post_mood, $post_category);

        $term_arrays = array();
        foreach ($arr as $single_arr) {
            $term_arrays[] = $single_arr->term_id;
        }
        $all_terms = implode(",", $term_arrays);

        global $wpdb;
        $query = "SELECT sub_table.ID, sub_table.TITLE, sub_table.SLUG, COUNT(1) AS counter 
                    FROM
                        (SELECT wp_posts.ID as ID, wp_posts.post_title as TITLE, wp_posts.post_name as SLUG, wp_posts.post_date as DATE, wp_terms.term_id
                        FROM wp_posts
                        INNER JOIN wp_term_relationships ON wp_posts.ID = wp_term_relationships.object_id
                        INNER JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
                        WHERE wp_terms.term_id IN ( $all_terms )
                        AND wp_posts.post_status = 'publish'
                        AND wp_posts.ID <> $id
                        ORDER BY wp_posts.post_date DESC)
                    AS sub_table
                    GROUP BY sub_table.ID
                    ORDER BY counter DESC
                    LIMIT $limit";
        $result = $wpdb->get_results($query);

        $new_content = array();
        foreach ($result as $row) {
            $new_content[] = $row->ID;
        }
        
        return $new_content;
    }

    public function get_views() {
    	if( $this->views === -1 )
    		$this->views = get_post_meta( $this->id, 'views', true );
    	return empty( $this->views ) ? 0 : $this->views;
    }

    public function is_series_article() {
    	$series_terms = array_merge( mp_get_sub_tags( 'seasonal', 'ramadan-series' ), mp_get_sub_tags( 'seasonal', 'tv-series' ) );
    	foreach ($series_terms as $term) {
	    	if( has_term( $term->term_id, 'seasonal', $this->id ) ) {
	    		return $term;
	    	}
    	}
    }

    public function get_series_permalink( $series ) {
    	$slugs = array();

    	$parent_series = get_term_by( 'id', $series->parent, 'seasonal' );

    	if( $parent_series->slug === 'ramadan-series' ) {
    		return get_sub_tag_url( RAMADANIYAT_TAG_SLUG, array( $parent_series->slug, $series->slug ) );
    	} else {
    		return get_sub_tag_url( $parent_series->slug, array( $series->slug ) );
    	}
    	
    }

    public function get_aggregate_count() {
    	if( $this->aggregate_count === -1 )
    		$this->aggregate_count = $this->get_views() + $this->activity_value->get_total_count();
    	
    	return $this->aggregate_count;
    }

    /*
    * Exposes the filter to retrieve post image. 
    */
    public static function get_post_image( $post_id, $size ) {
    	$mp_post = new MP_Post();
    	$mp_post->id = $post_id;

    	return $mp_post->get_image( $size );
    }

}