<?php
class MFEF_Password_Field extends MFEF_Text_Field {
	
	
	
	protected function render_field() {
		$required = ( $this->required ) ? ' required="required" ' : '';
	?>	
		<input type="password" id="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value); ?>" name="<?php echo esc_attr($this->id);?>" class="<?php echo implode( ' ', $this->classes ); ?>" <?php echo  $required; ?> />
	<?php
	}
	
	
}
