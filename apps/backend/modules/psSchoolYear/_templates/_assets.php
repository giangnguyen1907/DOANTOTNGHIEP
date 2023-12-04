<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/ps_assets');?>

<?php if (myUser::isAdministrator()) {?>
<script type="text/javascript">
	$('#service_ps_customer_id').focus();
	$(document).ready(function() {
		
		$('#btn_adddetail').click(function(e) {
			
			e.preventDefault();
		    $('#sf_fieldset_price_information_and_the_time_apply table#tb').append(addFieldDetail(newfieldscount, '<?php echo url_for('@ps_service_add_detail') ?>'));
		    newfieldscount = newfieldscount + 1;	    
		    /*$('#count').attr('value', parseInt($('#count').attr('value')) + 1);*/	    
		    $('.removenew').unbind('click');
		    
		    removeNew();   
	  	});
		
		$('#service_filters_ps_customer_id').change(function() {
	    	//$('#service_filters_service_group_id').hide();
	    	//$('#ajax-loader').show();
	    	$.ajax({
		        url: '<?php echo url_for('@ps_service_service_group?psc_id=') ?>' + $(this).val(),
		        type: 'POST',
		        data: 'psc_id=' + $(this).val(),
		        success: function(data) {
		            $('#service_filters_service_group_id').show();
		            //$('#ajax-loader').hide();
		            $('#service_filters_service_group_id').html(data);		            		            
		        }
		    });		 	
	    });
	    
	    $('#service_ps_customer_id').change(function() {
	    	//$('#service_filters_service_group_id').hide();
	    	//$('#ajax-loader').show();
	    	$.ajax({
		        url: '<?php echo url_for('@ps_service_service_group?psc_id=') ?>' + $(this).val(),
		        type: 'POST',
		        data: 'f=<?php echo md5(time())?>&psc_id=' + $(this).val(),
		        success: function(data) {
		            $('#service_service_group_id').html(data);		            		            
		        }
		    });		 	
		});
	    
	});
</script>
<?php }?>

<script type="text/javascript">
	function checkForm() {
		
		<?php if (myUser::isAdministrator()) {?>
		if ($('#service_ps_customer_id').val() <= 0) {
			alert('<?php echo __('Not school choice!')?>');
			$('#service_ps_customer_id').focus();
			return false;
		}
		<?php }?>
		
		if ($('#service_service_group_id').val() <= 0) {
			alert('<?php echo __('Not service group choice!')?>');
			$('#service_service_group_id').focus();
			return false;
		}
		
		if ($('#service_title').val().trim() <= 0) {
			alert('<?php echo __('Please enter a service name!')?>');
			$('#service_title').focus();
			return false;
		}
		
		if (!isNumeric($('#service_iorder').val())) {
			alert('<?php echo __('This value must be numeric, and > 0')?>');
			$('#service_iorder').focus();
			return false;
		}
		return true;		
	}
	
	$(document).ready(function(){
			
			$("#save").click(function(){
			 
			 if (!checkForm())
			 	return false;
			 
			 var check=$('.action input:checked');
			 
			 var count=$('#count').val();
			 
			 if(check.length >=1 &&  check.length < count)
			 {
				 if(confirm('<?php echo __('Are you sure to delete the selected items?')?>') == false)
				 return false;
			 } else if(check.length == count && count>0){
				 alert('<?php echo __('The system does not accept delete all items!')?>');
				 return false;
			 }
			 
			var row = 0; 	
			var detail_at= new Array(); 
			var detail_at_year = new Array();
			var detail_at_month = new Array();
			var detail_end= new Array();
			var detail_end_year = new Array();
			var detail_end_month = new Array();	
			var detail_amount = new Array();
			var detail_by_number = new Array();  
			
			newfieldscount = parseInt(newfieldscount);
			count = parseInt(count);
			var totalrow = newfieldscount + count;
			
			for(row_old = 0; row_old < count; row_old++){				
				detail_at_year[row_old] = $('#service_ServiceDetail_' + row_old + '_detail_at_year').val();				
				detail_at_month[row_old] = $('#service_ServiceDetail_' + row_old + '_detail_at_month').val();
				detail_end_year[row_old] = $('#service_ServiceDetail_' + row_old + '_detail_end_year').val();
				detail_end_month[row_old] = $('#service_ServiceDetail_' + row_old + '_detail_end_month').val();
				
				detail_amount[row_old] = $('#service_ServiceDetail_' + row_old + '_amount').val();
				detail_by_number[row_old] = $('#service_ServiceDetail_' + row_old + '_by_number').val();
				
				detail_at[row_old] = $('#service_ServiceDetail_' + row_old + '_detail_at_year').val() + $('#service_ServiceDetail_' + row_old + '_detail_at_month').val();
				detail_end[row_old] = $('#service_ServiceDetail_' + row_old + '_detail_end_year').val() + $('#service_ServiceDetail_' + row_old + '_detail_end_month').val();
			}			
			
			for(row_new = 0; row_new < newfieldscount; row_new++){				
				detail_at_year[(row_new + count)] = $('#service_new_' + row_new + '_detail_at_year').val();
				detail_at_month[(row_new + count)] = $('#service_new_' + row_new + '_detail_at_month').val();
				detail_end_year[(row_new + count)] = $('#service_new_' + row_new + '_detail_end_year').val();
				detail_end_month[(row_new + count)] = $('#service_new_' + row_new + '_detail_end_month').val();
				
				detail_amount[(row_new + count)] = $('#service_new_' + row_new + '_amount').val();
				detail_by_number[(row_new + count)] = $('#service_new_' + row_new + '_by_number').val();
				
				detail_at[(row_new + count)] = $('#service_new_' + row_new + '_detail_at_year').val() + $('#service_new_' + row_new + '_detail_at_month').val();
				detail_end[(row_new + count)] = $('#service_new_' + row_new + '_detail_end_year').val() + $('#service_new_' + row_new + '_detail_end_month').val();				
			}		
				
			for(row = 0; row < totalrow ; row++) {	
				
				//alert('row:' + row);
				
				var amount_flag = true;
				
				if(detail_amount[row] == null || detail_amount[row] == 0){					
					alert('<?php echo __('Entry Requirements price!')?>');
										
					amount_flag = false;										
				} else {
					
					var amount_value = (row < count) ? $('#service_ServiceDetail_' + row + '_amount').val() : $('#service_new_' + (row - count) + '_amount').val();
					
					if (!isNumeric(amount_value)) {
						alert('<?php echo __('This value must be numeric, and > 0')?>');
						amount_flag = false;
					}
				}
				
				if (!amount_flag) {
					
					if (row < count)
						$('#service_ServiceDetail_' + row + '_amount').focus();
					else
						$('#service_new_' + (row - count) + '_amount').focus();
					
					return false;	
				}
				
				
				var number_flag = true;
				
				if(detail_by_number[row] == null || detail_by_number[row] == 0){
					
					alert('<?php echo __('Entry Requirements number!')?>');					
					
					number_flag = false;
									
				} else {
					
					var by_number_value = (row < count) ? $('#service_ServiceDetail_' + row + '_by_number').val() : $('#service_new_' + (row - count) + '_by_number').val();
					
					if (!isNumeric(by_number_value)) {
						alert('<?php echo __('This value must be numeric, and > 0')?>');
						number_flag = false;						
					}
				}
				
				if (!number_flag) {
					if (row < count)
							$('#service_ServiceDetail_' + row + '_by_number').focus();
					else
							$('#service_new_' + (row - count) + '_by_number').focus();
					return false;	
				}
				
						
				if(detail_at_year[row] == null || detail_at_month[row] == null || detail_at_year[row] == 0 || detail_at_month[row] == 0){					
					alert('<?php echo __('Request select month / year started')?>');					
					if (row < count)
						$('#service_ServiceDetail_' + row + '_detail_at_month').focus();
					else
						$('#service_new_' + (row - count) + '_detail_at_month').focus();
					
					return false;
				}									
				
				if(row == (totalrow - 1) ){
					if(detail_end_year[row] == null || detail_end_month[row] == null || detail_end_year[row] == 0 || detail_end_month[row] == 0 || detail_end[row] <= detail_at[row]){
						if(confirm('<?php echo __('The end time must be greater than the start time! Do you want to reset it?')?>')){							
							if(row < count){								
								$('#service_ServiceDetail_' + row + '_detail_end_year').val(parseInt(detail_at_year[row]) + 1);
								$('#service_ServiceDetail_' + row + '_detail_end_month').val(parseInt(detail_at_month[row]));
							}else{
								$('#service_new_' + (row - count) + '_detail_end_year').val(parseInt(detail_at_year[row]) + 1);
								$('#service_new_' + (row - count)  + '_detail_end_month').val(parseInt(detail_at_month[row]));
							}	
							alert('<?php echo __('End time last month will be charged to ')?>' + month + ' <?php echo __('year')?> ' + (parseInt(detail_at_year[row])+10));							
						} else {
							return false;
						}	
					}
				} else {
					if(detail_end_year[row] == null || detail_end_month[row] == null || detail_end_year[row] == 0 || detail_end_month[row] == 0){					
						alert('<?php echo __('Request select month / year started')?>');					
						$('#service_new_' + row + '_detail_end_month').focus();
						return false;
					}
					
					if(detail_end[row] <= detail_at[row]){
							alert('<?php echo __('The end time must be greater than the start time!')?>');
							return false;						
					}
				}
				
				if(totalrow > 1) {
					if(row != (totalrow - 1)){
						if(detail_end_month[row] == 12){
							if( (parseInt(detail_at_year[(row + 1)]) != (parseInt(detail_end_year[row]) + 1)) && (parseInt(detail_at_month[(row + 1)]) != 1)){
								
								if(row < (count - 1)){
									$('#service_ServiceDetail_' + (row + 1) + '_detail_at_year').val(parseInt(detail_end_year[row]) + 1);
									$('#service_ServiceDetail_' + (row + 1) + '_detail_at_month').val(1);
									
								} else {
									$('#service_new_' + (row - count + 1) + '_detail_at_year').val(parseInt(detail_end_year[row]) + 1);
									$('#service_new_' + (row - count + 1) + '_detail_at_month').val(1);
								}
								
								alert('<?php echo __('Time to start next adjacent end time earlier')?>');
								
								return false;	
							}						
						} else {
							if( (parseInt(detail_at_year[(row + 1)]) != parseInt(detail_end_year[row])) || (parseInt(detail_at_month[(row + 1)]) != parseInt(detail_end_month[row]) + 1)) {
								
								alert('<?php echo __('Time to start next adjacent end time earlier')?>');
								
								if(row < (count-1)){
									$('#service_ServiceDetail_' + (row + 1) + '_detail_at_year').val(parseInt(detail_end_year[row]));
									$('#service_ServiceDetail_' + (row + 1) + '_detail_at_month').val(parseInt(detail_end_month[row]) + 1);
								}else{
									$('#service_new_' + (row - count + 1) + '_detail_at_year').val(parseInt(detail_end_year[row]));
									$('#service_new_' + (row -count + 1) + '_detail_at_month').val(parseInt(detail_end_month[row]) + 1);
								}	
								return false;	
							}		
						}
					} else {
						
						
						
					}					
				}
			}
			
			document.frm_service.submit();
			
			});
	});
</script>