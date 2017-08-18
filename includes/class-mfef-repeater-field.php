<?php
class MFEF_Repeater_Field extends MFEF_Field {
	
	protected $fields;
	protected $values;
	protected $add_text;
	protected $delete_text;
	
	public function __construct( $field_options ) {
		
		parent::__construct( $field_options );
		
		$defaults = array(
			'type'		=>	'repeater',
			'fields'	=>	array(),
			'value'		=> array(),
			'add_text'	=>	__('Add', 'mooberry-front-end-forms'),
			'delete_text'	=>	__('Delete', 'mooberry-front-end-forms'),
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		$this->type = $field_options['type'];
		$this->values = $field_options['value'];
		
		if ( !is_array( $field_options['fields'] ) ) {
			$field_options['fields'] = array( $field_options['fields'] );
		}
		$this->fields[] = MFEF_Field_Factory::create_field( array(
											'id'	=>	'unique_id',
											'type'	=>	'hidden',
											
											) 
										);
											
		foreach ( $field_options['fields'] as $field ) { 			
			$this->fields[] = MFEF_Field_Factory::create_field( $field );
		}
		
		$this->classes[] = 'mfef-field-repeater';
		$this->add_text = $field_options[ 'add_text' ];
		$this->delete_text = $field_options[ 'delete_text' ];
		
		
	}
	
	protected function render_field() {
			?>
	  <div data-repeater-list="<?php echo esc_attr($this->id); ?>" class="<?php echo implode(' ', $this->classes); ?>">
	  <?php 
	  
	  if ( !is_array($this->values) ) {
		  $this->values = array($this->values);
	  }
	   if ( count($this->values) == 0 ) {
		  foreach ( $this->fields as $field ) {
				$this->values[0][ $field->id ] = '';
				
		  }
	  } 
	  $counter = 1;
	  foreach ( $this->values as $row ) {
		  if ( !is_array( $row ) ) {
			  $row = array( $row );
		  }
		  
		  echo '<div data-repeater-item class="repeater-item"> ';
		  $counter++;
		  
		  foreach ( $this->fields as $field ) { 		  
				$value = null;				
				if ( array_key_exists( $field->id, $row ) ) {
					$field->value = $row[ $field->id ];
				}
				
				$field->render( );
		  }
				?>
				<input data-repeater-delete type="button" class="repeater-delete-button repeater-delete-button-<?php echo esc_attr($this->id); ?>"/>
				
				
			  </div>
		  <?php 
	}
	  
	  ?>
		</div>
		<input data-repeater-create type="button" value="<?php echo esc_attr($this->add_text); ?>" class="btn btn-primary btn-sm repeater-add-button repeater-add-button-<?php echo esc_attr($this->id); ?>"/>
		<?php
	}
	
	public function sanitize( $values ) {
	
		$sanitized_values = array();
		foreach ( $values as $row ) {
			$sanitized_fields = array();
			foreach ( $this->fields as $field ) {
				
				if ( array_key_exists( $field->id, $row ) ) {
					// if it's the uniqueID, generate one
					if ( $field->id == 'unique_id' && $row[ $field->id ] == '') {
						
						$row[$field->id] = mfef_get_unique_id();
						
					}
					$sanitized_fields[$field->id] = $field->sanitize( $row[ $field->id ]);
				}
			}
			$sanitized_values[] = $sanitized_fields;
		}			
	
	$this->values = $sanitized_values;
		return $sanitized_values;			
	}
	
	// return array of messages
	public function validate() {
		$message = array();
		foreach ( $this->fields as $key => $field ) {
			
			if ( !$field->validate() ) {
				$this->fields[ $key ]->classes[] = 'missing';
				$message[] = $field->label. ' is required.';
			}
		}
		if ( count($message) > 0 ) {
			return $message;
		} else {
			return true;
		}
	}
}
