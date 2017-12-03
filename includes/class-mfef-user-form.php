<?php

class MFEF_User_Form extends MFEF_Form {
	
	protected $wp_user_fields;

	public function __construct ( $id, $options = null, $capability = 'edit_users' ) {
		
		// item_id must be set before parent__contruct();
		$defaults = array(
				'user_id'	=>	'0',
				'allow_save_as_draft'   =>  false,
		);

		$options = array_merge( $defaults, $options );
		
		$this->item_id = $options['user_id'];

		// if editing self, then capability = read
		if ( $this->item_id == get_current_user_id() ) {
			$capability = 'read';
		}
		
		$access = $this->edit_permission_check( $this->item_id );

		if ( !$access ) {
			echo
					'<h3>' . __( 'Access Denied' ) . '</h3>' .
					'<p>' . __( 'Sorry, you do not have access to edit this user.', 'mooberry-front-end-forms' ) . '</p>';
			return;
		}

		parent::__construct( $id, $options, $capability);
		
		$this->wp_user_fields = array();
		$this->values = array();
		$this->fields[] = MFEF_Field_Factory::create_field( array(
											'id'	=>	'ID',
											'type'	=>	'hidden',
											'value'	=>	$this->item_id,
											) 
										);
		//$this->values = get_option( $this->options_key, array() );
		if ( $this->item_id != '0' ) {
			$user = get_user_by( 'ID', $this->item_id ); //array( ''fields' => 'all_with_meta' ) );
			
			//foreach ( $users as $user ) {
				 $meta = get_user_meta( $user->ID );
				// $family = ( array_key_exists( 'family', $meta ) ) ? $meta['family'] : '';
				// $first_name = ( array_key_exists( 'first_name', $meta ) ) ? $meta['first_name'] : '';
				// $last_name = ( array_key_exists( 'last_name', $meta ) ) ? $meta['last_name'] : '';
				$data = array();
				// foreach ( $user as $key => $value ) {
					// $this->values[ $key ] = $value;
				// }
				foreach ( $user->data as $key=>$value ) {
					$this->values[$key] = $value;
					$this->wp_user_fields[ $key] = $value;
				}
				foreach ( $user as $key=>$value ) {
					$this->values[$key] = $value;
					$this->wp_user_fields[ $key] = $value;
				}
				foreach ( $meta as $key => $value ) {
					if ( count($value) == 1 ) {
						$this->values[ $key ] = $value[0];
					} else {
						$this->values[ $key ] = $value;
					}
				}
				//print_r($data);
				
				//$this->values[] = array_merge( $this->values, $meta);
				/*$this->values[] = array( 'id'	=> $user->ID,
										'email'	=>	$user->data->user_email,
										'first_name'	=>	$first_name,
										'last_name'		=>	$last_name,
										'family'		=>	$family,
									);
							*/		
	//		}
		}
		
	}
	
	protected function save_fields( $fields ) {
		
			$this->item_id = $fields['ID'];
			$access = $this->edit_permission_check( $this->item_id );
			if ( !$access ) {
				echo
						'<h3>' . __( 'Access Denied' ) . '</h3>' .
						'<p>' . __( 'Sorry, you do not have access to edit this user.', 'mooberry-front-end-forms' ) . '</p>';
				return false;
			}
				
			if (  $this->item_id == '0' ) {
				// remove user ID so it adds the user
				unset($fields['ID']);
				
				$errors = register_new_user($fields['user_email'], $fields['user_email']);
				if ( !is_wp_error($errors) ) {
					$this->item_id = $errors;
				} else {
					return false;
				}
			} else {
				//wp_insert_user qruies user_login
				$fields['user_login'] = $this->values['user_login'];
				// wp_insert_user will update if ID is included, or add otherwise
				$errors = wp_insert_user( $fields );
				if ( is_wp_error( $errors )  ) {
					return false;
				}

			}
			// roles need to be handled differently
			$user = get_user_by('ID', $this->item_id);
			if ( array_key_exists( 'roles', $fields ) ) {
				if ( !is_array( $fields['roles'] ) ) {
					$fields['roles'] = array($fields['roles']);
				}
				// remove roles that aren't selected
				foreach ( $user->roles as $role ) {
					if ( !in_array($role, $fields['roles'] ) ) {
						$user->remove_role( $role);
					}
				}
				// add the new ones
				foreach ( $fields['roles'] as $role ) {
					if ( !in_array( $role, $user->roles) ) {
						$user->add_role( $role );
					}
				}
					
			}
			
			// grab just the meta fields
			$meta_fields = array_diff_key( $fields, $this->wp_user_fields );
			
			foreach ( $meta_fields as $key => $value ) {
				if ( $key != 'ID' ) {
					update_user_meta( $this->item_id, $key, $value );
					// so check and make sure the stored value matches $new_value
					if ( get_user_meta($this->item_id,  $key, true ) != $value ) {
						return false;
					}
				}
			}
			
			return $this->item_id;
		
	}

	protected function check_capability() {
		if ( $this->item_id == 0 ) {
			return current_user_can( 'create_users' );
		}

		if ( $this->capability != null && $this->item_id != 0 ) {
			return current_user_can( $this->capability, $this->item_id );
		} else {
			return true;
		}
	}


	protected function edit_permission_check( $profileuser_id) {
		// global $current_user, $profileuser;
	  
		
	   
		$current_user = wp_get_current_user();
	   // get_currentuserinfo();


		if( ! is_super_admin( $current_user->ID )  ) { // editing a user profile
			if ( is_super_admin( $profileuser_id ) ) { // trying to edit a superadmin while less than a superadmin
				return false;
			} elseif ( ! ( is_user_member_of_blog( $profileuser_id, get_current_blog_id() ) && is_user_member_of_blog( $current_user->ID, get_current_blog_id() ) )) { // editing user and edited user aren't members of the same blog
				return false;
			}
		}
		return true;
	}

	protected function delete() {
		if ( current_user_can( 'delete_users' ) ) {

			wp_delete_user( $this->item_id );
		}
	}
	
}
