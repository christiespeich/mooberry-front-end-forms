<?php
/*
Plugin Name: Mooberry Front-End Forms
Plugin URI: https://www.mooberrydreams.com/front-end-forms
Description: A framework for developers to add forms on the front end. Can save to Options, User Meta, or Post Types
Author: Mooberry Dreams
Version: 1.0
Author URI: http://www.moooberrydreams.com
License: GPL2
TextDomain: mooberry-front-end-forms
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
 //error_log('starting');
// Plugin version
if ( ! defined( 'MFEF_PLUGIN_VERSION' ) ) {
	define( 'MFEF_PLUGIN_VERSION', '1.0' );
}

if ( ! defined( 'MFEF_PLUGIN_VERSION_KEY' ) ) {
	define('MFEF_PLUGIN_VERSION_KEY', 'mfef_version');
}

// Plugin Folder Path
if ( ! defined( 'MFEF_PLUGIN_DIR' ) ) {
	define( 'MFEF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL
if ( ! defined( 'MFEF_PLUGIN_URL' ) ) {
	define( 'MFEF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File
if ( ! defined( 'MFEF_PLUGIN_FILE' ) ) {
	define( 'MFEF_PLUGIN_FILE', __FILE__ );
} 

require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-form-factory.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-form.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-user-form.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-option-form.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-post-form.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-custom-form.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-field-factory.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-single-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-text-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-wysiwyg-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-select-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-checkbox-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-textarea-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-radio-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-repeater-field.php';
require_once MFEF_PLUGIN_DIR . '/includes/class-mfef-hidden-field.php';

add_action( 'wp_enqueue_scripts', 'mfef_enqueue_scripts' );
add_action( 'login_enqueue_scripts', 'mfef_enqueue_scripts');
function mfef_enqueue_scripts() {
	wp_enqueue_script( 'jquery-repeater', MFEF_PLUGIN_URL . '/js/jquery.repeater/jquery.repeater.js', array( 'jquery' ), MFEF_PLUGIN_VERSION,true );
	wp_enqueue_script( 'repeater', MFEF_PLUGIN_URL . '/js/repeater.js', array( 'jquery' ),MFEF_PLUGIN_VERSION,true );
	
	wp_enqueue_style( 'mfef', MFEF_PLUGIN_URL . '/css/style.css' );
}



function mfef_get_unique_id() {

	return uniqid('', true);
		
}

