<?php
class MFEF_Repeater_Field extends MFEF_Field {
	
	protected $fields;
	protected $values;
	
	public function __construct( $field_options ) {
		$defaults = array(
			'id'	=>	'',
			'label'	=>	'',
			'type'	=>	'repeater',
			'fields'	=>	array(),
			'value'	=> array(),
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		if ( $field_options['id'] != '' ) {
			$this->id = $field_options['id'];
		} else {
			return null;
		}
		$this->label = $field_options['label'];
		$this->type = $field_options['type'];
		$this->values = $field_options['value'];
		
		if ( !is_array( $field_options['fields'] ) ) {
			$field_options['fields'] = array( $field_options['fields'] );
		}
		
		//$this->fields = $field_options['fields'];
		
		foreach ( $field_options['fields'] as $field ) { 
			
			$this->fields[] = MFEF_Field_Factory::create_field( $field );
		}
		
		
	}
	
	protected function render_field() {
			?>
	  <div data-repeater-list="<?php echo esc_attr($this->id); ?>">
	  <?php 
	  if ( !is_array($this->values) ) {
		  $this->values = array($this->values);
	  }
	  foreach ( $this->values as $row ) {
		  if ( !is_array( $row ) ) {
			  $row = array( $row );
		  }
		  echo '<div data-repeater-item>';
		  foreach ( $this->fields as $field ) { 		  
				$value = null;				
				if ( array_key_exists( $field->id, $row ) ) {
					$field->value = $row[ $field->id ];
				}
				
				$field->render( );
		  }
				?>
				<input data-repeater-delete type="button" value="Delete"/>
			  </div>
		  <?php 
	}
	  
	  ?>
		</div>
		<input data-repeater-create type="button" value="Add"/>
		<?php
	}
	
	public function sanitize( $values ) {
	
		$sanitized_values = array();
		foreach ( $values as $row ) {
			$sanitized_fields = array();
			foreach ( $this->fields as $field ) {
				if ( array_key_exists( $field->id, $row ) ) {
					$sanitized_fields[$field->id] = $field->sanitize( $row[ $field->id ]);
				}
			}
			$sanitized_values[] = $sanitized_fields;
		}			
	
		return $sanitized_values;			
	}
	
}
