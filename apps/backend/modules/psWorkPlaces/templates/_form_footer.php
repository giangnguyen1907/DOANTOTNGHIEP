<script type="text/javascript">
	$(document).ready(function() {    
	
		var activeTab = localStorage.getItem('activeTab');
	
		if (activeTab) {
			$('#myTab a[href="' + activeTab + '"]').tab('show');
	    }  
	
	});    
</script>