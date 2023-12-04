<?php include_partial('global/field_custom/_ps_assets') ?>
<?php include_partial('global/include/_box_modal') ?>
<script type="text/javascript">
    $('#remoteModal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
</script>