<?php
abstract class MFEF_Field {
	
	protected $label;
	protected $id;
	protected $type;
	protected $classes;
	protected $description;
	protected $load_callback;
	/* protected $options;
	protected $required;
	protected $default;
	protected $fields;
	protected $value;
	 */
	 
	 public function __construct( $field_options ) {
			
		$defaults = array(
			'id'	=>	'',
			'label'	=>	'',
			'classes'	=>	array(),
			'desc'	=>	'',
			'load_callback'	=>	'',
		);
		
		$field_options = array_merge( $defaults, $field_options );
		
		if ( $field_options['id'] != '' ) {
			$this->id = $field_options['id'];
		} else {
			return null;
		}
		
		$this->label = $field_options['label'];
		$this->type = $field_options['type'];
		$this->classes = $field_options['classes'];
		$this->description = $field_options['desc'];
		$this->load_callback = $field_options['load_callback'];
		
		$this->classes[] = 'mfef-field';
		$this->classes[] = 'mfef-field-' . esc_attr($this->id);
		
	}
	 
	
	abstract protected function render_field();
	abstract public function sanitize( $value );
	abstract public function validate();
	
	public function render(  ) {
		/* if ( $value == null ) {
			$this->value = $this->default;
		} else {
			$this->value = $value;
		 }
		*/
		?>
		<div class="<?php echo implode(' ', $this->classes); ?>">
		<label for="<?php echo esc_attr($this->id); ?>" class="label-<?php echo esc_attr($this->id); ?>"><?php echo esc_attr($this->label); ?></label>
		<?php if ( $this->description != '' ) { ?>
			<div class="mfef-field-description mfef-field-<?php echo esc_attr($this->id); ?>-description"><?php echo $this->description; ?></div>
		<?php } ?>
		<?php
		
		$this->render_field();	
		
		?>
		</div>
		<?php
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

