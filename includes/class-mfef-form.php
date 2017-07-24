<?php

abstract class MFEF_Form {
	
	//protected $options_key;
	//protected $action;
	protected $fields;
	protected $class;
	protected $values;
	protected $id;
	
	
	/* public function __construct( $options_key, $action ) {
		$this->options_key = $options_key;
		$this->action = $action;
		$this->fields = array();
		$this->class = '';
		$this->values = get_option( $options_key, array() );
		
		
	} */
	
	abstract protected function save( $fields );
	
	public function add_field( $field_options ) {
		
		if ( array_key_exists( $field_options['id'], $this->values ) ) {
			$field_options['value']  = $this->values[ $field_options['id'] ];
		}
		$new_field = MFEF_Field_Factory::create_field( $field_options );
		
		if ( $new_field != null ) {
			$this->fields[] = $new_field;
		}		
	}
	
	public function render() {
		 // ob_start();
		  
		 
		// $content = ob_get_contents();
        // ob_end_clean();

        // return $content;
		$this->process_form();
		
		if ( $this->contains_repeater() ) {
			$this->class .= ' repeater ';
		}
	
		?>
		<form id="<?php echo esc_attr($this->id); ?>" class="<?php echo esc_attr($this->class); ?>" action="" method="post">
			<?php
			foreach ( $this->fields as $field ) {
				/* $value = null;
				if ( array_key_exists( $field->id, $this->values ) ) {
					$value = $this->values[ $field->id ];
				} */
				$field->render(  );
			}
		
			 wp_nonce_field( $this->options_key , $this->options_key . '-front-end'); 
				?>
			
			<input type="submit" value="Save">	
		</form>
		<?php
	}
	
	protected function contains_repeater() {
		foreach ( $this->fields as $field ) {
			if ( $field->type == 'repeater'  ) {
				return true;
			} 
		}
		return false;
	}
	
	protected function process_form() {
		if (isset($_POST[$this->options_key . '-front-end']) ) {
			if (wp_verify_nonce($_POST[ $this->options_key . '-front-end'], $this->options_key)){ 
				// save form
				
				// sanitize the fields 
				$clean_fields = array();
				foreach ( $this->fields as $field ) {
					if ( array_key_exists( $field->id, $_POST ) ) {
						$clean_fields[ $field->id ] = $field->sanitize( $_POST[ $field->id ] );
					}
				}
				
				// save the form
				$this->save( $clean_fields );
				//$options = $this->sanitize_fields( $this->fields, $_POST );
				
				
				// set values so that form can be populated with values
				$this->values = $clean_fields;
				echo 'Saved!';
			} else {
				echo 'Update Failed!';
			}
		}
	}
	
	
	
	private function sanitize_fields( $fields, $post_data ) {
		$sanitized_fields = array();
		
		foreach ( $fields as $field ) {
			
			if ( array_key_exists( $field->id, $post_data ) ) {
				switch ( $field->type ) {
						case 'text':
							$sanitized_fields[ $field->id ] = sanitize_text_field($post_data[ $field->id ]);
							break;
						case 'textarea':
							$sanitized_fields[ $field->id ] = sanitize_textarea_field( $post_data[ $field->id ]);
							break;
						case 'repeater':
							foreach ( $post_data[ $field->id ] as $row ) {
								$sanitized_fields[$field->id][]   = $this->sanitize_fields( $field->fields, $row ) ;
							}
							break;
						default:
							$sanitized_fields[ $field->id ] = $post_data[ $field->id ];

				}
			}
		}
		
		return $sanitized_fields;
	}
	
	
	/**
	 * Magic __get function to dispatch a call to retrieve a private property
	 *
	 * @since 1.0
	 */
	public function __get( $key ) {

		if( method_exists( $this, 'get_' . $key ) ) {

			return call_user_func( array( $this, 'get_' . $key ) );

		} else {
			
			if ( property_exists( $this, $key ) ) {
				
				$ungettable_properties = array(  );
				
				if ( !in_array( $key, $ungettable_properties ) ) {
				
					return $this->$key;

				}
		
			}
		
		}
	
		return new WP_Error( 'ls-invalid-property', sprintf( __( 'Can\'t get property %s', 'leader-sidekick' ), $key ) );
				
	}
	
	/**
	 * Magic __set function to dispatch a call to retrieve a private property
	 *
	 * @since 1.0
	 */
	public function __set( $key, $value ) {

		if( method_exists( $this, 'set_' . $key ) ) {

			return call_user_func( array( $this, 'set_' . $key ), $value );

		} else {

			if ( property_exists( $this, $key ) ) {
				
				$unsettable_properties = array( );
				
				if ( !in_array( $key, $unsettable_properties ) ) {
				
					$this->$key = $value;

				}
				
			}
		}
	
		return new WP_Error( 'ls-invalid-property', sprintf( __( 'Can\'t get property %s', 'leader-sidekick' ), $key ) );
		
	}
}
