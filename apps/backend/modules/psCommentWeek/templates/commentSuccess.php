<?php use_helper('I18N', 'Date')?>
<?php include_partial('psCommentWeek/assets')?>
<?php include_partial('global/include/_box_modal_messages');?>

<script type="text/javascript">
$(document).ready(function() {	
	$( ".title_general" ).focusout(function() {
		var title_general =	$('.title_general').val();
		$('.title_student').val(title_general);	
	});
});
</script>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php include_partial('psCommentWeek/flashes')?>
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">

				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					
					<h2><?php echo __('PsCommentWeek Add');?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
            			    <?php include_partial('psCommentWeek/filters2', array('form' => $filters, 'configuration' => $configuration,'helper' => $helper)) ?>
            			  </div>
						</div>
						<form id="comment_week"
							action="<?php echo url_for('@ps_comment_week_all_save') ?>"
							method="post">
							<div class="dt-toolbar"></div>
							
							<div class="sf_admin_actions dt-toolbar-footer">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
    					    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_FEATURE_BRANCH_ADD',  1 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',))): ?>						
    							<a
										class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
										href="<?php echo url_for('@ps_comment_week')?>"><i
										class="fa-fw fa fa-list-ul" aria-hidden="true"
										title="<?php echo __('Back to list')?>"></i> <?php echo __('Back to list')?></a>
    							<?php endif; ?>
    							</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
    					    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_FEATURE_BRANCH_ADD',  1 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',))): ?>						
    							<button type="submit"
										class="btn btn-default btn-success btn-sm btn-psadmin pull-right">
										<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
											title="<?php echo __('Save')?>"></i> <?php echo __('Save')?></button>
    							<?php endif; ?>
    							</div>
							</div>
							
							<div id="datatable_fixed_column_wrapper"
								class="dataTables_wrapper form-inline no-footer no-padding">
								<div class="custom-scroll table-responsive">
									<table id="dt_basic"
										class="table table-striped table-bordered table-hover no-footer no-padding"
										width="100%">
										<thead>
											<tr>
												<th class="text-right" style='width: 230px'>
                								<?php echo __('Title general')?>
                								</th>
												<th>
													<div class="form-group1">
														<input class="form-control  title_general" type="text"
															name="comment_week_fix[title]" style="width: 100%;"> 
															<input class="form-control" type="hidden"
															name="comment_week_fix[ps_week]"
															value="<?php echo $ps_week?>"> <input
															class="form-control" type="hidden"
															name="comment_week_fix[ps_month]"
															value="<?php echo $ps_month?>"> <input
															class="form-control" type="hidden"
															name="comment_week_fix[ps_year]"
															value="<?php echo $ps_year?>"> <input
															class="form-control" type="hidden"
															name="comment_week_fix[ps_customer_id]"
															value="<?php echo $ps_customer_id?>">
													</div>
												</th>
												<th style='width: 170px'></th>
											</tr>
											<tr>
												<th style='width: 230px'><?php echo __('Student name').' ('.count($list_student).' H/s)'?></th>
												<th><?php echo __('Comment')?></th>
												<th style='width: 170px; text-align: center'><?php echo __('Updated by')?></th>
											</tr>
										</thead>

										<tbody>
                							<?php foreach ($list_student as $student){?>
                							<tr>
												<td>
                									<?php
													if ($student->getImage () != '') {
														$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();

														$path_file_root = '/media-web/root/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->getSchoolCode () . '/' . $student->getYearData () . '/' . $student->getImage ();
													} else {
														$path_file_root = $path_file = '/images/no_img.png';
													}
													?>
                									<a href="javascript:;" rel="popover-hover"
													data-placement="auto" data-html="true"
													data-content="<?php echo "<img style='width: 150px;' src='$path_file_root'>";?>">
														<div style="float: left;"><?php echo get_partial('global/include/_student_img2', array('path_file' => $path_file)) ?></div>
														<div style="float: left;"><?php echo $student->getStudentName() ?><br />
															<code><?php echo $student->getStudentCode();?></code>
														</div>
												</a>
												</td>
                								<?php
												$content = $updated_by = $updated_at = $title = '';

												foreach ( $comment_student as $comment ) {
													if ($comment->getStudentId () == $student->getId ()) {
														$content = $comment->getComment ();
														$title = $comment->getTitle ();
														$updated_at = $comment->getUpdatedAt ();
														$updated_by = $comment->getUpdatedBy ();
														break;
													}
												}
												?>
                								<td>
													<div style="margin-bottom: 20px">
														<input class="form-control title_student" type="text"
															style="width: 100%"
															name="comment_week[<?php echo $student->getId();?>][title]"
															placeholder="<?php echo __('Title')?>"
															value="<?php echo $title;?>">
													</div> <textarea rows="10" class="ps_comment_week_student"
														style="width: 100%; padding: 5px"
														placeholder="<?php echo __('Comment')?>"
														name="comment_week[<?php echo $student->getId();?>][comment]"><?php echo $content;?></textarea>
												</td>
												<td class="text-center">
                								<?php

echo $updated_by . '<br>';
																								echo false !== strtotime ( $updated_at ) ? date ( 'H:i d/m/Y', strtotime ( $updated_at ) ) : '&nbsp;';
																								?>
                								</td>
											</tr>
                							<?php }?>
                						</tbody>

									</table>
								</div>
							</div>

							<div class="sf_admin_actions dt-toolbar-footer">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
    					    	<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_FEATURE_BRANCH_ADD',  1 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',))): ?>						
    							<a
										class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
										href="<?php echo url_for('@ps_comment_week')?>"><i
										class="fa-fw fa fa-list-ul" aria-hidden="true"
										title="<?php echo __('Back to list')?>"></i> <?php echo __('Back to list')?></a>
    							<?php endif; ?>
    							</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">	
    					    	<button type="submit"
										class="btn btn-default btn-success btn-sm btn-psadmin pull-right">
										<i class="fa-fw fa fa-floppy-o" aria-hidden="true"
											title="<?php echo __('Save')?>"></i> <?php echo __('Save')?></button>
    							</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>