<?php use_helper('I18N', 'Date')?>
<?php include_partial('psLogtimes/assets')?>
<script type="text/javascript">
$(document).ready(function() {
	$('.time_picker').timepicker({
		timeFormat : 'HH:mm',
		showMeridian : false,
		defaultTime : null
	});
});
</script>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psLogtimes/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Delay logtimes', array(), 'messages') ?>
					<?php echo __('Config time logout', array(), 'messages') . $config_date?>
					</h2>
				</header>
				
            	<div>
            		<div class="widget-body no-padding">
            			<div class="dt-toolbar">
            			  <div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psLogtimes/filters_delay', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
            			  </div>
            			</div>
            		
            			<form class="form-horizontal" action="">
            			<input type="hidden" name="ps_logtimes_delay[date_time]" value="<?php echo $date_at; ?>" id="ps_logtimes_delay_date_time">
            			<div id="datatable_fixed_column_wrapper" class="dataTables_wrapper form-inline no-footer no-padding">
            				<div class="custom-scroll table-responsive">
            					<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding" width="100%">
            						
            						<thead>
            							<tr>
            								<th class="text-center"> <?php echo __('STT', array(), 'messages') ?></th>
            								<th> <?php echo __('Full name', array(), 'messages') ?></th>
            								<th class="text-center"><?php echo __('Class', array(), 'messages') ?></th>
                                            <th class="text-center"><?php echo __('Logout at', array(), 'messages') ?></th>
                                            <th class="text-center"><?php echo __('Action', array(), 'messages') ?></th>
                                        </tr>
            						</thead>
            						<tfoot>
                                        <tr>
                                          <th colspan="6">
                                          <div class="text-results">
                                          		<?php echo __('Hiển thị : %%nbResults%% Kết quả', array('%%from_item%%' => $pager->getFirstIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
                                          </div>            
                                          </th>
                                        </tr>
                                      </tfoot>
            						<tbody>
            							<?php foreach ($filter_list_student as $ky=> $list_student ): ?>
            							<?php 
            							$list_relative = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $list_student->getStudentId(), $list_student->getPsCustomerId());
                                        ?>
            							<tr>
            								<td class="text-center"><?php echo $ky+1; ?></td>
                							<td><?php echo $list_student->getStudentName() ?></td>
                							<td class="text-center"><?php echo $list_student->getClassName() ?></td>
                                            
                                            <td class="sf_admin_text sf_admin_list_td_logout_infomation">
                                            	<input type="hidden" name="student_logtime[<?php echo $student_id; ?>][student_id]" value="<?php echo $list_student->getStudentId() ?>">
                                                <div id="ic-loading-<?php echo $list_student->getStudentId();?>" style="display: none;">
                                                	<i class="fa fa-spinner fa-2x fa-spin text-success" style="padding:3px;"></i><?php echo __('Loading...')?>
                                                </div>                                            
                                            	<ul class="list-inline" id="box-<?php echo $list_student->getStudentId() ?>">
                                            		<?php echo get_partial('psLogtimes/row_li_delay', array('list_relative' => $list_relative, 'list_student' => $list_student,  'filter_value' => $filter_value, 'check_logtime' => $check_logtime))?>
                                            	</ul>                                              
                                           </td>
                                            
                                           <td class="text-center">
                                           <?php if(date("Hi") > date("Hi", strtotime($config_date))){ $disable = '';}else{ $disable = 'disabled';} ?>
                                           		
                                           		<?php if ($sf_user->hasCredential(array('PS_STUDENT_ATTENDANCE_DELAY'))): ?>                                           		
                                                <button type="button" class="btn btn-default btn-success btn-sm btn-delay-logtime" data-value="<?php echo $list_student->getStudentId() ?>">
                                                	<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="<?php echo __('Save');?>"></i><?php echo __('Save');?>
                                                </button>
                                                <?php endif;?>                                                                                          	
                                            	<input type="hidden" class="filter form-control" value="<?php echo $list_student->getId() ?>" name="lt_id" id="lt_id_<?php echo $list_student->getStudentId() ?>"/>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
            						</tbody>
            					</table>
            				</div>
            			</div>
            			</form>
            		</div>
            	</div>
			</div>

		</article>
		
	</div>
</section>