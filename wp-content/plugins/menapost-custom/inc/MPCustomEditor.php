<?php

if( !class_exists( 'MPCustomEditor' ) )  {

require_once( dirname(__FILE__) . '/MPPlugin.php' );

class MPCustomEditor extends MPPlugin {

	const MP_CUSTOM_EDITOR_VERSION = 0.2;

	const MP_SCRIPTS_STYLES_VERSION = 1.162;

	function __construct() {
		parent::__construct( 'MPCustomEditor', self::MP_CUSTOM_EDITOR_VERSION );

		// Adds the filter to format tinymce editor.
		add_filter('tiny_mce_before_init', array( &$this, 'mp_format_tiny_mce' ) );

		// Set tinymce as default editor
		add_filter( 'wp_default_editor', array( &$this, 'mp_default_editor' ) );

		// Add image field.
		add_action( 'add_meta_boxes', array( &$this, 'meta_boxes' ) );

		add_action( 'save_post', array( &$this, 'save_post' ) );
		add_action( 'save_post', array( &$this, 'save_ghost_author' ) );

		add_action( 'admin_enqueue_scripts', array( &$this, 'image_scripts_enqueue' ) );

		add_action( 'admin_menu', array( &$this, 'remove_meta_boxes' ) );

		add_filter('media_view_strings', array( &$this, 'remove_media_tab' ) );

        add_action('wp_ajax_sort_teammember_ajax', array( &$this, 'sort_teammember_ajax' ) );
        add_action('wp_ajax_nopriv_sort_teammember_ajax', array( &$this, 'sort_teammember_ajax' ) );

        add_action('wp_ajax_field_teammember_ajax', array( &$this, 'field_teammember_ajax' ) );
        add_action('wp_ajax_nopriv_field_teammember_ajax', array( &$this, 'field_teammember_ajax' ) );

        add_action( 'in_admin_footer', array( &$this, 'post_image_upload_modal' ) );
        add_action( 'wp_ajax_post_image_crop', array( &$this, 'post_image_crop' ) );
        add_action( 'wp_ajax_nopriv_post_image_crop', array( &$this, 'post_image_crop' ) );

        add_editor_style( plugin_dir_url( __FILE__ ) . 'css/custom-editor-style.css' );
        

		$this->restrict_add_tag_buttons();

		$this->allow_contributors_to_upload_images();
	}

        function sort_teammember_ajax() {
            $data_arr = isset($_POST['data_arr']) ? $_POST['data_arr'] : "";

            foreach ($data_arr as $data) {
//                echo "<br>" . $data[0] . " - " . $data[1];
                update_user_meta($data[0], 'team-member-order', $data[1]);
            }

            exit();
        }

        function field_teammember_ajax() {
            $id = isset($_POST['id']) ? $_POST['id'] : "";
            $field_name = isset($_POST['field_name']) ? $_POST['field_name'] : "";
            $field_value = isset($_POST['field_value']) ? $_POST['field_value'] : "";

            update_user_meta($id, $field_name, $field_value);
            echo $id . " - " . $field_name . " - " . $field_value;
            exit();
        }

	function restrict_add_tag_buttons() {

		$current_user = wp_get_current_user();
		if ( !($current_user instanceof WP_User) )
		   return;
		$roles = $current_user->roles;
		if( empty( $roles ) )
			return;
		$role = $roles[0];

		if( $role != 'administrator' && current_user_can( 'manage_categories' ) ) {
			$editor = get_role( $role );
			$editor->remove_cap( 'manage_categories' );
		}

	}

	function allow_contributors_to_upload_images() {
		$current_user = wp_get_current_user();
		if ( !($current_user instanceof WP_User) )
		   return;
		$roles = $current_user->roles;
		if( !empty( $roles ) ) {
			$role = $roles[0];
			if( $role == 'contributor' && !current_user_can( 'upload_files' ) ) {
				$role = get_role( $role );
			    // This only works, because it accesses the class instance.
			    // would allow the author to edit others' posts for current theme only
			    $role->add_cap( 'upload_files' );
			}
		}
	}

	function get_current_user_role() {
		$current_user = wp_get_current_user();
		if ( !($current_user instanceof WP_User) )
		   return;
		$roles = $current_user->roles;
		if( !empty( $roles ) ) {
			return $role = $roles[0];
		}
		return '';
	}

	function remove_media_tab($strings) {

		unset($strings["insertFromUrlTitle"]);
		return $strings;
	}

	private function mp_remove_text_tab() {
		echo '  <style type="text/css">
				a#content-html{
					display:none;
				}

				textarea {
					width: 100%;
					height: 100px;
				}
				</style>';
	}

	function mp_format_tiny_mce($in)
	{
		global $typenow;
		$this->mp_remove_text_tab();
		$in['init_instance_callback']        = "disableShortcuts";
		//$in['setup'] = "tinyMceSetup";
		$in['apply_source_formatting'] = false;
		$in['paste_as_text'] = true;
		if( $typenow == 'page' || $typenow == 'post' ) {
			$in['toolbar1'] = 'bullist,numlist,link,unlink,formatselect,rtl,ltr,fullscreen';
		} else {
			$in['toolbar1'] = 'bullist,numlist,link,unlink';
		}
		$in['toolbar2'] = '';

		$in['formats'] = json_encode( array(
			'copy' => array(
					'title' => 'Copyright',
		            'block' => 'small',
		            'classes' => 'copyright',
		            'exact'  => true
				)
			));

        $style_formats = array(
        	// array('title' => 'Paragraph',
        	// 	'items' => array(
	        // 			array(
				     //        'title' => 'Normal',
				     //        'format' => 'p',
				     //    ),
				     //    array(
				     //        'title' => 'Copyright Text',
				     //        'selector' => 'p',
				     //        'classes' => 'copyright'
				     //    )
			      //   )
        	// 	)
        	array(
	            'title' => 'Copyright',
	            'block' => 'small',
	            'classes' => 'copyright',
	            'exact'  => true
	        ),
    );

    // $in['style_formats'] = json_encode( $style_formats );

    $in['block_formats'] = 'Paragraph=p;Header 2=h2;Header 3=h3';




		return $in;
	}

	function mp_default_editor() {
		return 'tinymce';
	}


	function remove_meta_boxes() {
		remove_action('admin_notices','update_nag',3);

		remove_meta_box( 'genesis_inpost_seo_box', 'post', 'normal' );
		remove_meta_box( 'genesis_inpost_layout_box', 'post', 'normal' );
		remove_meta_box( 'genesis_inpost_scripts_box', 'post', 'normal' );
	}

	function user_can_upload_images() {
		return current_user_can( 'edit_published_posts' );
	}

	function image_meta_box( $post ) {
		wp_nonce_field( basename( __FILE__ ), 'mp_nonce' );
		$post_meta = get_post_meta( $post->ID );
		?>
		<p>
		    <label for="meta-image" class="mp-row-title"><?php _e( 'Post Header Image (min 1200x600)', 'menapost-custom' )?></label>
		    <input type="text" name="image" id="image" value="<?php if ( isset ( $post_meta['image'] ) ) echo $post_meta['image'][0]; ?>" />
		    <input type="hidden" name="image-attachment-id" id="image-attachment-id" value="<?php if ( isset ( $post_meta['image-attachment-id'] ) ) echo $post_meta['image-attachment-id'][0]; ?>" />
		    <input type="button" id="image-button" class="button" data-target="image" data-width="1200" data-height="600" value="<?php _e( 'Choose or Upload an Image', 'menapost-custom' )?>" />
                    <img style="margin-left: 60px; vertical-align: middle; <?php if ( !isset ( $post_meta['image'] ) ) echo "display: none;"; ?>" class="page-thumbnail-preview" id="post-page-thumbnail" src="<?php if ( isset ( $post_meta['image'] ) ) echo $post_meta['image'][0]; ?>" width="100px" height="auto">
		</p>
		<?php
	}

        function post_image_upload_modal() {
            ?>
            <div id="post-image-upload-modal" class="reveal-modal" data-reveal>
                <h1 data-modal-heading>Step 1: Upload Header Image</h1>

                <div class="spinner" id="post-image-upload-spinner"></div>
                <p id="post-preview-image" style="overflow: auto; max-width: 715px; max-height: 550px;">
                    <img>
                </p>

                <form action="<?php echo plugins_url( 'ImageUpload.php', __FILE__ ); ?>" id="post-image-upload-form">
                    <label>Upload an image <span class="red-color">(Minimum 1200x600)</span></label>
                    &nbsp;&nbsp;&nbsp;
                    <input type="button" size="20" id="post-imageUpload" class="button" value="Add Image" data-target="image" data-width="1200" data-height="600" >
                </form>

                <div class="crop-image-action"><button class="make-thumbnail element-hidden">Crop Image</button><span class="spinner"></span></div>

                <a class="close-reveal-modal">&#215;</a>
            </div>
            <?php
        }

        function save_image($path, $x, $y, $w, $h, $new_w, $new_h) {
            $image_editor = wp_get_image_editor($path);

            if (is_wp_error($image_editor)) {
                wp_send_json_error('Unable to open the editor.');
            }

            $image_editor->crop($x, $y, $w, $h, $new_w, $new_h);

            $saved = $image_editor->save();
            $matches = array();
            preg_match('/^.*?(wp-content\/.*)$/', $saved['path'], $matches);
            $saved['url'] = home_url($matches[1]);
            return $saved;
        }

        function post_image_crop() {

            $image = $_POST[ 'image' ];
            $result = array();

            $image_url = $image[ 'url' ];

            $matches = array();
            preg_match('/^.*?(wp-content\/.*)$/', $image_url, $matches);

            $path = ABSPATH . $matches[1];
            $wp_upload_dir = wp_upload_dir();

            $result['post_page_thumbnail'] = self::save_image( $path, $image[ 'step1x1' ], $image[ 'step1y1' ], $image[ 'step1w' ], $image[ 'step1h' ], 1200, 600 );

            $path = $result['post_page_thumbnail']['path'];

            $attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $path ),
				'post_mime_type' => $image[ 'type' ],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $path ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

            $attach_id = wp_insert_attachment( $attachment, $path, $image[ 'post_id' ] );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $path );
  			wp_update_attachment_metadata( $attach_id,  $attach_data );

            $result['post_page_thumbnail']['attachment_id'] = $attach_id;
            //mp_log( $result );
            wp_send_json_success( $result );
        }

	function read_duration_metabox( $post ) {
		wp_nonce_field( basename( __FILE__ ), 'mp_nonce' );
		$post_meta = get_post_meta( $post->ID );
		?>
		<p>
		    <label for="read-duration" class="prfx-row-title"><?php _e( 'Read Duration (minutes) ', 'menapost-custom' )?></label>
		    <input type="text" name="read-duration" readonly="readonly" id="read-duration" value="<?php if ( isset ( $post_meta['read-duration'] ) ) echo $post_meta['read-duration'][0]; ?>" />
		</p>
		<?php
	}

	function co_author_metabox( $post ) {
		wp_nonce_field( basename( __FILE__ ), 'mp_nonce' );
		$post_meta = get_post_meta( $post->ID );
		?>
		<p>
		    <label for="co-author" class="prfx-row-title"><?php _e( 'Co-Author', 'menapost-custom' )?> <?php _e( '(26 Characters Maximum)', 'menapost-custom' )?></label>
		    <input type="text" maxlength="26" size="26" name="co-author" id="co-author" value="<?php if ( isset ( $post_meta['co-author'] ) ) echo $post_meta['co-author'][0]; ?>" />
		</p>
		<?php
	}

	function ghost_author_metabox( $post ) {
		wp_nonce_field( basename( __FILE__ ), 'mp_nonce' );
		$post_meta = get_post_meta( $post->ID );
		$current_author_id = $post->post_author;
		$ghost_authors = get_users( 'role=ghost_authors&orderby=display_name' );
		?>
		<p>
		    <?php if( !empty( $ghost_authors ) ) : ?>
		    	<label for="ghost-author" class="prfx-row-title"><?php _e( 'Select an Author', 'menapost-custom' )?></label>
			    <select name="ghost-author" id="ghost-author">
			    	<option value="-1">None</option>
			    <?php foreach( $ghost_authors as $ghost_author ) : ?>
			    	<option value="<?php echo $ghost_author->ID; ?>" <?php if( $ghost_author->ID == $current_author_id ): echo 'selected="true"'; endif; ?>><?php echo $ghost_author->display_name; ?></option>
			    <?php endforeach; ?>
			    </select>
			<?php else : ?>
				<i><?php _e( 'No ghost authors in the system', 'menapost-custom' ); ?></i>
			<?php endif; ?>
			<?php add_thickbox();
			if( current_user_can( 'delete_published_posts' ) ) : ?>
			<a href="#TB_inline?width=580&height=500&inlineId=new-ghost-author-modal" title="Add New Ghost Author" class="thickbox" id="add-new-ghost-author"><?php _e( 'Add New Ghost Author', 'menapost-custom' ); ?></a>
			<?php endif; ?>
		</p>

		<?php if( current_user_can( 'delete_published_posts' ) ) : ?>
		<div id="new-ghost-author-modal" style="display:none;">
				<table class="form-table" id="ghost-author-fields-table">
					<tbody>
						<tr class="form-field">
							<th scope="row"><label for="user_first_name">First Name</label></th>
							<td><input name="user_first_name" type="text" id="user_first_name" value=""></td>
						</tr>
						<tr class="form-field">
							<th scope="row"><label for="user_last_name">Last Name</label></th>
							<td><input name="user_last_name" type="text" id="user_last_name" value=""></td>
						</tr>
						<tr class="form-field">
							<th scope="row"><label for="user_email">Email</label></th>
							<td><input name="user_email" type="email" id="user_email" value=""></td>
						</tr>
						<tr class="form-field">
							<th scope="row"><label for="username">Username</label></th>
							<td><input name="username" type="text" id="username" value=""></td>
						</tr>
						<tr class="form-field">
							<th scope="row"><label for="user_twitter">Twitter</label></th>
							<td>@<input name="user_twitter" type="text" id="user_twitter" value="" placeholder="<?php _e( 'username', 'menapost-custom' ); ?>" style="width: 91%;"></td>
						</tr>
						<tr class="form-field">
							<th scope="row"><label for="user_facebook">Facebook</label></th>
							<td><input name="user_facebook" type="text" id="user_facebook" value="" placeholder="<?php _e( 'http://facebook.com/username', 'menapost-custom' ); ?>"></td>
						</tr>
						<tr class="form-field">
							<th scope="row"><label for="user_google_plus">Google Plus</label></th>
							<td><input name="user_google_plus" type="text" id="user_google_plus" value="" placeholder="<?php _e( 'https://plus.google.com/user-id', 'menapost-theme' ); ?>"></td>
						</tr>
						<tr class="form-field">
							<th scope="row"><label for="user_description"><?php _e( 'Biographical Info' ); ?></label></th>
							<td><textarea name="user_description" type="text" id="user_description" value=""></textarea></td>
						</tr>
						<tr class="form-field">
			                <th><label for="profile_picture"><?php _e( 'Upload Avatar Here', 'menapost-custom' ); ?></label></th>
			                <td>
			                    <input type="hidden" name="profile_picture" id="profile_picture" value="" />
			                    <input type="hidden" name="profile_picture_author_page" id="profile_picture_author_page" value="" />
			                    <input type="hidden" name="profile_picture_team_page" id="profile_picture_team_page" value="" />
			                    <input type="hidden" name="profile_picture_thumbnail" id="profile_picture_thumbnail" value="" />
			                    <a data-target="open-image-upload-modal" class="button-primary">Click Here to Upload Image</a>
								<div class="ghost-author-profile-thumbnail">
				                    <div class="author-page-thumbnail-preview"><img class="author-page-thumbnail-preview" style="display: none;" id="author-page-thumbnail"></div>
		            				<!-- <div class="team-page-thumbnail-preview"><img class="team-page-thumbnail-preview" style="display: none;" id="team-page-thumbnail"></div> -->
	            				</div>
			                </td>
		            	</tr>


					</tbody>
				</table>
				<p id="ghost-author-form-error" style="color: red; display: none;"><?php _e( 'Please correct the fields above marked in red.', 'menapost-custom' ); ?></p>
				<p class="submit"><input type="submit" name="create-ghost-author-submit" id="create-ghost-author-submit" class="button button-primary" value="<?php _e( 'Submit', 'menapost-custom' ); ?>"><span id="create-ghost-author-spinner" class="spinner"></span></p>
		</div>
		<?php endif; ?>

		<?php
	}

	function scale_down_image( $image, $attachment_id ) {
		$meta_data = wp_get_attachment_metadata( $attachment_id );

		// mp_log( $meta_data );

		if( !$meta_data )
			return $image;

		$original_width = $meta_data[ 'width' ];
		$original_height = $meta_data[ 'height' ];

		$path = ABSPATH . 'wp-content/uploads/' . $meta_data[ 'file' ];
		$image_editor = wp_get_image_editor( $path );

		if( is_wp_error( $image_editor ) ) {
			return $image;
		}

		$sizes_array = array(
	        // #1 - Desktop/Retina
	        array ('width' => 1200, 'height' => 600, 'crop' => true),
	        // #2 - Tablets
	        array ('width' => 960, 'height' => 480, 'crop' => true),
	        // #3 - Mobiles
	        array ('width' => 580, 'height' => 290, 'crop' => true),
	        // #4 - Polaroid
	        array ('width' => 480, 'height' => 240, 'crop' => true)
	    );

		$resized_images = $image_editor->multi_resize( $sizes_array );

		$image_data = array();
		foreach ($resized_images as $resized_image) {
			$key = '';
			if( self::equal_tolerate( $resized_image, 1200, 600 ) ) {
				$key = 'desktop';
			} else if( self::equal_tolerate( $resized_image, 960, 480 ) ) {
				$key = 'tablet';
			} else if( self::equal_tolerate( $resized_image, 580, 290 ) ) {
				$key = 'mobile';
			} else if( self::equal_tolerate( $resized_image, 480, 240 ) ) {
				$key = 'polaroid';
			}
			$upload_dir = wp_upload_dir();
			$resized_image[ 'url' ] = $upload_dir['baseurl'] . '/' . substr( $meta_data[ 'file' ], 0, strrpos( $meta_data[ 'file' ], '/' ) + 1 ) . $resized_image[ 'file' ];
			$image_data[ $key ] = $resized_image;
		}
		$image_data[ 'attachment_id' ] = $attachment_id;
		$image_data[ 'original_image' ] = $image;

		// If desktop thumbnail is not present, then the original image is of desktop size.
		if( !isset( $image_data[ 'desktop' ] ) ) {
			$image_data[ 'desktop' ] = array( 'url' => $image, 'width' => 1200, 'height' => 600 );
		}

		// mp_log( $image_data );

		return $image_data;

	}

	private static function equal_tolerate( $image_meta, $width, $height ) {
		$widthDiff = abs( $image_meta[ 'width' ] - $width );
		$heightDiff = abs( $image_meta[ 'height' ] - $height );

		return $widthDiff >= 0 && $widthDiff <= 6 && $heightDiff >= 0 && $heightDiff <= 6;
	}

	function check_for_sizes( $post_id, $attachment_id ) {
		$resized_images = get_post_meta( $attachment_id, 'resized_images' , true );
		return is_array( $resized_images ) && !empty( $resized_images );
	}

	function save_ghost_author( $post_id ) {

		if ( ! wp_is_post_revision( $post_id ) && isset( $_POST[ 'ghost-author' ] ) ){

			$current_author = get_post_field( 'post_author', $post_id );
			$original_author = get_post_meta( $post_id, 'original-author', true );
			$ghost_author = $_POST[ 'ghost-author' ];
			$post_author = $current_author;

			// When ghost_author is selected and original author is not set.
			if( $ghost_author != -1 ) {
				$post_author = $ghost_author;
				if( $original_author == '' )
					update_post_meta( $post_id, 'original-author', $current_author );
			} else if( $ghost_author == -1 && $original_author != '' ) {
				$post_author = $original_author;
			}

	        $my_post = array(
	            'ID'            => $post_id,
	            'post_author'   => $post_author,
	        );


	        // unhook this function so it doesn't loop infinitely
	        remove_action('save_post', array( &$this, 'save_ghost_author' ) );

	        // update the post, which calls save_post again
	        wp_update_post( $my_post );

	        // re-hook this function
	        add_action('save_post', array( &$this, 'save_ghost_author' ) );

	    }

	}


	function save_post( $post_id ) {

	    // Checks save status
	    $is_autosave = wp_is_post_autosave( $post_id );
	    $is_revision = wp_is_post_revision( $post_id );
	    $is_valid_nonce = ( isset( $_POST[ 'mp_nonce' ] ) && wp_verify_nonce( $_POST[ 'mp_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	    // Exits script depending on save status
	    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
	        return;
	    }

	    // Checks for input and sanitizes/saves if needed
	    if( isset( $_POST[ 'image' ] ) ) {
	    	$image = $_POST[ 'image' ];
	    	$attachment_id = isset( $_POST[ 'image-attachment-id' ] ) ? $_POST[ 'image-attachment-id' ] : 0;

	    	if( $attachment_id != 0 && !$this->check_for_sizes( $post_id, $attachment_id ) ) {
	    		$resized_images = $this->scale_down_image( $image, $attachment_id );
	    	}
	    	if( isset( $resized_images ) && is_array( $resized_images ) ) {
	    		update_post_meta( $attachment_id, 'resized_images', $resized_images );
	    	}

	        update_post_meta( $post_id, 'image', sanitize_text_field( $image ) );

	    }

	    if( isset( $_POST[ 'image-attachment-id' ] ) ) {
	        update_post_meta( $post_id, 'image-attachment-id', sanitize_text_field( $_POST[ 'image-attachment-id' ] ) );
	    }

	    if( isset( $_POST[ 'read-duration' ] ) ) {
	        update_post_meta( $post_id, 'read-duration', sanitize_text_field( $_POST[ 'read-duration' ] ) );
	    }

	    if( isset( $_POST[ 'co-author' ] ) ) {
	        update_post_meta( $post_id, 'co-author', sanitize_text_field( $_POST[ 'co-author' ] ) );
	    }

	}

	function meta_boxes() {
		remove_meta_box( 'postimagediv','post','side' );

		if( $this->user_can_upload_images() ) {
			add_meta_box( 'image', __( 'Post Header Image', 'menapost-custom' ), array( &$this, 'image_meta_box' ), 'post', 'normal', 'high' );
		}

		add_meta_box( 'post_read_duration', __( 'Read Duration', 'menapost-custom' ), array( &$this, 'read_duration_metabox' ), 'post', 'normal', 'low' );
		add_meta_box( 'co_author', __( 'Co-Author', 'menapost-custom' ), array( &$this, 'co_author_metabox' ), 'post', 'normal', 'low' );

		if( user_is( array( 'editor', 'administrator' ) ) ) {
			add_meta_box( 'ghost_author', __( 'Ghost Author', 'menapost-custom' ), array( &$this, 'ghost_author_metabox' ), 'post', 'normal', 'low' );
		}
	}

	function image_scripts_enqueue() {
		global $typenow;
	    if( $typenow == 'post' ) {
	        wp_enqueue_media();

	        // Registers and enqueues the required javascript.
	        wp_register_script( 'custom-wp-editor', plugin_dir_url( __FILE__ ) . 'js/custom_wp_editor.js', array( 'jquery' ), self::MP_SCRIPTS_STYLES_VERSION );
	        wp_localize_script( 'custom-wp-editor', 'custom_editor',
	            array(
					'title'           => __( 'Choose or Upload an Image', 'menapost-custom' ),
					'button'          => __( 'Use this image', 'menapost-custom' ),
					'remaining_chars' => __( 'Remaining Characters', 'menapost-custom' ),
					'ajax_url'        => admin_url( 'admin-ajax.php' ),
					'can_edit_tags'   => current_user_can( 'manage_categories' ),
	            )
	        );
	        wp_enqueue_script( 'custom-wp-editor' );
	    }

	    wp_register_script( 'mp_custom_admin_script', plugin_dir_url( __FILE__ ) . 'js/mp_custom_admin.js', array( 'jquery' ), self::MP_SCRIPTS_STYLES_VERSION, true );
	    wp_localize_script( 'mp_custom_admin_script', 'backend_object',
	            array(
					'remaining_chars'   => __( 'Remaining Characters', 'menapost-custom' ),
					'ajax_url'          => admin_url( 'admin-ajax.php' ),
					'current_user_role' => $this->get_current_user_role(),
					'type_now'          => $typenow,
	            )
	        );
	    wp_enqueue_script( 'mp_custom_admin_script' );
	    wp_enqueue_style( 'mp_custom_admin_style', plugin_dir_url( __FILE__ ) . 'css/custom_admin_style.css', array(), self::MP_SCRIPTS_STYLES_VERSION  );
	}

	private static function tag_count_aggregate( $tags ) {
		$result = array();
		$counts = array();

		foreach ($tags as $key => $value) {
			$counts[ $key ] = $value->count + 1;
		}

		$result[ 'min_count' ] = min( $counts );
		$result[ 'max_count' ] = max( $counts );

		return $result;
	}

	public static function mp_get_tags() {
		$tags = get_terms( array( 'post_tag' ), array( 'hide_empty' => false ) );

		$unit          = 'pt';
		$min_font_size = 8;
		$max_font_size = 22;

		extract( self::tag_count_aggregate( $tags ) );

		$spread = $max_count - $min_count;
		if ( $spread <= 0 )
			$spread = 1;
		$font_spread = $max_font_size - $min_font_size;
		if ( $font_spread < 0 )
			$font_spread = 1;
		$font_step = $font_spread / $spread;

		$result = "";

		foreach ($tags as $key => $value) {
			$result .= "<a href=\"#\" class=\"tag-link-$value->term_id\" title=\"Topics $value->count\" style=\"font-size: " . str_replace( ',', '.', ( $min_font_size + ( ( $value->count + 1 - $min_count ) * $font_step ) ) ) . "$unit;\">$value->name</a>\n";
		}

		echo $result;
		exit;
	}

	public static function mp_new_ghost_author() {
		try {

			$user_details = $_POST['details'];

			$ghost_author = array(
				'user_login' => $user_details[ 'username' ],
				'user_nicename' => $user_details[ 'username' ],
				'user_email' => $user_details[ 'email' ],
				'display_name' => $user_details[ 'firstName' ] . ' ' . $user_details[ 'lastName' ],
				'first_name' => $user_details[ 'firstName' ],
				'last_name' => $user_details[ 'lastName' ],
				'description' => $user_details[ 'description' ],
				'role' => 'ghost_authors',
				'user_pass' => wp_generate_password(),
				);
			$response = wp_insert_user( $ghost_author );

			if( is_wp_error( $response ) ) {
				wp_send_json_error( $response->get_error_message() );
			}

			$user_id = $response;

			update_user_meta( $user_id, 'googleplus', $user_details[ 'googleplus' ] );
			update_user_meta( $user_id, 'twitter', $user_details[ 'twitter' ] );
			update_user_meta( $user_id, 'facebook', $user_details[ 'facebook' ] );
			update_user_meta( $user_id, 'profile_picture', $user_details[ 'profilePicture' ] );
			update_user_meta( $user_id, 'profile_picture_author_page', $user_details[ 'profilePictureAuthorPage' ] );
			update_user_meta( $user_id, 'profile_picture_team_page', $user_details[ 'profilePictureTeamPage' ] );
			update_user_meta( $user_id, 'profile_picture_thumbnail', $user_details[ 'profilePictureThumbnail' ] );


		} catch (Exception $ex) {
			wp_send_json_error( $e->getMessage() );
		}
		wp_send_json_success( array( 'user_id' => $user_id, 'details' => $ghost_author ) );

	}
	}
}

add_action( 'wp_ajax_get-tagcloud', array( 'MPCustomEditor', 'mp_get_tags' ), -99999 );
add_action( 'wp_ajax_new-ghost-author', array( 'MPCustomEditor', 'mp_new_ghost_author' ) );
