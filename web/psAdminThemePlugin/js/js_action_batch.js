function openModalBox(url) {

	var $modal = $('#ajax-modal');

	// create the backdrop and wait for next modal to be triggered
	$('body').modalmanager('loading');

	setTimeout(function() {

		$modal.load(url, '', function() {

			// remove autofocus when we edit item
			if (url.search('/edit') > 0) {
				$('#ajax-modal .autofocus').removeClass('autofocus');
			}

			$modal.modal();
			$("#ajax-modal").draggable({
				handle : ".modal-header,.modal-footer"
			});
		});
	}, 1);

}

// Check all list action batch
function checkAll() {
	var boxes = document.getElementsByTagName('input');
	for (var index = 0; index < boxes.length; index++) {
		box = boxes[index];
		if (box.type == 'checkbox'
				&& box.className == 'sf_admin_batch_checkbox')
			box.checked = document
					.getElementById('sf_admin_list_batch_checkbox').checked
	}
	return true;
}

// Get number sf_admin_batch_checkbox in list table
function getNumberBatchCheckbox() {

	var boxes = document.getElementsByTagName('input');
	var number_batch_checkbox = 0;

	for (var index = 0; index < boxes.length; index++) {
		box = boxes[index];

		if (box.type == 'checkbox'
				&& box.className == 'sf_admin_batch_checkbox')
			number_batch_checkbox++;
	}

	return number_batch_checkbox;
}

function setCheckObj(id) {
	$('#chk_id_' + id).attr('checked', true);
	return true;
}

function setOptionFeature(id) {
	$('#of_chk_id_' + id).attr('checked', true);
	return true;
}

// Check order
function setCheck(obj, id) {
	if (!validateNumber(obj)) {
		obj.value = 0;
		return false;
	}

	$('#chk_id_' + id).attr('checked', true);
	return true;
}

// Validate input number
function validateNumber(obj) {
	var number = obj.value;

	if (number != '' && !isNumeric(number)) {
		alert('Giá trị này phải là kiểu số và >= 0');
		// document.getElementById(obj.id).focus();
		return false;
	}
	return true;
}

function isNumeric(n) {
	var n = n.toString().replace(/\$|\,/g, '');

	var n2 = n;
	n = parseFloat(n);
	return (n != 'NaN' && n2 == n);
}

function resetOptions(select_id) {
	var selectBox = document.getElementById(select_id);

	for (var i = selectBox.length - 1; i >= 0; --i) {
		if (selectBox[i].value > 0) {
			selectBox.remove(i);
		}
	}

	$('#' + select_id).select2('val', '');
}

function keyNumber(e) {

	if (onlyNumber(e) == 8 || onlyNumber(e) == 45 || onlyNumber(e) == 46)
		return true;
	if ((onlyNumber(e) < 48 || onlyNumber(e) > 57))
		return false;
}

function onlyNumber(e) {
	var keynum;

	if (window.event) // IE
	{
		keynum = e.keyCode
	} else if (e.which) // Netscape/Firefox/Opera
	{
		keynum = e.which
	}
	return keynum;
}

function downloadfile(url_file, save_file_name) {
	var a = document.createElement("a");
	a.href = url;
	a.download = save_file_name;
	document.body.appendChild(a);
	a.click();
	window.URL.revokeObjectURL(url);
	a.remove();
	return;
}

function download_album(filename_zip, list_photo_id) {

	var zip = new JSZip();
	var count = 0;

	var files = [];
	$('#' + list_photo_id).find('img.photo').each(function() {
		var url = $(this).attr('src');
		var paths = url.split('/');
		var filenames = paths[paths.length - 1].split('?');
		var filename = filenames[0];
		files.push({
			filename : filename,
			url : url
		});
	});

	files.forEach(function(file) {
		var filename = file.filename;

		// loading a file and add it in a zip file
		JSZipUtils.getBinaryContent(file.url, function(err, data) {
			if (err) {
				throw err; // or handle the error
			}
			zip.file(filename, data, {
				binary : true
			});
			count++;
			if (count == files.length) {
				zip.generateAsync({
					type : 'blob'
				}).then(function(content) {
					saveAs(content, zip_filename);
					hideLoading();
				});
			}
		});
	});
}
