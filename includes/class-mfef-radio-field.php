<?php 
class MFEF_Radio_Field extends MFEF_Single_Field {

	protected $options;
	
	public function __construct( $field_options ) {
		parent::__construct( $field_options );
		
		$defaults = array(
			'options'	=>	array(),
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		$this->options = $field_options['options'];
		
	}
	
	protected function render_field() {
	}
	
	public function sanitize( $value ) {
		return $this->value;
	}
}

