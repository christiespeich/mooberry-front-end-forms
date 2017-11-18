jQuery( window ).load(function() {
jQuery('#_thumbnail_id_file').bind('change', mfef_delete_thumbnail);
jQuery('#mfef_thumbnail_preview span').bind('click', mfef_delete_thumbnail);
jQuery('[data-toggle="tooltip"]').tooltip();

//$('.troop_select').bind( 'click', select_troop);
});

function mfef_delete_thumbnail() {
    jQuery('#_thumbnail_id').val('');
    jQuery('#mfef_thumbnail_preview').empty();
}

