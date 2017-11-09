<?php
abstract class MFEF_Single_Field extends MFEF_Field {
	
	protected $value;
	protected $required;
	protected $default;
	protected $validation_callback;
	protected $empty_value;

	public function __construct( $field_options ) {
		
		parent::__construct( $field_options );
		
		$defaults = array(
			'type'	=>	'text',
			'required'	=>	false,
			'default'	=>	'',
			'value'		=>	'',
			'validation_callback'	=>	'',
			'empty_value'	=>	'',
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		$this->type = $field_options['type'];
		$this->required = $field_options['required'];
		$this->default	= $field_options['default'];
		if ( $this->load_callback == '' ) {
			$this->value = $field_options['value'];
		} else {
			$this->value = call_user_func( $this->load_callback, $this );
		}
		$this->validation_callback = $field_options['validation_callback'];
		$this->empty_value = $field_options['empty_value'];
		
		if ( ( $this->value == '' || $this->value == null ) && ( $this->default != '' && $this->default != null ) ) {
			$this->value = $this->default;
		}
		
		if ( $this->required ) {
			$this->classes[] = 'required';
		}
		
	}
	
	public function validate() {
		// if not required, always return true
		// if required, return true if it's not blank
		if ( is_array( $this->value ) ) {
			$valid = count($this->value) > 0;
		} else {
			$valid =  ( !$this->required || trim( $this->value ) != $this->empty_value );
		}
		if ( !$valid ) {
			$this->classes[] = 'missing';
			return new WP_Error( 'missing-field', $this->label . ' is required.', $this->label);
		}
		
		if ( $this->validation_callback != '' ) {
			if ( is_callable( $this->validation_callback ) ) {
				$result = call_user_func( $this->validation_callback, $this );
				if ( is_wp_error( $result ) ) {
					return $result;
				}
			}
		}
		return true;
	}
		
	public function is_blank( $value = null ) {
		if ( $value == null ) {
			$value = $this->value;
		}
		
		return ( trim($value) == '' );
	}
	
	
}
