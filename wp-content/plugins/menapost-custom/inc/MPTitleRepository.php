<?php

if( class_exists( 'MPTitleRepository' ) ) {
	return;
}
require_once( dirname(__FILE__) . '/MPPlugin.php' );

class MPTitleRepository extends MPPlugin {
	const MP_TITLE_REPOSITORY_VERSION = 0.1;
	const MP_TITLE_COUNT = 21;

	public function __construct() {
		parent::__construct( 'MPTitleRepository', self::MP_TITLE_REPOSITORY_VERSION );

		add_action( 'admin_init', array(  &$this, 'register_meta_box' ) );

		add_action( 'save_post', array(  &$this, 'save_meta_box_data' ) );
	}

	public function register_meta_box() {
		add_meta_box( 
			'title_repository_meta_box', 
			__( 'Titles', 'menapost-custom' ), 
			array(  &$this, 'meta_box_callback' ), 
			'post', 
			'normal', 
			'default'
		);
	}

	public function init_title_repository() {
		$titles_repository = array();

		for ($i=0; $i < self::MP_TITLE_COUNT; $i++) { 
			$titles_repository[] = new Title($i);	
		}

		return $titles_repository;
	}

	public function get_title_repository( $post_id ) {
		$titles_repository = get_post_meta( $post_id, 'titles_repository', true );

		if($titles_repository === "") {
			$titles_repository = $this->init_title_repository();
		}

		return $titles_repository;
	}

	public function meta_box_callback( $post ) {
		wp_nonce_field( 'title_meta_box', 'titles_meta_box_nonce' );

		$titles_repository = $this->get_title_repository( $post->ID );
?>
<style>
	#title-repository-table tr {
		text-align: center;
	}
</style>

<table id="title-repository-table" data-max-title-selection="0">
<thead>
	<tr>
		<th></th>
		<th></th>
		<th>Manager</th>
		<th>Admin</th>
	</tr>
</thead>
<?php
		foreach ($titles_repository as $titles) {
			if($titles instanceof Title) {
				echo $titles->get_html();
			}
		}
?>
</table>
<?php
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

	function save_meta_box_data( $post_id ) {
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['titles_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['titles_meta_box_nonce'], 'title_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		// Make sure that it is set.
		if ( ! isset( $_POST['titles_repository'] ) ) {
			return;
		}

		$titles_repository = $_POST['titles_repository'];

		$titles_repository = $this->marshal_title_repository($titles_repository, $post_id);

		update_post_meta( $post_id, 'titles_repository', $titles_repository );
	}

	function marshal_title_repository( $title_repository_raw, $post_id ) {
		$title_repository = $this->get_title_repository( $post_id );
		$user_role = $this->get_current_user_role();

		for ($i=0; $i < self::MP_TITLE_COUNT; $i++) { 
			$title = $title_repository[$i];
			$title_raw = $title_repository_raw[$i];

			$title->update_attributes($title_raw, $user_role);

			$title_repository[$i] = $title;
		}

		return $title_repository;
	}

}


class Title {
	private $id;
	private $text;
	private $manager_selected;
	private $admin_selected;

	public function __construct($id) {
		$this->id = $id;

		$this->text = "";
		$this->manager_selected = false;
		$this->admin_selected = false;
	}

	public function get_html() {
?>

<tr <?= ($this->id == 20)? 'class="admin-only"':''; ?>>
	<input type="hidden" name="titles_repository[<?= $this->id; ?>][id]" value="<?= $this->id; ?>"/>
	<td><?= sprintf( __( 'Title %d', 'menapost-custom' ), $this->id + 1 ); ?></td>
	<td>
		<input type="text" style="width:250px;" name="titles_repository[<?= $this->id; ?>][text]" value="<?= $this->text; ?>" />
	</td>
	<td>
		<input type="checkbox" class="manager-checkbox" name="titles_repository[<?= $this->id; ?>][manager_selected]" <?= $this->manager_selected ? "checked" : ""; ?>/>
	</td>
	<td>
		<input type="checkbox" class="admin-checkbox" name="titles_repository[<?= $this->id; ?>][admin_selected]" <?= $this->admin_selected ? "checked" : ""; ?>/>
	</td>
</tr>

<?php
	}

	public function update_attributes( $attr_array, $user_role ) {
		$this->text = $attr_array['text'];

		switch( $user_role ) {
			case 'administrator':
				$this->admin_selected = isset($attr_array['admin_selected']);
				break;
			case 'editor':
				$this->manager_selected = isset($attr_array['manager_selected']);
				break;
			default:
				break;
		}
	}

}


