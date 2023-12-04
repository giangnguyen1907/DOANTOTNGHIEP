$(document)
		.ready(
				function() {
					// cleanup the content of the hidden remote modal because it
					// is cached
					$('#remoteModal').on('hide.bs.modal', function(e) {
						$(this).removeData('bs.modal');
						//$('#ps-form-teacher-class').formValidation('resetForm', true);
					});
					
					$('#confirmDeleteModal').on('hide.bs.modal', function(e) {
						$(this).removeData('bs.modal');
					});
					
					$('#confirmDeleteClass').on('hide.bs.modal', function(e) {
						$(this).removeData('bs.modal');
					});
					
					/* BASIC ;*/
					var responsiveHelper_dt_basic_student = undefined;
					var responsiveHelper_datatable_fixed_column = undefined;
					var responsiveHelper_datatable_col_reorder = undefined;
					var responsiveHelper_datatable_tabletools = undefined;
						
					var breakpointDefinition = {
						tablet : 1024,
						phone : 480
					};	
					
					$('table.display').dataTable({
						"language": {
						    "emptyTable":"",
						    "zeroRecords":""
						 },
						"paging": false,
						"searching": false,
						"info": false,
						"ordering": true,
						"sDom": '',
						"autoWidth" : true,
						"rowReorder": true,
				        "aoColumnDefs": [
				            { 'bSortable': false, 'aTargets': ['no-order'] }
				        ]
					});
					/* END BASIC */
					
					$('#ps-form').formValidation({
						framework : 'bootstrap',
						excluded : [':disabled'],
						addOns : {
							i18n : {}
						},
						errorElement : "div",
						errorClass : "help-block with-errors",
						message : {
							vi_VN : 'This value is not valid'
						},
						icon : {},
						fields : {
							
						}
					});
					
					$('#ps-form').formValidation('setLocale', PS_CULTURE);
					
				});
