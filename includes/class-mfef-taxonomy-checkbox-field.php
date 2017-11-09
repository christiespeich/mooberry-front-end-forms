<?php

class MFEF_Taxonomy_Checkbox_Field extends MFEF_Checkbox_Field {
	
	protected $taxonomy;
	
	
	public function __construct( $field_options ) {
		parent::__construct( $field_options );
		
		$defaults = array(
			'taxonomy'	=>	'category',
			'options'	=>	'',
		
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		$this->taxonomy = $field_options['taxonomy'];
		$terms = get_terms( array(
							'taxonomy'	=>	$this->taxonomy,
							'hide_empty' => false,
							)
						);
		foreach ( $terms as $term ) {
			$this->options[ $term->term_id ] = $term->name;
		}
		
		/* $this->value = maybe_unserialize($this->value);
		if ( !is_array($this->value ) ) {
			$this->value = array( $this->value );
		}
		
		$this->classes[] = 'mfef-field-checkbox'; */
	}
	
	/* protected function render_field() {
		
		$required = ( $this->required ) ? ' required="required" ' : '';
		?><ul><?php
		foreach ( $this->options as $key => $value ) {
			$checked = ( in_array($key, $this->value) ) ? ' checked="checked" ' : '';
		?>
		<li>
			<input type="checkbox" value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($this->id); ?>[]" class="<?php echo implode(' ', $this->classes); ?> mfef-checkbox-option mfef-checkbox-option-<?php echo esc_attr($key); ?>" <?php echo $required . $checked; ?> />
			<label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
		</li>
		<?php
		}
		?></ul><?php
	} */
	/* 
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
	 */
	
}
