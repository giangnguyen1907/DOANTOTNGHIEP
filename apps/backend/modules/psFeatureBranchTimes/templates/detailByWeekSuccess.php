<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psFeatureBranchTimes/assets') ?>
<?php include_partial('global/include/_box_modal_warning');?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">      
      <?php include_partial('psFeatureBranchTimes/flashes') ?>
      <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-cutlery"></i></span>
					<h2><?php echo __('Schedule activities', array(), 'messages') ?></h2>
				</header>
				<div id="sf_admin_header" class="no-margin no-padding no-border"></div>
				<div id="sf_admin_content">
					<div class="widget-body no-padding">
						<div id="datatable_fixed_column_wrapper"
							class="dataTables_wrapper form-inline no-footer no-padding">    		        	
			        <?php include_partial('psFeatureBranchTimes/week_menu_detail', array('list_menu'=>$list_menu, 'week_start'=>$week_start, 'week_end'=>$week_end, 'week_list' => $week_list, 'width_th' => (100 / (count($week_list) + 1)),'formFilter' => $formFilter, 'form' => $form, 'ps_feature_branch_times' => $ps_feature_branch_times));?>
			        <div class="sf_admin_actions dt-toolbar-footer">		        	
			        	<?php echo $helper->linkToList(array(  'credentials' =>   array(),  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>
			        </div>
						</div>
					</div>
				</div>
				<div id="sf_admin_footer" class="no-border no-padding"></div>
			</div>
		</article>
	</div>
</section>