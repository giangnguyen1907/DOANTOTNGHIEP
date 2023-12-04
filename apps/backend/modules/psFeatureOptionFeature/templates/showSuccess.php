<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psFeatureOptionFeature/assets') ?>
<script type="text/javascript">
	var number_page = <?php echo $pager->getNbResults();?>;
</script>

<script type="text/javascript">
$(document).on("ready", function(){
	$('#feature_option_feature_id, #feature_option_servicegroup_id').change(function() {

		var c_id = $('#feature_option_ps_customer_id').val();
		
		var feature_branch_id = $('#feature_option_feature_branch_id').val();

		var feature_id = $('#feature_option_feature_id').val();

		var servicegroup_id = $('#feature_option_servicegroup_id').val();

		var keywords = $('#feature_option_keyword').val();

		var keyword = keywords.trim();
		
		$('#ic-loading').show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_feature_option_feature_load_ajax') ?>',
	        type: 'POST',
	        data: 'c_id=' + c_id + '&feature_branch_id=' + feature_branch_id  + '&feature_id=' + feature_id + '&servicegroup_id=' + servicegroup_id + '&keyword=' + keyword,
	        success: function(data) {
	        	$('#ic-loading').hide();
	        	$('#load-ajax').html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	            $('#ic-loading').hide();
	        },
		});
	});

	$( "#feature_option_keyword" ).focusout(function() {

		var keywords = $('#feature_option_keyword').val();

		var feature_branch_id = $('#feature_option_feature_branch_id').val();

		var feature_id = $('#feature_option_feature_id').val();

		var servicegroup_id = $('#feature_option_servicegroup_id').val();

		var keyword = keywords.trim();
		
		$('#ic-loading').show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_feature_option_feature_load_ajax') ?>',
	        type: 'POST',
	        data: 'c_id=' + $('#feature_option_ps_customer_id').val() + '&feature_branch_id=' + feature_branch_id  + '&feature_id=' + feature_id + '&servicegroup_id=' + servicegroup_id + '&keyword=' + keyword,
	        success: function(data) {
	        	$('#ic-loading').hide();
	        	$('#load-ajax').html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	            $('#ic-loading').hide();
	        },
		});
	});

	$('.feature_option_feature_status').click(function() {
		
		var fee_id = $(this).attr('value');
		$('#status-loading-' + fee_id).show();		
		$.ajax({
	        url: '<?php echo url_for('@ps_feature_option_feature_updated_status') ?>',
	        type: 'POST',
	        data: 'fee_id=' + fee_id,
	        success: function(data) {
	        	$('#status-loading-' + fee_id).hide();
	        	$('#feature_option_feature_status_' + fee_id).html(data);
	        },
	        error: function (request, error) {
	            alert(" Can't do because: " + error);
	            $('#status-loading-' + fee_id).hide();
	        },
		});
	});	
});
</script>

<section id="widget-grid" style="overflow:hidden;">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psFeatureOptionFeature/flashes') ?>
		</article>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
			<div id="sf_admin_header" style="padding: 10px;border: 1px solid #ccc;">
				<div class="">
					<div class="form-group" style="margin-bottom: 0px">
						<label>
					        <?php echo $formFilter['ps_customer_id']->render() ?>
		        		</label>
		        		<label>
		        		 	<?php echo $formFilter['feature_id']->render() ?>
		        		</label>
		        		<label>
		        		 	<?php echo $formFilter['servicegroup_id']->render() ?>
		        		</label>
		        		<label>
		        		 	<?php echo $formFilter['keyword']->render() ?>
		        		</label>
						<label>
							<button type="submit" rel="tooltip" data-placement="bottom"
								data-original-title="<?php echo __('Search')?>"
								class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin">
								<i class="fa fa-search"></i>
							</button>
						</label>
					</div>
				</div>
			</div>
					
        </article>
		<article class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php include_partial('psFeatureOptionFeature/tpl_custom/feature_option_list', array('feature_options' => $feature_options, 'feature_branch_id' => $feature_branch->getId())) ?>
			<?php if ($pager->haveToPaginate()): ?>
	        <?php include_partial('global/include/_pagination', array('pager' => $pager)) ?>
	        <?php endif; ?>
		</article>
		<article class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<!-- sf_admin_container -->
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Featureoptionfeature List')?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">
							<div class="dt-toolbar no-margin no-padding no-border">
								<div class="col-xs-12 col-sm-12">
									<div id="sf_admin_header">
								<?php include_partial('psFeatureOptionFeature/list_header', array('pager' => $pager, 'feature_branch' => $feature_branch2,'feature' => $feature)) ?>
								</div>
								</div>
							</div>
												
					<?php if (!$pager->getNbResults()): ?>
					<div class="dt-toolbar no-margin">
								<div class="col-xs-12 col-sm-12">
									<div class="alert alert-warning fade in">	    
							    <?php echo __('No result', array(), 'sf_admin') ?>
						  	</div>
								</div>
							</div>  
				  	<?php endif;?>
					  					
					<form id="frm_batch"
								action="<?php echo url_for('ps_feature_option_feature_collection', array('action' => 'batch')) ?>"
								method="post">

								<input type="hidden" name="branch_id"
									value="<?php echo $feature_branch_id;?>">
					
					<?php include_partial('psFeatureOptionFeature/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
					
					<!-- sf_admin_footer -->
								<div
									class="sf_admin_actions dt-toolbar-footer no-border-transparent">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left">				    	
				    	<?php if ($pager->haveToPaginate()): ?>
      					<?php include_partial('psFeatureOptionFeature/pagination', array('pager' => $pager)) ?>
    					<?php endif; ?>
				    	</div>

									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">					      
					      <?php include_partial('psFeatureOptionFeature/list_batch_actions', array('helper' => $helper)) ?>					      					      
					      <?php include_partial('psFeatureOptionFeature/list_actions', array('helper' => $helper, 'feature_branch' => $feature_branch)) ?>
				      </div>
								</div>
								<!-- END: sf_admin_footer -->
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- END: sf_admin_container -->
		</article>

	</div>
</section>