<?php

abstract class MFEF_Form {
	
	//protected $options_key;
	//protected $action;
	protected $fields;
	protected $class;
	protected $values;
	protected $id;
	protected $redirect_after_save;
	protected $capability;
	protected $item_id;
	protected $nonce_field;
	protected $nonce_value;
	protected $button_name;
	protected $button_value;
	protected $button_text;
	protected $button_classes;
	protected $save_callback;
	protected $redirect_after_cancel;
	protected $allow_delete;
		
		
	abstract protected function save_fields( $fields );
	abstract protected function delete();
	
	 public function __construct( $id, $options = null, $capability = null) {
		 
		 $this->capability = $capability;

		 $access = $this->check_capability();
		 if ( !$access ) {
			 
			 echo
					'<h3>' . __( 'Access Denied' ) . '</h3>' .
					'<p>' . __( 'Sorry, you do not have access.', 'mooberry-front-end-forms' ) . '</p>';
			return;
		 }
		 
		$defaults = array(
			'redirect_after_save'	=>	'',
			'redirect_after_cancel'	=>	'',
			'nonce_field'	=>	$id,
			'nonce_value'	=>	$id . '-front-end',
			'button_name'	=> 'mfef_btn_save',
			'button_value'	=>	'save',
			'button_text'	=>	__('Save', 'mooberry-front-end-forms'),
			'button_classes'	=>	array(),
			'save_callback'	=>	'',
            'allow_delete'  =>  false,
		);
		
		$options = array_merge( $defaults, $options );
		
		$this->id = $id;
		$this->nonce_field = $options['nonce_field'];
		$this->nonce_value = $options['nonce_value'];
		$this->button_name = $options['button_name'];
		$this->button_value = $options['button_value'];
		$this->button_text	= $options['button_text'];
		$this->button_classes = $options['button_classes'];
		$this->save_callback = $options['save_callback'];
		$this->allow_delete = $options['allow_delete'];
		
		$this->redirect_after_save = $options['redirect_after_save'];
		if ( $options['redirect_after_cancel'] == '' ) {
			$this->redirect_after_cancel = $this->redirect_after_save;
		} else {
			$this->redirect_after_cancel = $options['redirect_after_cancel'];
		}
		
	} 
	
	protected function check_capability() {

		if ( $this->capability != null ) {
			return current_user_can( $this->capability, $this->item_id );
		} else {
			return true;
		}
	}
		
	
	public function add_field( $field_options ) {
		$access = $this->check_capability();
		 if ( $access ) {
			if ( array_key_exists( $field_options['id'], $this->values ) ) {
				$field_options['value']  = $this->values[ $field_options['id'] ];
			}
			$new_field = MFEF_Field_Factory::create_field( $field_options );
			
			if ( $new_field != null ) {
				$this->fields[] = $new_field;
			}		
		 }
	}
	
	public function render() {
		$access = $this->check_capability();
		 if ( $access ) {
			 
			 // ob_start();
			  
			 
			// $content = ob_get_contents();
			// ob_end_clean();

			// return $content;
			$this->process_form();
			
			if ( $this->contains_repeater() ) {
				$this->class .= ' repeater ';
			}
		
			?>
			<form id="<?php echo esc_attr($this->id); ?>" class="<?php echo esc_attr($this->class); ?> mfef-form mfef-form-<?php echo esc_attr($this->id); ?>" action="" method="post"  enctype="multipart/form-data">
				<?php
				foreach ( $this->fields as $field ) {
					/* $value = null;
					if ( array_key_exists( $field->id, $this->values ) ) {
						$value = $this->values[ $field->id ];
					} */
					$field->render(  );
				}
			
				 wp_nonce_field( $this->nonce_field , $this->nonce_value);
				do_action( 'mfef_before_save_button');
					?>
				<div id="mfef_button_block">
                    <button type="submit" value="<?php echo esc_attr($this->button_value); ?>" name="<?php echo esc_attr($this->button_name); ?>" class="btn btn-primary btn-large button-next mfef-btn-save <?php echo implode(' ', $this->button_classes); ?>"><?php echo esc_html($this->button_text); ?></button>
                    <?php
                        do_action( 'mfef_after_save_button');
                    ?>
                    <button type="submit" value="cancel" id="mfef_btn_cancel" name="mfef_btn_cancel" class="btn btn-primary btn-large button-next mfef-btn-cancel <?php echo implode(' ', $this->button_classes); ?>"><?php echo esc_html(__('Cancel', 'mooberry-front-end-forms') ); ?></button>
                    <button type="submit" value="delete" id="mfef_btn_delete" name="mfef_btn_cancel" class="btn btn-danger btn-large button-next mfef-btn-delete <?php echo implode(' ', $this->button_classes); ?>"><?php echo esc_html(__('Delete', 'mooberry-front-end-forms') ); ?></button>
                </div>
			</form>
			<?php
		 }
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
		
		$access = $this->check_capability();
		 if ( $access ) {
			 
			if (isset($_POST[$this->nonce_value]) ) {
				if (wp_verify_nonce($_POST[ $this->nonce_value], $this->nonce_field)){
					if ( isset($_POST['mfef_btn_cancel']) ) {
					    wp_safe_redirect( $this->redirect_after_cancel );
					    exit;
					}
					if ( isset($_POST['mfef_btn_delete']) ) {
						$this->delete();
						return;
					}
				    // save form
					$clean_fields = array();
					$message = array();
					foreach ( $this->fields as $field ) {
						
						if ( array_key_exists( $field->id, $_POST ) ) {
							// sanitize the fields 
							
							$clean_fields[ $field->id ] = $field->sanitize( $_POST[ $field->id ] );
							
							// validate the fields
							$result = $field->validate();
							
							if ( is_wp_error($result) )  {
								//$field->classes[] = 'missing';
								
									$message[] = $result->get_error_message();
								
							}
						
						}
					}
					
					if ( count($message) == 0 ) {
						// save the form
						$success = $this->save( $clean_fields );
						//$options = $this->sanitize_fields( $this->fields, $_POST );
						
						
						// set values so that form can be populated with values
					//	$this->values = $clean_fields;
						
					} else {
						echo '<p>' . implode('</p><p>', $message) . '</p>';
					}
				} else {
					echo 'Update Failed!';
				}
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
	
	
	protected function save( $fields ) {
		if ( $this->check_capability() ) {
			do_action('mfef_pre_save', $this, $fields );
			$success = $this->save_fields( $fields );
			if ( $success !== false ) {
				$this->item_id  = $success;
			}
			do_action('mfef_post_save_pre_callback', $this, $fields );
			if ( $this->save_callback != ''  ) {
				if ( is_callable( $this->save_callback) ) {
					call_user_func ( $this->save_callback,  $this, $fields  );
				}
			}
			do_action('mfef_post_save_post_callback', $this, $fields );
			if ( $success &&  $this->redirect_after_save != '' ) {
				do_action('mfef_post_save_pre_redirect', $this, $fields);
				wp_safe_redirect( $this->redirect_after_save );
				exit();
			}
		}
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
