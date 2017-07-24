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
		
	}
	
	protected function render_field() {
		?>
		<select id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>">
		<?php
		foreach ( $this->options as $key => $value ) {
			if ( $key == $this->value ) {
				$selected = ' selected ';
			} else {
				$selected = '';
			}
			?>
			<option value="<?php echo esc_attr($key); ?>"<?php echo $selected; ?>><?php echo esc_html($value); ?></option>
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
	
	
}
