<?php
class MFEF_WYSIWYG_Field extends MFEF_Single_Field {
	
	public function __construct( $field_options ) {
		parent::__construct( $field_options );
		
		$this->classes[] = 'mfef-field-wysiwyg';
	}
	
	protected function render_field() {
		$required = ( $this->required ) ? ' required="required" ' : '';
		echo wp_editor( $this->value, $this->id );
	?>	
		<!-- <input type="text" id="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value); ?>" name="<?php echo esc_attr($this->id);?>" class="<?php echo implode( ' ', $this->classes ); ?>" <?php echo  $required; ?> />  -->
	<?php
	}
	
	public function sanitize( $value ) {
		$this->value = wp_kses_post( $value );
		return $this->value;
	}
	
}
