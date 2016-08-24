<?php
$error_code = http_response_code();
$error_message = get_error_message( $error_code );

add_filter( 'wp_title', 'error_page_title' );
function error_page_title( $title )
{
  	return get_error_message();
}
do_action( 'navbar' ); 

?>

    <!-- Error content -->
    <div class="jumbotron error-container">
        <div class="container">
            <div class="error-img">
                <img src="<?php echo CHILD_URL . '/img/error.png'; ?>">
            </div>
            <h1><?php _e( 'Oh No! Looks like the page you are looking for doesn’t exist. Please try searching again.', 'menapost-theme' ); ?></h1>
            <p><?php echo $error_message; ?></p>
            <p class="redirect animated fadeInDown"><?php _e( 'You will be redirected to', 'menapost-theme' ); ?> <a href="<?php echo get_home_url(); ?>">kasra.co</a> <?php _e( 'in', 'menapost-theme' ); ?><span id="redirect"></span> <?php _e( 'seconds', 'menapost-theme' ); ?></p>
        </div>

    </div>

	<script type="text/javascript">
		var i = 10;

		setInterval(function () {
			jQuery("#redirect").html((i < 0 ? 0 : i));
			if(i === 0) {
				window.location = '<?php echo get_home_url(); ?>';
			}
			i--;
		}, 1000);
        </script>

<?php
/**
 * Loads genesis engine.
 */
genesis();