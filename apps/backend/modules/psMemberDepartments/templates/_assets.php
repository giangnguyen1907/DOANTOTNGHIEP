<?php include_partial('global/field_custom/_ps_assets') ?>
<style>
#ps-filter .has-error {
	/* To make the feedback icon visible */
	z-index: 9999;
	color: #b94a48;
}

.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.select2-container {
	width: 100% !important;
	padding: 0;
}
</style>
<script type="text/javascript">
    $('#remoteModal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
</script>