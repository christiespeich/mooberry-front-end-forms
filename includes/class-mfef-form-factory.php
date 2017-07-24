<?php
class MFEF_Form_Factory {
	
	public static function create_form( $id, $type, $options = array() ) {
		
		$form = null;
		
		switch ( $type ) {
			case 'user':
				$form = new MFEF_User_Form( $id, $options );
				break;
			case 'post':
				$form = new MFEF_Post_Form( $id, $options );
				break;
			case 'option':
				$form = new MFEF_Option_Form( $id, $options );
				break;
		}
		
		return $form;
	}

}
