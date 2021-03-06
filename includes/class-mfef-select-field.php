<?php

class MFEF_Select_Field extends MFEF_Single_Field {
	
	protected $options;
	
	
	public function __construct( $field_options ) {
		parent::__construct( $field_options );
		
		$defaults = array(
			'options'	=>	array(),
		
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		$this->options = $field_options['options'];
		
		
		$this->classes[] = 'mfef-field-select';
	}
	
	protected function render_field() {
		$required = ( $this->required ) ? ' required="required" ' : '';
		?>
		<select id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="<?php echo implode(' ', $this->classes); ?>" <?php echo $required; ?>>
		<?php
		foreach ( $this->options as $key => $value ) {
			$selected = ( $key == $this->value ) ? ' selected="selected" ' : '';
			
			?>
			<option value="<?php echo esc_attr($key); ?>" class="mfef-select-option mfef-select-option-<?php echo esc_attr($this->id); ?>" <?php echo $selected; ?>><?php echo esc_html($value); ?></option>
			<?php
		}
		?>
		</select>
		<?php
	}
	
	public function sanitize( $value ) {
		$this->value = $value;
		return $this->value;
	}
	
	
	
	public function is_blank( $value = null ) {
		if ( $value == null ) {
			$value = $this->value;
		}
		
		return ( $value == $this->empty_value );
	}
	
	
}
