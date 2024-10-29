var mediaFrmField = "";
function media_upload(fieldId) {

	mediaFrmField = jQuery('#'+fieldId).attr('id');
	var send_attachment_bkp = wp.media.editor.send.attachment;

    wp.media.editor.send.attachment = function(props, attachment) {
        jQuery('#'+mediaFrmField).val(attachment.url);
        wp.media.editor.send.attachment = send_attachment_bkp;
    }
    wp.media.editor.open();
	return false;
}