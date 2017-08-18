<?php

class MFEF_Option_Form extends MFEF_Form {
	
	protected $options_key;
	
	public function __construct ( $id, $options = null, $capability = 'manage_options' ) {
		
		parent::__construct( $id, $options, $capability );
		
		
		if ( array_key_exists( 'key', $options ) ) {
			$this->options_key = $options[ 'key' ];
		} else {
			return null;
		}
		$this->values = get_option( $this->options_key, array() );
	}
	
	protected function save_fields( $fields ) {
			return update_option( $this->options_key, $fields );	
	}
	
	
	
}