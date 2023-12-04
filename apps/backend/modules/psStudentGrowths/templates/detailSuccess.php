<?php use_helper('I18N', 'Date') ?>
<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
?>
<style>
@media ( min-width : 992px) .modal-lg {
	min-width:900px;
	width:1200px;
}
#home tr th{color: #333;line-height: 35px;}
.modal-lg {
	min-width: 900px;
	width: 1200px;
}
</style>
<?php $student_sex = $student->getSex();?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $student->getFirstName().' '.$student->getLastName() ?></h4>
	<h5 class="modal-title"><?php echo __('Birthday ') .format_date($student->getBirthday(),'dd-MM-yyyy') ?> <?php echo __('Gender ') ?><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student_sex)) ?></h5>
</div>

<div class="modal-body">

	<div class="row">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#home"><?php echo __('Growths information') ?></a></li>
			<li><a data-toggle="tab" href="#menu1"><?php echo __('Chart Height') ?></a></li>
			<li><a data-toggle="tab" href="#menu2"><?php echo __('Chart Weight') ?></a></li>
		</ul>
		<div class="tab-content">
			<br>
			<div id="home" class="tab-pane fade in active">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th class="text-center"><?php echo __('Age') ?></th>
								<th class="text-center"><?php echo __('Height') ?></th>
								<th class="text-center"><?php echo __('Weight') ?></th>
								<th class="text-center"><?php echo __('Index tooth') ?></th>
								<th class="text-center"><?php echo __('Index throat') ?></th>
								<th class="text-center"><?php echo __('Index eye') ?></th>
								<th class="text-center"><?php echo __('Index heart') ?></th>
								<th class="text-center"><?php echo __('Index lung') ?></th>
								<th class="text-center"><?php echo __('Index skin') ?></th>
								<th class="text-center"><?php echo __('Examination') ?></th>
								<th class="text-center"><?php echo __('Action') ?></th>
							</tr>
						</thead>
						<tbody>
              <?php foreach ($growths as $growth): ?>
                <tr>
								<td class="text-center"> <?php echo $growth->getIndexAge() ?> </td>
								<td class="text-center"><b><?php echo $growth->getHeight() ?></b>
									<br />
                  <?php include_partial('psStudentGrowths/index_height', array('value' => $growth->getIndexHeight()))?>
                  </td>

								<td class="text-center"><b><?php echo $growth->getWeight() ?></b>
									<br />
                  <?php include_partial('psStudentGrowths/index_weight', array('value' => $growth->getIndexWeight()))?>
                  </td>
								<td class="text-center"><?php echo $growth->getIndexTooth(); ?></td>
								<td class="text-center"><?php echo $growth->getIndexThroat(); ?></td>
								<td class="text-center"><?php echo $growth->getIndexEye(); ?></td>
								<td class="text-center"><?php echo $growth->getIndexHeart(); ?></td>
								<td class="text-center"><?php echo $growth->getIndexLung(); ?></td>
								<td class="text-center"><?php echo $growth->getIndexSkin(); ?></td>
								<td class="text-center"> <?php echo $growth->getName(); ?> <br>
					<?php echo false !== strtotime($growth->getInputDateAt()) ? format_date($growth->getInputDateAt(), "dd/MM/yyyy") : '&nbsp;' ?>                  
                  </td>
								<td class="text-center">
                      <?php if ($sf_user->hasCredential('PS_MEDICAL_GROWTH_EDIT')): ?>
                      <?php echo $helper->linkToEdit($growth, array(  'credentials' => 'PS_MEDICAL_GROWTH_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
                      <?php endif; ?>
                      <?php if ($sf_user->hasCredential('PS_MEDICAL_GROWTH_DELETE')): ?>
                      <?php echo $helper->linkToDelete($growth, array(  'credentials' => 'PS_MEDICAL_GROWTH_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
                      <?php endif; ?>
				  </td>
							</tr>
              <?php endforeach ?>
            </tbody>
					</table>
				</div>
			</div>
			<div id="menu1" class="tab-pane fade">
				<div class="table-responsive">
					<div id="content">
						<section id="widget-grid" class="">
							<div class="row">
								<article class="col-sm-12">
									<div class="jarviswidget" id="wid-id-0"
										data-widget-togglebutton="false"
										data-widget-editbutton="false"
										data-widget-fullscreenbutton="false"
										data-widget-colorbutton="false"
										data-widget-deletebutton="false">
										<div class="no-padding">
											<div class="widget-body">
												<div id="myTabContent" class="tab-content">
													<div
														class="tab-pane fade active in padding-10 no-padding-bottom">
														<div class="widget-body-toolbar bg-color-white smart-form"
															id="rev-toggles1">
															<div class="inline-group">
																<input type="color" value="#ff7d7d"> <label for="body"><?php echo __('Stunting') ?></label>

																<input type="color" value="#f8a980"> <label for="body"><?php echo __('Low') ?></label>

																<input type="color" value="#c7eafd"> <label for="body"><?php echo __('Normal Height') ?></label>

																<input type="color" value="#fff8af"> <label for="body"><?php echo __('Tall') ?></label>
															</div>
														</div>
														<div class="padding-10">
															<div id="statsChart"
																class="chart-large has-legend-unique"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<p><?php echo __('World Health Organization') ?> </p>
								</article>
							</div>
						</section>
					</div>
				</div>
			</div>

			<div id="menu2" class="tab-pane fade">
				<div class="table-responsive">
					<div id="content">
						<section id="widget-grid" class="">
							<div class="row">
								<article class="col-sm-12">
									<div class="jarviswidget" id="wid-id-0"
										data-widget-togglebutton="false"
										data-widget-editbutton="false"
										data-widget-fullscreenbutton="false"
										data-widget-colorbutton="false"
										data-widget-deletebutton="false">
										<div class="no-padding">
											<div class="widget-body">
												<div id="myTabContent" class="tab-content">
													<div
														class="tab-pane fade active in padding-10 no-padding-bottom">
														<div class="widget-body-toolbar bg-color-white smart-form"
															id="rev-toggles1">
															<div class="inline-group">
																<input type="color" value="#ff7d7d"> <label for="body"><?php echo __('Malnutrition') ?></label>

																<input type="color" value="#f8a980"> <label for="body"><?php echo __('Thin') ?></label>

																<input type="color" value="#c7eafd"> <label for="body"><?php echo __('Normal Weight') ?></label>

																<input type="color" value="#fff8af"> <label for="body"><?php echo __('Fat') ?></label>
															</div>
														</div>
														<div class="padding-10">
															<div id="statsChart1"
																class="chart-large has-legend-unique"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<p><?php echo __('World Health Organization') ?> </p>
								</article>
							</div>
						</section>
					</div>
				</div>
			</div>

		</div>

	</div>

</div>

<script>

$(document).ready(function() {
	//Ve bieu do chieu cao			
	$(function() {
		
		var minheight1 = [
    			<?php foreach ($student_bmi as $growth1): ?>
    			<?php if($growth1->getSex() == $student_sex && $growth1->getMinHeight1() > 0): ?>
    			[<?php echo $growth1->getIsMonth() ?>, <?php echo $growth1->getMinHeight1()?>],
    				
    			<?php endif ?>
    			<?php endforeach ?>
    		],
		
    		minheight = [
    			<?php foreach ($student_bmi as $growth1): ?>
    			<?php if($growth1->getSex() == $student_sex && $growth1->getMinHeight() > 0): ?>
    			
    			[<?php echo $growth1->getIsMonth() ?>, <?php echo $growth1->getMinHeight()?>],
    			
    			<?php endif ?>
    			<?php endforeach ?>
    			],

    		maxheight1 = [
    			<?php foreach ($student_bmi as $growth1): ?>
    			<?php if($growth1->getSex() == $student_sex && $growth1->getMaxHeight1() > 0): ?>
    			[<?php echo $growth1->getIsMonth() ?>, <?php echo $growth1->getMaxHeight1()?>],
    					
    			<?php endif ?>
    			<?php endforeach ?>
    			],

			maxheight = [
				<?php foreach ($student_bmi as $growth1): ?>
				<?php if($growth1->getSex() == $student_sex && $growth1->getMaxHeight() > 0): ?>
				[<?php echo $growth1->getIsMonth() ?>, <?php echo $growth1->getMaxHeight()?>],
				
				<?php endif ?>
				<?php endforeach ?>
				], 
				
			heightchild = [
					<?php foreach ($growths as $growth1): ?>
					[<?php echo $growth1->getIndexAge() ?>, <?php echo $growth1->getHeight()?>],
					<?php endforeach ?>
					], 

			mediumheight = [
				<?php foreach ($student_bmi as $growth1): ?>
				<?php if($growth1->getSex() == $student_sex && $growth1->getMediumWeight() > 0): ?>
				[<?php echo $growth1->getIsMonth() ?>, <?php echo $growth1->getMediumHeight()?>],
				
				<?php endif ?>
				<?php endforeach ?>
				],  
					
			data = [{
			label : "<?php echo __('Min height') ?>",
			data : minheight,
			color : "#b1a409",
			lines : {
				show : true,
				lineWidth : 1,
				fill : true,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.1
					}]
				}
			},
			points : {
				show : false
			}
		}, 
		{
			label : "<?php echo __('Min height1') ?>",
			data : minheight1,
			color : "#ea1f39",
			lines : {
				show : true,
				lineWidth : 1,
				fill : true,
				fillColor : {
					colors : [{
						opacity : 0.2
					}, {
						opacity : 1
					}]
				}
			},
			points : {
				show : false
			}
		},
		{
			label : "<?php echo __('Max height1') ?>",
			data : maxheight1,
			color : "#ea1f39",
			lines : {
				show : true,
				lineWidth : 1,
				fill : false,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.13
					}]
				}
			},
			points : {
				show : false
			}
		},
		
		{
			label : "<?php echo __('Max height') ?>",
			data : maxheight,
			color : "#669eb5",
			lines : {
				show : true,
				lineWidth : 1,
				fill : true,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.13
					}]
				}
			},
			points : {
				show : false
			}
		},
		{
			label : "<?php echo __('Child height') ?>",
			data : heightchild,
			color : "#FF0000",
			lines : {
				show : true,
				lineWidth : 1,
				fill : false,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.13
					}]
				}
			},
			points : {
				show : true
			}
		}
		,
		{
			label : "<?php echo __('Medium height') ?>",
			data : mediumheight,
			color : "#c79121",
			lines : {
				show : true,
				lineWidth : 1,
				fill : false,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.13
					}]
				}
			},
			points : {
				show : false
			}
		}
		];

		var options = {
			grid : {
				hoverable : true
			},
// 			colors : [ "#669eb5", "#15130f","#cccccc","#ff0000","EEEEEE"],
			
			tooltip : true,
			tooltipOpts : {
				defaultTheme : false
			},
			xaxis : {
				ticks : [
					<?php foreach ($student_bmi as $growth1): ?>
					<?php if((int)$growth1->getIsMonth()%2==0): ?>
					
					[<?php echo $growth1->getIsMonth() ?>, '<?php echo $growth1->getIsMonth() ?>'],
					
					<?php endif ?>
					<?php endforeach ?>
					]
			},
			yaxes : {
				
			}
		};

		var plot3 = $.plot($("#statsChart"), data, options);
	});
// Ve bieu do can nang

$(function() {
		
		var minweight1 = [
    			<?php foreach ($student_bmi as $growth2): ?>
    			<?php if($growth2->getSex() == $student_sex && $growth2->getMinWeight1() > 0): ?>
    			[<?php echo $growth2->getIsMonth() ?>, <?php echo $growth2->getMinWeight1()?>],
    				
    			<?php endif ?>
    			<?php endforeach ?>
    		],
		
    		minweight = [
    			<?php foreach ($student_bmi as $growth2): ?>
    			<?php if($growth2->getSex() == $student_sex && $growth2->getMinWeight() > 0): ?>
    			
    			[<?php echo $growth2->getIsMonth() ?>, <?php echo $growth2->getMinWeight()?>],
    			
    			<?php endif ?>
    			<?php endforeach ?>
    			],

    		maxweight1 = [
    			<?php foreach ($student_bmi as $growth2): ?>
    			<?php if($growth2->getSex() == $student_sex && $growth2->getMaxWeight1() > 0): ?>
    			[<?php echo $growth2->getIsMonth() ?>, <?php echo $growth2->getMaxWeight1()?>],
    					
    			<?php endif ?>
    			<?php endforeach ?>
    			],

			maxweight = [
				<?php foreach ($student_bmi as $growth2): ?>
				<?php if($growth2->getSex() == $student_sex && $growth2->getMaxWeight() > 0): ?>
				[<?php echo $growth2->getIsMonth() ?>, <?php echo $growth2->getMaxWeight()?>],
				
				<?php endif ?>
				<?php endforeach ?>
				], 

			mediumweight = [
				<?php foreach ($student_bmi as $growth2): ?>
				<?php if($growth2->getSex() == $student_sex && $growth2->getMediumWeight() > 0): ?>
				[<?php echo $growth2->getIsMonth() ?>, <?php echo $growth2->getMediumWeight()?>],
				<?php endif ?>
				<?php endforeach ?>
				], 
				
			weightchild = [
					<?php foreach ($growths as $growth2): ?>
					[<?php echo $growth2->getIndexAge() ?>, <?php echo $growth2->getWeight()?>],
					<?php endforeach ?>
					], 
	
			data = [{
			label : "<?php echo __('Min weight') ?>",
			data : minweight,
			color : "#b1a409",
			lines : {
				show : true,
				lineWidth : 1,
				fill : true,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.1
					}]
				}
			},
			points : {
				show : false
			}
		}, 
		{
			label : "<?php echo __('Min weight1') ?>",
			data : minweight1,
			color : "#ea1f39",
			lines : {
				show : true,
				lineWidth : 1,
				fill : true,
				fillColor : {
					colors : [{
						opacity : 0.2
					}, {
						opacity : 1
					}]
				}
			},
			points : {
				show : false
			}
		},
		{
			label : "<?php echo __('Max weight1') ?>",
			data : maxweight1,
			color : "#ea1f39",
			lines : {
				show : true,
				lineWidth : 1,
				fill : false,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.13
					}]
				}
			},
			points : {
				show : false
			}
		},
		
		{
			label : "<?php echo __('Max weight') ?>",
			data : maxweight,
			color : "#669eb5",
			lines : {
				show : true,
				lineWidth : 1,
				fill : true,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.13
					}]
				}
			},
			points : {
				show : false
			}
		},
		{
			label : "<?php echo __('Child weight') ?>",
			data : weightchild,
			color : "#FF0000",
			lines : {
				show : true,
				lineWidth : 1,
				fill : false,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.13
					}]
				}
			},
			points : {
				show : true
			}
		},
		{
			label : "<?php echo __('Medium weight') ?>",
			data : mediumweight,
			color : "#c79121",
			lines : {
				show : true,
				lineWidth : 1,
				fill : false,
				fillColor : {
					colors : [{
						opacity : 0.1
					}, {
						opacity : 0.13
					}]
				}
			},
			points : {
				show : false
			}
		}
		];

		var options = {
			grid : {
				hoverable : true
			},
			//colors : [ "#669eb5", "#15130f","#cccccc","#ff0000","EEEEEE"],
			
			tooltip : true,
			tooltipOpts : {
				defaultTheme : false
			},
			xaxis : {
				ticks : [
					<?php foreach ($student_bmi as $growth2): ?>
					<?php if((int)$growth2->getIsMonth()%2==0): ?>
					
					[<?php echo $growth2->getIsMonth() ?>, '<?php echo $growth2->getIsMonth() ?>'],
					
					<?php endif ?>
					<?php endforeach ?>
					]
			},
			yaxes : {
				
			}
		};

		var plot3 = $.plot($("#statsChart1"), data, options);
	});	

});
</script>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close')?></button>
</div>
