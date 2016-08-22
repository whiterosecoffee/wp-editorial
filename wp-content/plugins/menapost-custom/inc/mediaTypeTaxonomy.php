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

