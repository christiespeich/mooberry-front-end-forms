<?php

class MFEF_Post_Form extends MFEF_Form {
	
	protected $wp_post_fields;
	protected $post_type;
	
	public function __construct ( $id, $options = null, $capability = 'edit_posts' ) {
		parent::__construct( $id, $options, $capability);
		
		$defaults = array(
				'post_id'	=>	'0',
				'post_type'	=>	'post',
				);
		$options = array_merge( $defaults, $options );
		
		$this->item_id = $options['post_id'];
		$this->wp_post_fields = array();
		$this->values = array();
		$this->post_type = $options['post_type'];
		
		$this->wp_post_fields = array_flip(array_keys( get_class_vars( 'WP_Post' ) ));
		
		$this->fields[] = MFEF_Field_Factory::create_field( array(
											'id'	=>	'ID',
											'type'	=>	'hidden',
											'value'	=>	$this->item_id,
											) 
										);
		//$this->values = get_option( $this->options_key, array() );
		if ( $this->item_id != '0' ) {
			$post = get_post(  $this->item_id, ARRAY_A ); //array( ''fields' => 'all_with_meta' ) );
			
			
			
			$meta = get_post_meta( $this->item_id );
			$data = array();
			
			foreach ( $post as $key=>$value ) {
				$this->values[$key] = $value;
			//	$this->wp_post_fields[ $key] = $value;
			}
			foreach ( $meta as $key => $value ) {
				$this->values[ $key ] = $value[0];
			}
			
		}
	}
	
	protected function save_fields( $fields ) {
		
			$post_id = $fields['ID'];
			// post_id = 0 means add new
			$post_args = array();
			foreach ( array_keys($this->wp_post_fields) as $key ) {
				if ( array_key_exists( $key, $fields ) ) {
					$post_args[ $key ] = $fields[ $key ];
				}
			}
			// grab just the meta fields
			$meta_fields = array_diff_key( $fields, $this->wp_post_fields );
			$meta_args = array();
			foreach ( $meta_fields as $key => $value ) {
				$meta_args[ $key ] = $fields[ $key ];
			}
			$post_args['meta_input'] =  $meta_args;
			$post_args['post_type']	=	$this->post_type;
			$post_args['post_status']	= 'publish';
			
			$new_post_id = wp_insert_post( $post_args, true );
			if ( is_wp_error($new_post_id) ) {
				// TO DO: Error handing
				print_r($new_post_id);
				return false;
			} else {
				return true;
			}
			
		
	}
	
	
	
}
