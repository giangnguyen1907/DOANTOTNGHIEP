<?php use_helper('I18N', 'Number')?>
<?php include_partial('global/include/_box_modal')?>

<script type="text/javascript">
	$(document).on("ready", function(){
		$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_title a").on("contextmenu",function(){
	    return false;
		});		
	});
</script>