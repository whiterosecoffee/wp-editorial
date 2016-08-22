<?php 

if( class_exists( 'MPPlugin' ) ) 
	return;

class MPPlugin {

	private $plugin_name;
	private $version;
	
	function __construct($name, $version) {
		$this->plugin_name = $name;
		$this->version = $version;
		$this->validate();
	}

	protected function validate() {
		$version = get_option( $this->plugin_name );
		// If version is not set - install it.
		if( !$version ) {
			$this->install();
		} else if( $version != $this->version ) {
			$this->upgrade();
			error_log( $version );
		}
	}

	protected function install() {
		update_option( $this->plugin_name, $this->version );
	}

	protected function upgrade() {
		update_option( $this->plugin_name, $this->version );
	}

	protected function uninstall() {
			
	}

	public static function log( $var ) {
		error_log( print_r( $var, true ) );
	}

	/**
	 * @param $method_name
	 * @return callable An array-wrapped PHP callable suitable for calling class methods when working with
	 * WordPress add_action/add_filter.
	 */
	public function marshal( $method_name ) {
		return array( &$this , $method_name );
	}

}