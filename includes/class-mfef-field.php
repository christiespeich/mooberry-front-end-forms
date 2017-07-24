<?php
abstract class MFEF_Field {
	
	protected $label;
	protected $id;
	protected $type;
	/* protected $options;
	protected $required;
	protected $default;
	protected $fields;
	protected $value;
	 */
	 
	
	abstract protected function render_field();
	abstract public function sanitize( $value );
	
	public function render(  ) {
		/* if ( $value == null ) {
			$this->value = $this->default;
		} else {
			$this->value = $value;
		 }
		*/
		?>
		<label for="<?php echo esc_attr($this->id); ?>"><?php echo esc_attr($this->label); ?></label>
		<?php
		
		$this->render_field();	
			
	}
	
	
	
	protected function render_select() {
		
	}
	
	protected function render_textarea() {
		
		
		
	}
	
	protected function render_radio() {
	}
	
	protected function render_text() {
		
	}

	protected function render_repeater() {
	
		
	}

	/**
	 * Magic __get function to dispatch a call to retrieve a private property
	 *
	 * @since 1.0
	 */
	public function __get( $key ) {

		if( method_exists( $this, 'get_' . $key ) ) {

			return call_user_func( array( $this, 'get_' . $key ) );

		} else {
			
			if ( property_exists( $this, $key ) ) {
				
				$ungettable_properties = array(  );
				
				if ( !in_array( $key, $ungettable_properties ) ) {
				
					return $this->$key;

				}
		
			}
		
		}
	
		return new WP_Error( 'ls-invalid-property', sprintf( __( 'Can\'t get property %s', 'leader-sidekick' ), $key ) );
				
	}
	
	/**
	 * Magic __set function to dispatch a call to retrieve a private property
	 *
	 * @since 1.0
	 */
	public function __set( $key, $value ) {

		if( method_exists( $this, 'set_' . $key ) ) {

			return call_user_func( array( $this, 'set_' . $key ), $value );

		} else {

			if ( property_exists( $this, $key ) ) {
				
				$unsettable_properties = array( );
				
				if ( !in_array( $key, $unsettable_properties ) ) {
				
					$this->$key = $value;

				}
				
			}
		}
	
		return new WP_Error( 'ls-invalid-property', sprintf( __( 'Can\'t get property %s', 'leader-sidekick' ), $key ) );
		
	}
	
}

