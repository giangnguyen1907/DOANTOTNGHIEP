<?php include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">
$('#remoteModal').on('hide.bs.modal', function(e) {
	$(this).removeData('bs.modal');
});

$('.time_picker').timepicker({
	timeFormat : 'HH:mm',
	showMeridian : false,
	defaultTime : null
});
</script>