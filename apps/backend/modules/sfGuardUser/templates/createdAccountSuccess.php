<?php use_helper('I18N', 'Date') ?>
<?php include_partial('global/include/_box_modal_messages');?>

<?php //print_r($array_function);?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('sfGuardUser/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Created username login', array(), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
								<div id="dt_basic_filter"
									class="sf_admin_filter dataTables_filter">
        	<?php if ($formFilter->hasGlobalErrors()): ?>
              <?php echo $formFilter->renderGlobalErrors() ?>
            <?php endif; ?>
        	<form id="ps-filter" class="form-inline pull-left"
										action="<?php echo url_for('sf_guard_user_collection', array('action' => 'createdAccount')) ?>"
										method="post">
										<div class="pull-left">
            	 	<?php echo $formFilter->renderHiddenFields(true) ?>
            	 	<div class="form-group">
						<label>
                		 	<?php echo $formFilter['is_type']->render() ?>
                		 	<?php echo $formFilter['is_type']->renderError() ?>
                		 </label>
					</div>
            	 	<div class="form-group">
												<label>
                		 	<?php echo $formFilter['ps_user']->render() ?>
                		 	<?php echo $formFilter['ps_user']->renderError() ?>
                		 </label>
											</div>
											
											<div class="form-group">
												<label>
                		 	<?php echo $formFilter['ps_password']->render() ?>
                		 	<?php echo $formFilter['ps_password']->renderError() ?>
                		</label>
											</div>
											<div class="form-group">
												<label>
            				<?php echo $helper->linkToFilterSearch() ?>
            			</label>
											</div>

										</div>
									</form>
								</div>
							</div>
						</div>
						<div style="clear: both"></div>
	<div id="datatable_fixed_column_wrapper" class="dataTables_wrapper form-inline no-footer" style="padding: 0px 15px">
		
		<?php $total = count($listRelative);?>
		<h4 style="padding: 10px 0px;font-weight: 600;"><?php echo __('Number account created:').$total?></h4>
		
		<?php if(count($array_error) > 0){?>
		<h4 style="padding: 10px 0px;font-weight: 600;"><?php echo __('List created account error')?></h4>
		<div class="row custom-scroll table-responsive" style="height: 500px; overflow-y: scroll;">
    	
    	<table id="table_item_icon" class="table table-striped table-bordered table-hover" width="100%">
          
          <thead>
        	  <tr>
        	    <th style="width: 100px" class="text-center"><?php echo __('STT')?></th>
        		<th><?php echo __('Name')?></th>
        		<th><?php echo __('Phone')?></th>
        		<th><?php echo __('Email')?></th>
        	  </tr>
          </thead>
          <tbody>
    		<?php foreach ($array_error as $key=> $member){?>
			<tr>
    			<td class="text-center"><?php echo $key+1;?></td>
    			<td><?php echo $member->getFirstName().' '.$member->getLastName();?></td>
				<td><?php echo $member->getMobile();?></td>
				<td><?php echo $member->getEmail();?></td>
			</tr>
    		<?php }?>
    		
    	  </tbody>
    	 </table>
    	</div>
    	<?php }?>
	</div>

					</div>
				</div>
			</div>
		</article>

	</div>
</section>
