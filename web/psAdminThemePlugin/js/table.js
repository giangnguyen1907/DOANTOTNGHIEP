$(document)
		.ready(
				function() {

					$('#remoteModal').on('hide.bs.modal', function(e) {
						$(this).removeData('bs.modal');
					});

					$('.btn-filter-search').click(function() {
						$('#ps-filter').submit();
						return true;
					});

					if ($("#dt_basic").length) {
						/* BASIC ; */
						var responsiveHelper_dt_basic = undefined;
						var responsiveHelper_datatable_fixed_column = undefined;
						var responsiveHelper_datatable_col_reorder = undefined;
						var responsiveHelper_datatable_tabletools = undefined;

						var breakpointDefinition = {
							tablet : 1024,
							phone : 480
						};

						$('#dt_basic')
							.dataTable(
									{
										"language" : {
											"emptyTable" : "",
											"zeroRecords" : ""
										},
										"paging" : false,
										"searching" : false,
										"info" : false,
										"ordering" : false,
										"sDom" : '',
										"autoWidth" : true,
										"preDrawCallback" : function() {
											// Initialize the responsive
											// datatables helper once.
											if (!responsiveHelper_dt_basic) {
												responsiveHelper_dt_basic = new ResponsiveDatatablesHelper(
														$('#dt_basic'),
														breakpointDefinition);
											}
										},
										"rowCallback" : function(nRow) {
											responsiveHelper_dt_basic
													.createExpandIcon(nRow);
										},
										"drawCallback" : function(oSettings) {
											responsiveHelper_dt_basic.respond();
										}
									});
					/* END BASIC */
					}

					$('#sf_admin_list_batch_checkbox').click(function() {

						var boxes = document.getElementsByTagName('input');

						for (var index = 0; index < boxes.length; index++) {
							box = boxes[index];
							if (box.type == 'checkbox' && box.name == 'ids[]')
								box.checked = $(this).is(":checked");
						}

						return true;
					});
				});