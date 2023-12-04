<?php use_helper('I18N', 'Date')?>
<?php include_partial('psCmsNotification/assets')?>

<section id="widget-grid">
  <div class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">      
      <?php include_partial('psCmsNotification/flashes') ?>
      <div class="jarviswidget no-margin no-padding" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false">
        <header><span class="widget-icon"><i class="fa fa-birthday-cake" aria-hidden="true"></i></span>     
        <h2><?php echo __('Birthday Notification %%date_at%%', array('%%date_at%%' => $track_at), 'messages') ?></h2>
        </header>
        
        <div class="row">
        	<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        		<div class="well padding-10">		        	
                    <header>     
                    	<p><span class="widget-icon"><i class="fa fa-birthday-cake" aria-hidden="true"></i></span> <?php echo __('Relatives list', array(), 'messages') ?></p>
                    </header>
					
					<div class="custom-scroll table-responsive" style="max-height:400px; overflow-y: scroll;">
            					<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding" width="100%">
            						<thead>
            							<tr>
            								<th class="text-center"><?php echo __('Checkbox')?></th>
                                            <th class="text-center"><?php echo __('Full name')?></th>
                                            <th class="text-center"><?php echo __('Sex')?></th>
                                            <th class="text-center"><?php echo __('Input content')?></th>
                                            <th class="text-center"><?php echo __('Save')?></th>
                                            
                                        </tr>
            						</thead>
            						<tbody>
            							<?php foreach ($relatives_list as $relatives): ?>            							
            							<tr>
            								<td class="sf_admin_text sf_admin_list_td_checkbox"><input ></td>
                                            <td class="sf_admin_text sf_admin_list_td_full_name"><?php echo $relatives->getFullName()?></td>
                                            <td class="sf_admin_boolean sf_admin_list_td_sex"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $relatives->getSex())) ?></td>
                                            <td class="sf_admin_text sf_admin_list_td_input_content"><input ></td>
            								<td class="text-center ">
            									<?php if(myUser::credentialPsCustomers('PS_CMS_NOTIFICATIONS_ADD')):?>	
                                            	  <button style="margin-right: 5px;" type="button" class="btn btn-default btn-xs " >
                                            		<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="<?php echo __('Save')?>"></i>
                                            	  </button>
                                            	  <?php else: ?>
                                            	  <button style="margin-right: 5px;" type="button" class="btn btn-default btn-xs disabled">
                                            		<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="<?php echo __('Save')?>"></i>
                                            	  </button>
                                            	  <?php endif; ?>
            								</td>
            							</tr>
            							<?php endforeach; ?>            							
            							
            						</tbody>
            					</table>
            				</div>
				
            	</div>
        		<div class="well padding-10">		        	
                    <?php echo aaaa; ?>
        		</div>
        		<div class="well padding-10">		        	
                    <?php echo aaaa; ?>
        		</div>
        	</div>
        	
        	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        		<div class="well padding-10 row">
        			<form id="psnew-filter"
        				class="form-inline pull-left"
        				action="<?php echo url_for('ps_cms_notifications_ps_cms_notification_collection', array('action' => 'birthday_notify')) ?>"
        				method="post">
        				<div class="form-group">
        					<label> 
        				 <?php echo $formFilter['school_year_id']->render()?>
        				 <?php echo $formFilter['school_year_id']->renderError()?>
        				 </label>
        				</div>
        				<div class="form-group">
        					<label> 
        				 <?php echo $formFilter['ps_customer_id']->render()?>
        				 <?php echo $formFilter['ps_customer_id']->renderError()?>
        				 </label>
        				</div>
        				<div class="form-group ">
        					<label> 
        				 <?php echo $formFilter['ps_workplace_id']->render()?>
        				 <?php echo $formFilter['ps_workplace_id']->renderError()?>
        				 </label>
        				</div>
        				<div class="form-group ">
        					<label> 
        				 <?php echo $formFilter['ps_class_id']->render()?>
        				 <?php echo $formFilter['ps_class_id']->renderError()?>
        				 </label>
        				</div>
        				<div class="form-group ">
        					<label> 
        				 <?php echo $formFilter['track_at']->render()?>
        				 <?php echo $formFilter['track_at']->renderError()?>
        				 </label>
        				</div>
        				<br>
        				<div class="form-group ">
        					<label> 
        				 <?php echo $helper->linkToFilterSearchBirthdaynotify() ?>
        				 </label>
        				</div>
        				<div class="form-group ">
        					<label> 
        				 <?php echo $helper->linkToFilterResetBirthdaynotify() ?>
        				 </label>
        				</div>
        			</form>
        		</div>
        	</div>
	     </div>
	     
      </div>
      
    </article>
  </div>
</section>
