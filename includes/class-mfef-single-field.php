<?php
abstract class MFEF_Single_Field extends MFEF_Field {
	
	protected $value;

	public function __construct( $field_options ) {
		
		$defaults = array(
			'id'	=>	'',
			'label'	=>	'',
			'type'	=>	'text',
			'required'	=>	false,
			'default'	=>	'',
			'value'		=>	'',
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		if ( $field_options['id'] != '' ) {
			$this->id = $field_options['id'];
		} else {
			return null;
		}
		
		$this->label = $field_options['label'];
		$this->type = $field_options['type'];
		
		$this->required = $field_options['required'];
		$this->default	= $field_options['default'];
		$this->value = $field_options['value'];
		
		
		
	}
	
	
}
