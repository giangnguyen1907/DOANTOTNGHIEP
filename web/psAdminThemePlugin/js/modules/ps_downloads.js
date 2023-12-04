function getExt(filename) {
	var ext = /^.+\.([^.]+)$/.exec(filename);
	return ext == null ? "" : ext[1];
}

function downloadfile(url_file) {
	var file_name = url_file.split('/').pop().split('#')[0].split('?')[0];
	var ext = getExt(file_name);
	// alert(ext);
	var new_file = 'abc.' + ext;

	return;
}
$(document).ready(function() {

	$('.save-img').click(function() {
		var data_url = $(this).attr('data-url');
		downloadfile(data_url);
	});

});