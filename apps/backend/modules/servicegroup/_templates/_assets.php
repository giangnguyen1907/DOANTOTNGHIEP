<?php include_partial('global/ps_assets');?>
<style type="text/css">
	.sf_admin_list_td_iorder,.sf_admin_list_th_iorder {width: 100px;text-align:center !important;}
	#sf_admin_list_th_actions,.sf_admin_td_actions {text-align:center !important;width: 150px;}
	
</style>
<script type="text/javascript">
	function setCheck(obj,id) {
		
		if (!validateNumber(obj)) {
			obj.value = 0;
			return false;
		}
		
		$('#chk_id_' + id).attr('checked', true);
		return true;
	}
</script>
