$(document).ready(function() {
	
	CKEDITOR.replace( 'ps_albums[note]', { height: '250px', startupFocus : true,	
				
		toolbar: [
			{ name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] },	

			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
					
			['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
					
			'/',
					
			{ name: 'insert', items: [ 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'] },
					
			{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
					
			{ name: 'links', items: [ 'Link', 'Unlink'] }

	]} );

	
    $('#ps_albums_form').formValidation('setLocale', PS_CULTURE);

});