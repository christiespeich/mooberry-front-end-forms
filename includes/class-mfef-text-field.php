<?php
class MFEF_Text_Field extends MFEF_Single_Field {
	
	public function __construct( $field_options ) {
		parent::__construct( $field_options );
		
		$this->classes[] = 'mfef-field-text';
	}
	
	protected function render_field() {
		$required = ( $this->required ) ? ' required="required" ' : '';
	?>	
		<input type="text" id="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value); ?>" name="<?php echo esc_attr($this->id);?>" class="<?php echo implode( ' ', $this->classes ); ?>" <?php echo  $required; ?> />
	<?php
	}
	
	public function sanitize( $value ) {
		$this->value = sanitize_text_field( $value );
		return $this->value;
	}
	
}
