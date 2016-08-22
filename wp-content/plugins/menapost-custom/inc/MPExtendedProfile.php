<?php 

if( !class_exists( 'MPExtendedProfile' ) ) {

	require_once( dirname(__FILE__) . '/MPPlugin.php' );

	class MPExtendedProfile extends MPPlugin {
		const MP_EXTENDED_PROFILE_VERSION = 0.1;

		function __construct() {
			parent::__construct( 'MPExtendedProfile', self::MP_EXTENDED_PROFILE_VERSION );

			add_action( 'show_user_profile', array( &$this, 'extra_fields' ) );
			add_action( 'edit_user_profile', array( &$this, 'extra_fields' ) );	

			add_action( 'personal_options_update', array( &$this, 'save_extra_fields' ) );
			add_action( 'edit_user_profile_update', array( &$this, 'save_extra_fields' ) );
	                
	                
	        //User Profile Picture
	        add_action( 'show_user_profile', array( &$this, 'my_avatar_fields' ) );
	        add_action( 'edit_user_profile', array( &$this, 'my_avatar_fields' ) );
	        
	        add_action('personal_options_update', array( &$this, 'my_user_profile_update_action' ) );
	        add_action('edit_user_profile_update', array( &$this, 'my_user_profile_update_action' ) );

	        add_action( 'in_admin_footer', array( &$this, 'image_upload_modal' ) );

	        add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
                
                //Team Member`s Page
                add_action( 'admin_menu', array(&$this, 'team_member_section') );
                
                //Social Plugins Addition
                add_filter( 'user_contactmethods', array( $this, 'update_contactmethods' ), 10, 1 );
		}

		function enqueue_scripts() {
			$base = plugin_dir_url( __FILE__ );

			wp_enqueue_style( 'foundation' , $base . 'css/foundation.css' );
			wp_enqueue_style( 'reveal' , $base . 'css/jquery.Jcrop.min.css' );

			wp_enqueue_script( 'foundation' , $base . 'js/foundation.js' , array( 'jquery' ), 1, true );
			wp_enqueue_script( 'foundation.reveal' , $base . 'js/foundation.reveal.js' , array( 'jquery', 'foundation' ), 1, true );
			wp_enqueue_script( 'ajaxupload' , $base . 'js/ajaxupload.js' , array( 'jquery' ), 1, true );
			wp_enqueue_script( 'jquery.jcrop' , $base . 'js/jquery.Jcrop.min.js' , array( 'jquery' ), 1, true );
		}
                
        function update_contactmethods( $contactmethods ) {
			// Add Google+
			$contactmethods['googleplus'] = __( 'Google+ Profile URL', 'menapost-custom' );
			// Add Twitter
			$contactmethods['twitter'] = __( 'Twitter username (without @)', 'menapost-custom' );
			// Add Facebook
			$contactmethods['facebook'] = __( 'Facebook Profile URL', 'menapost-custom' );
			// Add Preferred Email
			$contactmethods['pref_email'] = __( 'Prefered Email', 'menapost-custom' );
			// Add Nationality
			$contactmethods['nationality'] = __( 'Nationality', 'menapost-custom' );

			return $contactmethods;
		}

        function team_member_section(){
            add_users_page( 'Team Member Section', 'Team Member', 'edit_users', 'team-member-section', array(&$this, 'team_member_section_func') ); 
        }

        function team_member_section_func(){
            include(ABSPATH .'wp-content/plugins/menapost-custom/team-member-section.php');
        }

		function extra_fields( $user ) {
			?>
			<?php if( current_user_can( 'manage_options' ) ): ?>
		    <h3><?php _e( 'Kasra Team', 'menapost-custom' ); ?></h3>
			<table class="form-table">
			    <tr>
			        <th><label for="is-team-member"><?php _e( 'Is Team Member?', 'menapost-custom' ); ?></label></th>
			        <td>
			        	<input type="checkbox" name="is-team-member" <?php if( get_the_author_meta( 'is-team-member', $user->ID ) ) echo 'checked="checked"'; ?> />
			            <span class="description"><?php _e( 'Select if user is a team member.', 'menapost-custom' ); ?></span>
			        </td>
			    </tr>
			</table>
			<?php endif; ?>
			<?php

		}

		function check_for_ghost_author( $user_id ) {
			$user = new WP_User( $user_id );
			if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
				foreach ( $user->roles as $role )
					if( $role == 'ghost_authors' )
						return true;
			}
			return false;
		}

		function save_extra_fields( $user_id ) {
			if ( !current_user_can( 'edit_user', $user_id ) )
	        	return false;
	    	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
	    	update_user_meta( $user_id, 'facebook', $_POST['facebook'] );
	    	update_user_meta( $user_id, 'pref_email', $_POST['pref_email'] );
	    	update_user_meta( $user_id, 'nationality', $_POST['nationality'] );
	    	

	    	if( current_user_can( 'manage_options' ) ) {
		    	if( isset( $_POST['is-team-member'] ) ) {
		    		update_user_meta( $user_id, 'is-team-member', $_POST['is-team-member'] );
		    	} else {
		    		update_user_meta( $user_id, 'is-team-member', FALSE );
		    	} 
	    	}
	    	
	    	if( isset( $_POST['title'] ) )
	    		update_user_meta( $user_id, 'title', $_POST['title'] );
	    	if( isset( $_POST['team-member-order'] ) )
	    		update_user_meta( $user_id, 'team-member-order', intval($_POST['team-member-order']) );
		}
	        
	    function my_avatar_fields( $user ) {
			$current_user                = $user->ID;
			$profile_picture             = get_user_meta($current_user, 'profile_picture', true);
			$profile_picture_author_page = get_user_meta($current_user, 'profile_picture_author_page', true);
			$profile_picture_team_page   = get_user_meta($current_user, 'profile_picture_team_page', true);
			$profile_picture_thumbnail   = get_user_meta($current_user, 'profile_picture_thumbnail', true);
	    ?>
                <?php if( current_user_can( 'manage_options' ) ): ?>
            <div id="profile-avatar-section">
		        <h3><?php _e( 'Avatar', 'menapost-custom' ); ?></h3>

		        <table class="form-table">
		            <tr>
		                <th><label for="profile_picture"><?php _e( 'Upload Avatar Here', 'menapost-custom' ); ?></label></th>
		                <td>
		                    <input type="hidden" name="profile_picture" id="profile_picture" value="<?php echo $profile_picture; ?>" />
		                    <input type="hidden" name="profile_picture_author_page" id="profile_picture_author_page" value="<?php echo $profile_picture_author_page; ?>" />
		                    <input type="hidden" name="profile_picture_team_page" id="profile_picture_team_page" value="<?php echo $profile_picture_team_page; ?>" />
		                    <input type="hidden" name="profile_picture_thumbnail" id="profile_picture_thumbnail" value="<?php echo $profile_picture_thumbnail; ?>" />
		                    <a data-target="open-image-upload-modal" class="button-primary">Click Here to Upload Image</a>
		                    <img class="author-page-thumbnail-preview" style="<?php echo ( $profile_picture_author_page == '' ) ? 'display: none;' : ''; ?>" id="author-page-thumbnail" src="<?php echo $profile_picture_author_page; ?>">
		                </td>
		                <!-- <td class="team-page-thumbnail-preview"><img class="team-page-thumbnail-preview" style="<?php // echo ( $profile_picture_team_page == '' ) ? 'display: none;' : ''; ?>" id="team-page-thumbnail" src="<?php // echo $profile_picture_team_page; ?>"></td> -->
		            </tr>
		        </table>
	        </div>
                <?php endif; ?>

	    <?php 
	    }

	    function my_user_profile_update_action( $user_id ) {
	    	if( isset( $_POST['profile_picture'] ) )
	        	update_user_meta( $user_id, 'profile_picture', $_POST['profile_picture'] );
	        if( isset( $_POST['profile_picture_author_page'] ) )
	        	update_user_meta( $user_id, 'profile_picture_author_page', $_POST['profile_picture_author_page'] );
	        if( isset( $_POST['profile_picture_team_page'] ) )
	        	update_user_meta( $user_id, 'profile_picture_team_page', $_POST['profile_picture_team_page'] );
	        if( isset( $_POST['profile_picture_thumbnail'] ) )
	        	update_user_meta( $user_id, 'profile_picture_thumbnail', $_POST['profile_picture_thumbnail'] );
	    }

	    function image_upload_modal() {
	    	?>
				<div id="image-upload-modal" class="reveal-modal" data-reveal>
					<h1 data-modal-heading></h1>
					
					<div class="spinner" id="image-upload-spinner"></div>
					<p id="preview-image">
						<img>
					</p>

					<form action="<?php echo plugins_url( 'ImageUpload.php', __FILE__ ); ?>" id="image-upload-form">
						<label>Upload a Picture of Yourself <span class="red-color">(Minimum 630x630)</span></label>
						<input type="file" size="20" id="imageUpload" class=" ">
					</form>

					<div class="crop-image-action"><button class="make-thumbnail element-hidden">Crop Image</button><span class="spinner"></span></div>

					<a class="close-reveal-modal">&#215;</a>
				</div>
	    	<?php
	    }

	    public static function save_image( $path, $x, $y, $w, $h, $new_w, $new_h ) {
	    	$image_editor = wp_get_image_editor( $path );

			if( is_wp_error( $image_editor ) ) {
				wp_send_json_error( 'Unable to open the editor.' );
			}	    	
			
			$image_editor->crop( $x, $y, $w, $h, $new_w, $new_h );

			$saved = $image_editor->save();
			$matches = array();
	    	preg_match('/^.*?(wp-content\/.*)$/', $saved['path'], $matches);
			$saved[ 'url' ] = home_url( $matches[1] );
			return $saved;
	    }

	    public static function image_crop() {

	    	$image = $_POST[ 'image' ];
			$result = array();

			$image_url = $image[ 'url' ];

			
			$matches = array();
	    	preg_match('/^.*?(wp-content\/.*)$/', $image_url, $matches);

	    	$path = ABSPATH . $matches[1];
			

			// Resize and make a 200 x 200 Image
			$result['author_page'] = self::save_image( $path, $image[ 'step1x1' ], $image[ 'step1y1' ], $image[ 'step1w' ], $image[ 'step1h' ], 200, 200 );
			$result['thumbnail'] = self::save_image( $path, $image[ 'step1x1' ], $image[ 'step1y1' ], $image[ 'step1w' ], $image[ 'step1h' ], 40, 40 );
			$result['team_page'] = self::save_image( $path, $image[ 'step2x1' ], $image[ 'step2y1' ], $image[ 'step2w' ], $image[ 'step2h' ], 630, 315 );

			
			wp_send_json_success( $result );
	    }
	}
}

add_action( 'wp_ajax_image_crop', array( 'MPExtendedProfile', 'image_crop' ) );
