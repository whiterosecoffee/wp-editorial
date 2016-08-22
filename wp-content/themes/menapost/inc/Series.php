<?php

class Series {

	// TODO - To be changed to fetch data from database
	public static function get_shows_list( $slug ) {
		$shows_list = array();

		$shows_terms = mp_get_sub_tags( 'seasonal', $slug );

		foreach ($shows_terms as $show_term) {
			$show = new Show();

			$show->term_id = $show_term->term_id;
			$show->title = $show_term->name;
			$show->slug = $show_term->slug;

			if( $slug === RAMADAN_SERIES_TAG_SLUG ) {
				$show->category = RAMADANIYAT_TAG_SLUG;
				$show->sub_category = RAMADAN_SERIES_TAG_SLUG; 
			} else {
				$show->category = $slug;	
			}

			$shows_list[$show->slug] = $show;			
		}

		return $shows_list;
	}

	public static function get_show( $series_slug, $show_slug ) {
		$shows_list = self::get_shows_list( $series_slug );

		return $shows_list[$show_slug];
	}


}

class Show {
	private $term_id;
	private $title;
	private $slug;
	private $image;

	private $category;
	private $sub_category;


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

	public function get_image( $size = "" ) {

		$filenames = array(
			"سحر-الأسمر" => "sahar-al-asmar",
			"الأخوة" => "al-akhuwa",
			"لارا" => "lara",
			"هبة-رجل-الغراب" => "hibat-rajol-elghurab",
			"مرآة-ذات-وجهين" => "mara-za-wajhein",
			"حريم-السلطان-4ج" => "muhisem",
			"الخطيئة" => "al_khate2a",
			"محمود-ومريم" => "mahmoud_wa_mariam",
			"نهاية-العالم-ليست-غدا" => "nehayet_alalam_leyst_ghadan",
			"كيف-تخسر-مليون-جنيه؟" => "keif_takhsar_million_genay"
			);

		$file_name = $this->slug;

		if( array_key_exists(urldecode($this->slug), $filenames) ) {
			$file_name = $filenames[urldecode($this->slug)];
		}

		if( empty( $size ) )
			return CHILD_URL . '/images/' . $file_name . '.jpg';
		else
			return CHILD_URL . '/images/' . $size . "-" . $file_name . '.jpg';
	}

	public function get_permalink() {
		$slugs = array();

		if( isset( $this->sub_category ) ) {
		
			$slugs[] = $this->sub_category;

		}

		$slugs[] = $this->slug;
		
		return get_sub_tag_url( $this->category, $slugs );
	}

}