<?php 
class MFEF_Radio_Field extends MFEF_Single_Field {

	protected $options;
	protected $inline;
	
	public function __construct( $field_options ) {
		parent::__construct( $field_options );
		
		$defaults = array(
			'options'	=>	array(),
            'inline'    =>  false,
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		$this->options = $field_options['options'];
		$this->inline = $field_options[ 'inline' ];
	}
	
	protected function render_field() {
		//$required = ( $this->required ) ? ' required="required" ' : '';
        if ( $this->inline ) {
	        $inline_class = ' mfef-show-inline ';
        } else {
	        $inline_class = '';
        }
        $checked = '';
		?><ul class="<?php echo $inline_class; ?>"><?php
		foreach ( $this->options as $key => $value ) {
			$checked = ( $key == $this->value ) ? ' checked="checked" ' : '';
		?>
		<li class="<?php echo $inline_class; ?>">
			<input type="radio" value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($this->id); ?>" class="<?php echo implode(' ', $this->classes); ?> mfef-checkbox-option mfef-checkbox-option-<?php echo esc_attr($key); ?>" <?php echo  $checked; ?> />
			<label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
		</li>
		<?php
		}
		?></ul><?php
	}
	
	public function sanitize( $value ) {
		$value = sanitize_text_field( $value );
		return in_array( $value, $this->options ) ?  $value : '';
	}
}

