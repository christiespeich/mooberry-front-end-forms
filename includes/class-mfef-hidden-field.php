<?php
class MFEF_Hidden_Field extends MFEF_Single_Field {
	
	public function __construct( $field_options ) {
		parent::__construct( $field_options );
		
		$this->classes[] = 'mfef-field-hidden';
	}
	
	protected function render_field() {
?>
		<input type="hidden" id="<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="<?php echo $this->value; ?>">
<?php
	}
	
	public function sanitize( $value ) {
		$this->value = sanitize_text_field( $value );
		return $this->value;
	}
	
	

}