var mediaFrmField = "";
function media_upload(fieldId) {

	mediaFrmField = jQuery('#'+fieldId).attr('id');
	tb_show('Upload a book cover', 'media-upload.php?referer=author_progress_bar&type=image&TB_iframe=true&post_id=0', false);
	return false;
}

jQuery(document).ready(function() {
	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#'+mediaFrmField).val(imgurl);
		tb_remove();
	}
});
