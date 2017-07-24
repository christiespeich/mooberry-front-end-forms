<?php
class MFEF_Text_Field extends MFEF_Single_Field {
	
	protected function render_field() {
		echo '<input type="text" id="' . esc_attr($this->id) . '" value="' . esc_attr($this->value) . '" name="' . esc_attr($this->id) . '"/>';
	}
	
	public function sanitize( $value ) {
		$this->value = sanitize_text_field( $value );
		return $this->value;
	}
	
}
