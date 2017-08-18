<?php

class MFEF_Custom_Form extends MFEF_Form {
	
	protected $save_callback;
	protected $load_callback;

	
	
	public function __construct ( $id, $options = null, $capability = null ) {
		
		parent::__construct( $id, $options, $capability );
		
		$defaults = array( 'save_callback'	=>	'',
							'load_callback'	=>	'',
							'item_id'	=>	'',
						);
						
		$options = array_merge( $defaults, $options );
		
		$this->load_callback = $options['load_callback'];
		$this->save_callback = $options['save_callback'];
		$this->item_id = $options['item_id'];
		
		
		
		if ( $this->load_callback != '' && function_exists( $this->load_callback ) ) {
			$this->values = call_user_func( $this->load_callback, $this->item_id );
		} else {
			$this->values = array();
		}
	}
	
	protected function save_fields( $fields ) {
		if ( $this->save_callback != '' && function_exists( $this->save_callback ) ) {
			return call_user_func( $this->save_callback, $fields );
		}
	}
	
	
	
}