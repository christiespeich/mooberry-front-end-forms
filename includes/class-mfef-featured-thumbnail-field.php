<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 11/12/2017
 * Time: 10:02 AM
 */

class MFEF_Featured_Thumbnail_Field extends MFEF_Single_Field {

	public function __construct( $field_options ) {

		$field_options['id'] = '_thumbnail_id';

	    parent::__construct( $field_options );


		$this->classes = $this->classes +  array('mfef-field-file', 'mfef-field-featured-thumbnail');
	}

	protected function render_field() {
		$required = ( $this->required ) ? ' required="required" ' : '';
		echo '<div id="mfef_thumbnail_preview">';
		if ( $this->value != '' ) {
			echo wp_get_attachment_image( $this->value );
			?>
			<span class="glyphicon glyphicon-remove-sign" aria-hidden="true" data-toggle="tooltip" title="<?php echo esc_attr(__('Delete', 'leader-sidekick') ); ?>"></span>
            <?php
		}
		?>


        </div>
      <!--   <input type="button" id="mfef_delete_thumbnail" value="Delete Photo"></input> -->
        <input type="hidden" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr( $this->value); ?>" />
		<input type="file" id="<?php echo esc_attr($this->id . '_file'); ?>"  name="<?php echo esc_attr($this->id . '_file');?>" class="<?php echo implode( ' ', $this->classes ); ?>" <?php echo  $required; ?> />
		<?php
	}

	public function sanitize( $value ) {
		return $value;
	}



}