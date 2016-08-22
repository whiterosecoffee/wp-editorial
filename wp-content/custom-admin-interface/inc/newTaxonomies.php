<?php

if( !class_exists( 'mediaTypeTaxonomy' ) ) {
	require_once( dirname(__FILE__) . '/MPPlugin.php' );

	class mediaTypeTaxonomy extends MPPlugin {
		const MP_MEDIA_TYPE_TAXONOMY_VERSION = 0.1;

		public function __construct() {
			parent::__construct( 'mediaTypeTaxonomy', self::MP_MEDIA_TYPE_TAXONOMY_VERSION );
			$this->register_taxonomy();
		}

		public function register_taxonomy() {

			register_taxonomy(
				'media-type-taxonomy',
				'post',
				array(
					'labels'        => array(
						'name'              => __( 'Media Type', 'menapost-custom' ),
						'singular_name'     => __( 'Media Type', 'menapost-custom' ),
						'search_items'      => __( 'Search Media Type', 'menapost-custom' ),
						'all_items'         => __( 'All Media Type', 'menapost-custom' ),
						'parent_item'       => __( 'Parent Tag', 'menapost-custom' ),
						'parent_item_colon' => __( 'Parent Tag:', 'menapost-custom' ),
						'edit_item'         => __( 'Edit Tag', 'menapost-custom' ),
						'update_item'       => __( 'Update Tag', 'menapost-custom' ),
						'add_new_item'      => __( 'Add New Media Type', 'menapost-custom' ),
						'new_item_name'     => __( 'Media Type', 'menapost-custom' ),
						'menu_name'         => __( 'Media Type', 'menapost-custom' ),
					),
					'rewrite'      => array( 'slug' => 'media-type-taxonomy' ),
					'hierarchical' => true,
					'meta_box_cb'  => array(&$this, 'meta_box'),
					'capabilities' => array(
						'manage_terms' => 'manage_categories',
						'edit_terms'   => 'manage_categories',
						'delete_terms' => 'manage_categories',
						'assign_terms' => 'edit_posts'
					)
				)
			);//register_taxonomy

			register_taxonomy(
				'content-type-taxonomy',
				'post',
				array(
					'labels'        => array(
						'name'              => __( 'Content Type', 'menapost-custom' ),
						'singular_name'     => __( 'Content Type', 'menapost-custom' ),
						'search_items'      => __( 'Search Content Type', 'menapost-custom' ),
						'all_items'         => __( 'All Content Type', 'menapost-custom' ),
						'parent_item'       => __( 'Parent Tag', 'menapost-custom' ),
						'parent_item_colon' => __( 'Parent Tag:', 'menapost-custom' ),
						'edit_item'         => __( 'Edit Tag', 'menapost-custom' ),
						'update_item'       => __( 'Update Tag', 'menapost-custom' ),
						'add_new_item'      => __( 'Add New Content Type', 'menapost-custom' ),
						'new_item_name'     => __( 'Content Type', 'menapost-custom' ),
						'menu_name'         => __( 'Content Type', 'menapost-custom' ),
					),
					'rewrite'      => array( 'slug' => 'content-type-taxonomy' ),
					'hierarchical' => true,
					'meta_box_cb'  => array(&$this, 'meta_box'),
					'capabilities' => array(
						'manage_terms' => 'manage_categories',
						'edit_terms'   => 'manage_categories',
						'delete_terms' => 'manage_categories',
						'assign_terms' => 'edit_posts'
					)
				)
			);//register_taxonomy

			register_taxonomy(
				'authorship-taxonomy',
				'post',
				array(
					'labels'        => array(
						'name'              => __( 'Authorship', 'menapost-custom' ),
						'singular_name'     => __( 'Authorship', 'menapost-custom' ),
						'search_items'      => __( 'Search Authorship', 'menapost-custom' ),
						'all_items'         => __( 'All Authorships', 'menapost-custom' ),
						'parent_item'       => __( 'Parent Tag', 'menapost-custom' ),
						'parent_item_colon' => __( 'Parent Tag:', 'menapost-custom' ),
						'edit_item'         => __( 'Edit Authorship', 'menapost-custom' ),
						'update_item'       => __( 'Update Authorship', 'menapost-custom' ),
						'add_new_item'      => __( 'Add New Authorship', 'menapost-custom' ),
						'new_item_name'     => __( 'Authorship', 'menapost-custom' ),
						'menu_name'         => __( 'Authorship', 'menapost-custom' ),
					),
					'rewrite'      => array( 'slug' => 'authorship-taxonomy' ),
					'hierarchical' => true,
					'meta_box_cb'  => array(&$this, 'meta_box'),
					'capabilities' => array(
						'manage_terms' => 'manage_categories',
						'edit_terms'   => 'manage_categories',
						'delete_terms' => 'manage_categories',
						'assign_terms' => 'edit_posts'
					)
				)
			);//register_taxonomy

			register_taxonomy(
				'source-taxonomy',
				'post',
				array(
					'labels'        => array(
						'name'              => __( 'Source', 'menapost-custom' ),
						'singular_name'     => __( 'Source', 'menapost-custom' ),
						'search_items'      => __( 'Search Source', 'menapost-custom' ),
						'all_items'         => __( 'All Source', 'menapost-custom' ),
						'parent_item'       => __( 'Parent Tag', 'menapost-custom' ),
						'parent_item_colon' => __( 'Parent Tag:', 'menapost-custom' ),
						'edit_item'         => __( 'Edit Source', 'menapost-custom' ),
						'update_item'       => __( 'Update Source', 'menapost-custom' ),
						'add_new_item'      => __( 'Add New Source', 'menapost-custom' ),
						'new_item_name'     => __( 'Source', 'menapost-custom' ),
						'menu_name'         => __( 'Source', 'menapost-custom' ),
					),
					'rewrite'      => array( 'slug' => 'source-taxonomy' ),
					'hierarchical' => true,
					'meta_box_cb'  => array(&$this, 'meta_box'),
					'capabilities' => array(
						'manage_terms' => 'manage_categories',
						'edit_terms'   => 'manage_categories',
						'delete_terms' => 'manage_categories',
						'assign_terms' => 'edit_posts'
					)
				)
			);//register_taxonomy
			register_taxonomy(
				'season-taxonomy',
				'post',
				array(
					'labels'        => array(
						'name'              => __( 'Season', 'menapost-custom' ),
						'singular_name'     => __( 'Season', 'menapost-custom' ),
						'search_items'      => __( 'Search Season', 'menapost-custom' ),
						'all_items'         => __( 'All Season', 'menapost-custom' ),
						'parent_item'       => __( 'Parent Tag', 'menapost-custom' ),
						'parent_item_colon' => __( 'Parent Tag:', 'menapost-custom' ),
						'edit_item'         => __( 'Edit Season', 'menapost-custom' ),
						'update_item'       => __( 'Update Season', 'menapost-custom' ),
						'add_new_item'      => __( 'Add New Season', 'menapost-custom' ),
						'new_item_name'     => __( 'Season', 'menapost-custom' ),
						'menu_name'         => __( 'Season', 'menapost-custom' ),
					),
					'rewrite'      => array( 'slug' => 'season-taxonomy' ),
					'hierarchical' => true,
					'meta_box_cb'  => array(&$this, 'meta_box'),
					'capabilities' => array(
						'manage_terms' => 'manage_categories',
						'edit_terms'   => 'manage_categories',
						'delete_terms' => 'manage_categories',
						'assign_terms' => 'edit_posts'
					)
				)
			);//register_taxonomy

			register_taxonomy(
				'geography-taxonomy',
				'post',
				array(
					'labels'        => array(
						'name'              => __( 'Geography', 'menapost-custom' ),
						'singular_name'     => __( 'Geography', 'menapost-custom' ),
						'search_items'      => __( 'Search Geography', 'menapost-custom' ),
						'all_items'         => __( 'All Geography', 'menapost-custom' ),
						'parent_item'       => __( 'Parent Tag', 'menapost-custom' ),
						'parent_item_colon' => __( 'Parent Tag:', 'menapost-custom' ),
						'edit_item'         => __( 'Edit Geography', 'menapost-custom' ),
						'update_item'       => __( 'Update Geography', 'menapost-custom' ),
						'add_new_item'      => __( 'Add New Geography', 'menapost-custom' ),
						'new_item_name'     => __( 'Geography', 'menapost-custom' ),
						'menu_name'         => __( 'Geography', 'menapost-custom' ),
					),
					'rewrite'      => array( 'slug' => 'geography-taxonomy' ),
					'hierarchical' => true,
					'meta_box_cb'  => array(&$this, 'meta_box'),
					'capabilities' => array(
						'manage_terms' => 'manage_categories',
						'edit_terms'   => 'manage_categories',
						'delete_terms' => 'manage_categories',
						'assign_terms' => 'edit_posts'
					)
				)
			);//register_taxonomy

		}//  public function register_taxonomy() {

		function meta_box( $post, $box ) {
			$defaults = array('taxonomy' => 'category');
			if ( !isset($box['args']) || !is_array($box['args']) )
				$args = array();
			else
				$args = $box['args'];
			extract( wp_parse_args($args, $defaults), EXTR_SKIP );
			$tax = get_taxonomy($taxonomy);

			?>
			<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
				<ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
					<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all"><?php echo $tax->labels->all_items; ?></a></li>
				</ul>

				<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
					<?php
		            $name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
		            echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
		            ?>
					<ul id="<?php echo $taxonomy; ?>checklist" data-wp-lists="list:<?php echo $taxonomy?>" class="categorychecklist form-no-clear">
						<?php wp_terms_checklist($post->ID, array( 'taxonomy' => $taxonomy, 'descendants_and_self ' => True, 'checked_ontop' => False ) ) ?>
					</ul>
				</div>
			</div>
			<?php
		}

	}
}

