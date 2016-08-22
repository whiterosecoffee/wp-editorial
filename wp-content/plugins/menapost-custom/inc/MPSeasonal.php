<?php

if( !class_exists( 'MPSeasonal' ) ) {
	require_once( dirname(__FILE__) . '/MPPlugin.php' );

	class MPSeasonal extends MPPlugin {
		const MP_SEASONAL_VERSION = 0.1;

		public function __construct() {
			parent::__construct( 'MPSeasonal', self::MP_SEASONAL_VERSION );
			$this->register_taxonomy();
		}

		public function register_taxonomy() {
			register_taxonomy(
				'seasonal',
				'post',
				array(
					'labels'        => array(
						'name'              => __( 'Seasonal Tags', 'menapost-custom' ),
						'singular_name'     => __( 'Seasonal Tag', 'menapost-custom' ),
						'search_items'      => __( 'Search Seasonal Tags', 'menapost-custom' ),
						'all_items'         => __( 'All Seasonal Tags', 'menapost-custom' ),
						'parent_item'       => __( 'Parent Tag', 'menapost-custom' ),
						'parent_item_colon' => __( 'Parent Tag:', 'menapost-custom' ),
						'edit_item'         => __( 'Edit Tag', 'menapost-custom' ),
						'update_item'       => __( 'Update Tag', 'menapost-custom' ),
						'add_new_item'      => __( 'Add New Tag', 'menapost-custom' ),
						'new_item_name'     => __( 'New Tag Name', 'menapost-custom' ),
						'menu_name'         => __( 'Seasonal Tags', 'menapost-custom' ),
					),
					'rewrite'      => array( 'slug' => 'seasonal' ),
					'hierarchical' => true,
					'meta_box_cb'  => array(&$this, 'meta_box'),
					'capabilities' => array(
						'manage_terms' => 'manage_categories',
						'edit_terms'   => 'manage_categories',
						'delete_terms' => 'manage_categories',
						'assign_terms' => 'edit_posts'
					)
				)
			);
		}

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

