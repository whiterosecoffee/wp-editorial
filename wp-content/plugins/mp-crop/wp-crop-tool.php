<?php
/*
Plugin Name: WP Crop Tool - Test
Plugin URI: http://venturedive.com/
Description: Allows cropping of image in admin panel.
Version: 0.1
Author: Ahmed
Author URI: http://venturedive.com/
*/

define( 'WPCROPTOOL__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPCROPTOOL__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

add_action( 'init', array( 'WPCropTool', 'init' ) );

add_action( 'wp_ajax_save_and_resize_image', array( 'WPCropTool', 'save_and_resize_image' ) );

class WPCropTool {

	private static $initialized = false;

	const MIN_WIDTH = 1200;
	const MIN_HEIGHT = 300;


	public static function init() {
		if( !self::$initialized ) {
			self::init_hooks();
		}
	}

	public static function init_hooks() {
		self::$initialized = true;

		add_action( 'admin_enqueue_scripts', array( 'WPCropTool', 'enqueue_scripts_styles' ) );

		add_action( 'add_meta_boxes', array( 'WPCropTool', 'add_meta_boxes' ) );

		add_action( 'in_admin_footer', array( 'WPCropTool', 'post_image_upload_modal' ) );
	}

	public static function enqueue_scripts_styles() {
		// Enqueue Required Scripts
		wp_enqueue_script( 'jcrop-2', 'http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'foundation', WPCROPTOOL__PLUGIN_URL . 'vendor/foundation.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'foundation.reveal', WPCROPTOOL__PLUGIN_URL . 'vendor/foundation.reveal.js', array( 'jquery', 'foundation' ), false, true );
		wp_enqueue_script( 'wp-crop-tool-script', WPCROPTOOL__PLUGIN_URL . 'script.js', array( 'jquery', 'jcrop-2', 'foundation' ), false, true );

		wp_localize_script( 'wp-crop-tool-script', 'backend_object', 
			array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ), 
				)
			);

		// Enqueue Required Styles
		wp_enqueue_style( 'jcrop-2', 'http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.min.css' );
		wp_enqueue_style( 'foundation', WPCROPTOOL__PLUGIN_URL . 'vendor/foundation.css' );
		wp_enqueue_style( 'wp-crop-tool-style', WPCROPTOOL__PLUGIN_URL . 'style.css' );
	}

	public static function add_meta_boxes() {
		add_meta_box( 'image', __( 'Post Header Image', 'wp-crop-tool' ), array( 'WPCropTool', 'image_meta_box' ), 'post', 'normal', 'high' );
	}

	public static function image_meta_box() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), 'mp_nonce' );
		$post_meta = get_post_meta( $post->ID );
		?>
		<p>
			<div>
				<img style="<?php if ( !isset ( $post_meta['image'] ) ) echo "display: none;"; ?>" class="page-thumbnail-preview" id="post-page-thumbnail" src="<?php if ( isset ( $post_meta['image'] ) ) echo $post_meta['image'][0]; ?>" width="400px" height="auto">
			</div>
		    <input type="hidden" name="image-attachment-id" id="image-attachment-id" value="<?php if ( isset ( $post_meta['image-attachment-id'] ) ) echo $post_meta['image-attachment-id'][0]; ?>" />
            <input type="button" id="upload-image-button" class="button" data-target="image" data-width="<?= self::MIN_WIDTH; ?>" data-height="<?= self::MIN_HEIGHT; ?>" value="<?php _e( 'Choose or Upload an Image', 'wp-crop-tool' )?>" />
		</p>
		<?php
	}

	public static function post_image_upload_modal() {
	    ?>
	    <div id="post-image-upload-modal" class="reveal-modal" data-reveal>
	        <h1 data-modal-heading><?= __( 'Image Upload &amp; Cropping Tool', 'wp-crop-tool' ); ?></h1>

	        <div class="spinner" id="post-image-upload-spinner"></div>
	        <p id="uploaded-image" style="overflow: hidden; max-width: 700px; max-height: 450px;">
	            <img>
	        </p>

	        <form action="<?php echo plugins_url( 'ImageUpload.php', __FILE__ ); ?>" id="post-image-upload-form">
	            <label><?= __( 'Upload an image ', 'wp-crop-tool' ); ?><span class="red-color"><?= __(sprintf('(Minimum %dx%d)', self::MIN_WIDTH, self::MIN_HEIGHT), 'wp-crop-tool'); ?></span></label>
	            <input type="button" size="20" id="add-image-button" class="button" value="<?= __( 'Add Image', 'wp-crop-tool' ); ?>" data-target="image" data-width="<?= self::MIN_WIDTH; ?>" data-height="<?= self::MIN_HEIGHT; ?>" >
	        </form>

	        <div class="crop-image-action element-hidden">
	        	<button class="crop-image"><?= __( 'Crop Image', 'wp-crop-tool' ); ?></button><span class="spinner"></span>
	        	<button class="switch-selection"><?= __( 'Switch Selection', 'wp-crop-tool' ); ?></button>
	        </div>

	        <a class="close-reveal-modal">&#215;</a>
	    </div>
	    <?php
	}

	public static function save_and_resize_image() {
		$result = '';
		try {
			$attachment = new Attachment( $_POST['crop_data'] );
			$result = $attachment->process();

		} catch(Exception $e) {
			wp_send_json_error($e);
		}

		wp_send_json_success($result);
	}

}

class Attachment {

	private $original_attachment_id;
	private $url;
	private $mime_type;
	private $post_id;
	private $path;
	private $images;

	public function __construct( $data ) {
		$this->original_attachment_id = $data['attachmentId'];
		$this->url = $data['url'];
		$this->mime_type = $data['mimeType'];
		$this->post_id = $data['postId'];

		$this->images = ImageDetail::fromArray( $data['images'] );
	}

	public function process() {
		$attachment_meta = wp_get_attachment_metadata( $this->original_attachment_id );

		$this->path = self::create_path($attachment_meta['file']);

		$this->crop_images();

		$result = $this->update_post_meta();

		return $result;
	}

	private function update_post_meta() {
		$result = array();
		if(isset($this->images['header'])) {
			$header_image = $this->images['header'];
			
			update_post_meta($this->post_id, 'image-attachment-id', $header_image->get_attachment_id());
			update_post_meta($this->post_id, 'image', $header_image->get_image_url());

			$result['header'] = $header_image->get_image_url();
			$result['header_attachment_id'] = $header_image->get_attachment_id();
		}

		if(isset($this->images['preview'])) {
			$preview_image = $this->images['preview'];

			set_post_thumbnail($this->post_id, $preview_image->get_attachment_id());

			$result['preview'] = $preview_image->get_image_url();
			$result['preview_attachment_id'] = $preview_image->get_attachment_id();
		}

		return $result;
	}

	private function crop_images() {
		foreach ($this->images as $image) {
			$cropped_image = $image->crop_image( $this->path );

			$image->set_attachment_id($this->generate_and_save_attachment($cropped_image));
		}
	}

	private function generate_and_save_attachment($cropped_image) {
		$wp_upload_dir = wp_upload_dir();

		$attachment = array(
			'guid'           => trailingslashit($wp_upload_dir['url']) . basename( $cropped_image['path'] ),
			'post_mime_type' => $this->mime_type,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $cropped_image['path'] ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

        $attach_id = wp_insert_attachment( $attachment, $cropped_image['path'], $this->post_id );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $cropped_image['path'] );

        foreach ($cropped_image['scaled_sizes'] as $scaled_size_label => $scaled_size) {
        	$attach_data['sizes'][$scaled_size_label] = $scaled_size;
        }

		wp_update_attachment_metadata( $attach_id,  $attach_data );

		return $attach_id;
	}

	private static function create_path($file_dir) {
		$uploads_dir = wp_upload_dir();

		return trailingslashit($uploads_dir['basedir']) . $file_dir;
	}

}

class ImageDetail {

	private $label;
	private $coordinates;
	private $scaled_sizes;

	private $width;
	private $height;

	private $attachment_id;

	public function __construct( $label, $width, $height, $coordinates, $scaled_sizes ) {
		$this->label = $label;

		$this->width = $width;
		$this->height = $height;

		$this->coordinates = $coordinates;
		$this->scaled_sizes = $scaled_sizes;

		CUtils::log($this);
	}

	public static function fromArray( $dataArray ) {
		$images = array();
		foreach ($dataArray as $value) {
			$images[$value['label']] = new ImageDetail( 
				$value['label'],
				$value['width'],
				$value['height'],
				$value['coordinates'], 
				isset($value['scaled_sizes']) ? $value['scaled_sizes'] : array() 
			);
		}
		return $images;
	}

	public function __toString() {
		return $this->label;
	} 

	public function crop_image( $path ) {
		$image_editor = $this->get_image_editor( $path );
		$image_editor->crop(
			$this->coordinates['x'],
			$this->coordinates['y'],
			$this->coordinates['w'],
			$this->coordinates['h'],
			$this->width,
			$this->height
		);

		$image_data = $image_editor->save();

		if($this->scaled_sizes) {
			$image_data['scaled_sizes'] = $this->add_label($image_editor->multi_resize($this->get_scaled_dimensions()));
		} else {
			$image_data['scaled_sizes'] = array();
		}

		return $image_data;
	}

	public function set_attachment_id($attachment_id) {
		$this->attachment_id = $attachment_id;
	}
	public function get_attachment_id() {
		return $this->attachment_id;
	}

	public function get_image_url() {
		return wp_get_attachment_url($this->attachment_id);
	}

	private function add_label($resized_images) {
		$result = array();

		foreach ($resized_images as $resized_image) {
			$label = '';

			foreach ($this->scaled_sizes as $scaled_size) {

				if($resized_image['width'] == $scaled_size['width'] && $resized_image['height'] == $scaled_size['height']) {
					$label = $scaled_size['label'];
				}
			}	

			$result[$label] = $resized_image;
		}

		return $result;
	}

	private function get_scaled_dimensions() {
		foreach ($this->scaled_sizes as $scaled_size) {
			$this->scaled_size['crop'] = true;
		}

		return $this->scaled_sizes;
	}

	private function get_image_editor( $path ) {
		$image_editor = wp_get_image_editor( $path );

		if( is_wp_error( $image_editor ) ) {
			throw new Exception( $image_editor->get_error_message() );
		}

		return $image_editor;
	}

}

class CUtils {
	public static function log( $var ) {
		error_log( print_r( $var, true) );
	}
}