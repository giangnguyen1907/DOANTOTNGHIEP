<script type="text/javascript">
	$(document).ready(function(){

		/*
		$('#ps_app_permission_app_permission_code input:radio').click(function() {
			$('#ps_app_permission_title').attr('value', $("#"+this.id).next("label").text());	
		});*/

		$('#ps_app_permission_app_permission_code').click(function() {
			$('#ps_app_permission_title').attr('value', $("#ps_app_permission_app_permission_code option:selected").text());	
		});
		
});
</script>
