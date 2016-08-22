<?php
// create custom plugin settings menu
add_action('admin_menu', 'create_menu', 10);
add_action('admin_menu', 'hide_admin_panel_pages', 10);

add_action( 'wp_ajax_install-copy-editors', 'create_copy_editors' );
add_action( 'wp_ajax_install-ghost-authors', 'create_ghost_authors' );
add_action( 'wp_ajax_update-capabitilies', 'update_capabitilies' );

function create_copy_editors() {
    $result = add_role(
        'copy_editor',
        __( 'Copy Editors' ),
        array(
            'read'                 => true, //contribs
            'edit_posts'           => true, //contribs
            'delete_posts'         => true, //contribs
            'upload_files'         => true, // allow contribs to upload files
            'edit_others_posts'    => true, // edit other posts
            'edit_private_posts'   => true, // edit private posts
            'edit_published_posts' => true, // edit published posts
            'publish_posts'        => true, // publish posts
        )
    );
    if ( null !== $result ) {
        wp_send_json_success( 'Yay! New copy_editor role created!' );
    }
    else {
        $role = get_role( 'copy_editor' );
        $role->add_cap( 'edit_published_posts' ); 
        $role->add_cap( 'publish_posts' ); 
        wp_send_json_error( 'Oh... the copy_editor role already exists.' );
    }
}

function create_ghost_authors() {
    $result = add_role(
        'ghost_authors',
        __( 'Ghost Authors' ),
        array(
            'read'                   => true, // contribs
            'delete_posts'           => true, // contribs
            'edit_posts'             => true, // contribs
            // 'delete_published_posts' => true, // authors
            // 'publish_posts'          => true, // authors
            'upload_files'           => true, // authors
            'edit_published_posts'   => true, // authors
        )
    );
    if ( null !== $result ) {
        wp_send_json_success( 'Yay! New ghost_authors role created!' );
    }
    else {

        $ghost_authors = get_role( 'ghost_authors' );

        $remove_caps = array(
            'delete_published_posts',
            'publish_posts',
        );

        foreach ( $remove_caps as $cap ) {
            // Remove the capability.
            $ghost_authors->remove_cap( $cap );
        }

        wp_send_json_error( 'Ghost Author roles updated!' );
    }
}

function update_capabitilies() {
    $editor = get_role( 'editor' );

    $remove_caps = array(
        'moderate_comments',
        'edit_pages',
    );
    $add_caps = array(
        'install_plugins',
        'update_plugins',
        'activate_plugins',
        'edit_plugins',
        );

    foreach ( $remove_caps as $cap ) {
        // Remove the capability.
        $editor->remove_cap( $cap );
    }

    foreach ( $add_caps as $acap ) {
        // Add the capability.
        $editor->add_cap( $acap );
    }
    
    wp_send_json_success( 'Yay! Roles updated!' );
}

function hide_admin_panel_pages() {
    if( !current_user_can( 'manage_options' ) ) {
        remove_menu_page('edit-comments.php'); 
        remove_menu_page('tools.php'); 
    }
}

function create_menu() {
    //create new top-level menu
	add_menu_page('menaPOST Plugin Settings', 'menaPOST', 'administrator', __FILE__, 'settings_page', NULL);

	//call register settings function
	add_action( 'admin_init', 'register_settings' );
}

function register_settings() {
    register_setting( 'menapost-settings-group', 'facebook_app_id' );
    register_setting( 'menapost-settings-group', 'mp_custom_editor_active' );
    register_setting( 'menapost-settings-group', 'mp_reading_list_active' );
    register_setting( 'menapost-settings-group', 'logo_image' );
    register_setting( 'menapost-settings-group', 'google_search_engine_id' );

    register_setting( 'menapost-settings-group', 'facebook_page' );
    register_setting( 'menapost-settings-group', 'google_plus_page' );
    register_setting( 'menapost-settings-group', 'twitter_handle' );
}

function settings_page() {
    ?>
    <div class="wrap">
        <h2>menaPOST Custom Plugin Settings</h2>

        <?php if( isset($_GET['settings-updated']) ) { ?>
            <div id="message" class="updated">
                <p><strong><?php _e('Settings saved.') ?></strong></p>
            </div>
        <?php } ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'menapost-settings-group' ); ?>
            <?php do_settings_sections( 'menapost-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Custom Editor?</th>
                    <td><input type="checkbox" name="mp_custom_editor_active" <?php if(get_option('mp_custom_editor_active')) echo 'checked'; ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Enable Reading List?</th>
                    <td><input type="checkbox" name="mp_reading_list_active" <?php if(get_option('mp_reading_list_active')) echo 'checked'; ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Facebook App ID</th>
                    <td><input type="text" name="facebook_app_id" value="<?php echo get_option('facebook_app_id'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Twitter Handle</th>
                    <td><input type="text" name="twitter_handle" value="<?php echo get_option('twitter_handle'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Facebook Page</th>
                    <td><input type="text" name="facebook_page" value="<?php echo get_option('facebook_page'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Google Plus Page</th>
                    <td><input type="text" name="google_plus_page" value="<?php echo get_option('google_plus_page'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Logo Image (For Pinterest)</th>
                    <td><input type="text" name="logo_image" value="<?php echo get_option('logo_image'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Google Search Engine ID</th>
                    <td><input type="text" name="google_search_engine_id" value="<?php echo get_option('google_search_engine_id'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Copy Editors Role</th>
                    <td><button id="install-copy-editors">Install</button> <span><i data-element="message"></i></span></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Ghost Authors</th>
                    <td><button id="install-ghost-authors">Install</button> <span><i data-element="message"></i></span></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Update Capabilities</th>
                    <td><button id="update-capabitilies">Install</button> <span><i data-element="message"></i></span></td>
                </tr>
            </table>
    
            <?php submit_button(); ?>

        </form>

    </div>

    <script>
    (function ($) {

        $( '#install-copy-editors, #install-ghost-authors, #update-capabitilies' ).on( 'click', function ( e ) {
            var self = $( this ).attr( 'disabled', true );
            $.getJSON( "<?php echo admin_url( 'admin-ajax.php' ); ?>", { action: self.attr( 'id' ) }, function (res) {
                console.log( res );
                self.parent().find( '[data-element="message"]' ).text( res.data );
                self.removeAttr( 'disabled' );
            });
        });

    })( jQuery );
    </script>

<?php } ?>