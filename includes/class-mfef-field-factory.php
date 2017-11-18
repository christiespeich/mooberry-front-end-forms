<?php

class MFEF_Field_Factory {
	
	public static function create_field( $options ) {
		
		if ( array_key_exists( 'type', $options ) ) {
			$type = $options['type'];
		} else {
			$type = 'text';
		}
		
		$field = null;
	
		switch ( $type ) {
			case 'select':
				$field = new MFEF_Select_Field( $options );
				break;
			case 'textarea':
				$field = new MFEF_Textarea_Field( $options );
				break;
			case 'radio':
				$field = new MFEF_Radio_Field( $options );
				break;
			case 'repeater':
				$field = new MFEF_Repeater_Field( $options );
				break;
			case 'text':
				$field = new MFEF_Text_Field( $options );
				break;
			case 'hidden':
				$field = new MFEF_Hidden_Field( $options );
				break;
			case 'checkbox':
				$field = new MFEF_Checkbox_Field( $options );
				break;
			case 'wysiwyg':
				$field = new MFEF_WYSIWYG_Field( $options );
				break;
			case 'taxonomy':
				$field = new MFEF_Taxonomy_Checkbox_Field( $options );
				break;
			case 'featured-thumbnail':
				$field = new MFEF_Featured_Thumbnail_Field( $options );
				break;
			
		}
		return $field;
	}

}
