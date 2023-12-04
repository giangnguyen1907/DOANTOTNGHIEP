<?php use_helper('I18N', 'Date')?>
<style>
#a2 table tr th{color: #333; line-height: 25px;}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Relative infomation') ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-2">
			<div style="width: 100%; text-align: center; margin: 0 auto;">
				<?php
				if ($_relative->getImage () != '') :

					$path_file = '/media-web/root/' . PreSchool::MEDIA_TYPE_RELATIVE . '/' . $_relative->getSchoolCode () . '/' . $_relative->getYearData () . '/' . $_relative->getImage ();
					echo '<img class="img-circle img-responsive" style="margin: 0 auto; max-width: 150px; max-height: 150px;" src="' . $path_file . '">';
					?>
				<?php endif;?>
			</div>
		</div>
		<div class="col-sm-12 col-md-12 col-lg-10">
			<div class="padding-10">
				<ul class="nav nav-tabs tabs-pull-right">
					<li class="active"><a href="#a1" data-toggle="tab"
						aria-expanded="true"><?php echo __('Infomation detail') ?></a></li>
					<li class=""><a href="#a2" data-toggle="tab" aria-expanded="false"><?php echo __('Relationship with the baby')?></a></li>

					<li class="pull-left"><span class="margin-top-10 display-inline">&nbsp;<?php echo $_relative->getFirstName().' '.$_relative->getLastName() ?></span></li>
				</ul>
				<div class="tab-content padding-top-10">
					<div class=" custom-scroll tab-pane fade" id="a2"
						style="height: 350px; overflow-y: scroll;">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th class="text-center"><?php echo __('Image') ?></th>
									<th class="text-center"><?php echo __('Student') ?></th>
									<th class="text-center"><?php echo __('Birthday') ?></th>
									<th class="text-center"><?php echo __('Sex') ?></th>
									<th class="text-center"><?php echo __('Class') ?>
									
									
									<th class="text-center"><?php echo __('Status') ?>
									
									
									<th class="text-center"><?php echo __('Relation') ?></th>

								</tr>
							</thead>
							<tbody>
							<?php
							foreach ( $students as $student ) :
								?>
								<tr>
									<td class="text-center" width=60px;>	
										<?php
								if ($student->getImage () != '') {
									$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $student->get ( 'school_code' ) . '/' . $student->getYearData () . '/' . $student->getImage ();
									echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
								}
								?>
									</td>
									<td width=100px;><?php echo $student->getStudentName()?></td>
									<td class="text-center" width=80px;><?php echo (false !== strtotime($student->getStudentBrithday()) ? format_date($student->getStudentBrithday(), "dd/MM/yyyy") : '' ); ?></td>

									<td class="text-center" width=40px;>
									<?php
								$sex = PreSchool::loadPsGender ();
								echo __ ( $sex [$student->getStudentSex ()] );
								?>
									</td>
									<td width=110px;><?php echo $student->getMcTitle() . ' (' . $student->getScyTitle() . ' )'?></td>
									
									<?php
								if ($student->getIsActivated () == PreSchool::ACTIVE) {
									$status = PreSchool::loadStatusStudentClass ();
									echo "<td class='text-center' width='40px;' >" . __ ( $status [$student->getStudentType ()] ) . "</td>";
								} else {
									$status = PreSchool::loadPsActivity ();
									if($student->getIsActivated ()){
									echo "<td class='text-center' width='40px;' style='color: #FF0000; '>" . __ ( $status [$student->getIsActivated ()] ) . "</td>";
									}else{
										echo "<td></td>";
									}
								}
								?>
									
									<td class="text-center" width=60px;><?php echo $student->getTitle()?></td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>




					</div>
					<div class="tab-pane fade in active" id="a1">
						<div class="col-sm-12 col-md-12 col-lg-12">

							<div class="col-md-2">
								<b><?php echo __('Ps customer')?></b>:
							</div>
							<div class="col-md-10"> <?php echo ($_relative->getSchoolName()) ? $_relative->getSchoolName() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Address')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_relative->getSchoolAddress()) ? $_relative->getSchoolAddress() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Ward')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_relative->getWardName()) ? $_relative->getWardName() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('District')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_relative->getDistrictName()) ? $_relative->getDistrictName() : '-'?></div>
							<div class="col-md-2">
								<b><?php echo __('Province')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_relative->getProvinceName()) ? $_relative->getProvinceName() : '-' ?></div>

						</div>
						<span class="timeline-seperator text-left"> <span><?php echo __('Personal information') ?></span>
						</span><br>

						<div class="col-sm-12 col-md-12 col-lg-12">

							<div class="col-sm-12 col-md-12 col-lg-6">
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Birthday')?></b>:
									</div>
									<div class="col-md-8"> <?php echo ($_relative->getBirthday()) ? date('d-m-Y', strtotime($_relative->getBirthday())) : '--/--/----'?></div>
									<div class="col-md-4">
										<b><?php echo __('Sex')?></b>:
									</div>
									<div class="col-md-8"><?php echo ( $_relative->getSex() == 1 ) ? __('Male') : __('Female') ?></div>
									<div class="col-md-4">
										<b><?php echo __('Identity card')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_relative->getIdentityCard()) ? $_relative->getIdentityCard() : '-' ?>	</div>
									<div class="col-md-4">
										<b><?php echo __('Card date')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_relative->getCardDate()) ? date('d-m-Y', strtotime($_relative->getCardDate())) : '--/--/----'?></div>
									<div class="col-md-4">
										<b><?php echo __('Card local')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_relative->getCardLocal()) ? $_relative->getCardLocal() : '-'?></div>
								</div>
							</div>

							<div class="col-sm-12 col-md-12 col-lg-6">
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Nationality')?></b>:
									</div>
									<div class="col-md-8"> <?php

									if ($_relative->getNationality ()) {
										$culture = sfContext::getInstance ()->getUser ()
											->getCulture ();
										echo __ ( sfCultureInfo::getInstance ( $culture )->getCountry ( $_relative->getNationality () ) );
									} else
										echo '-';
									?></div>
									<div class="col-md-4">
										<b><?php echo __('Ethnic')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_relative->getEthnicTitle()) ? $_relative->getEthnicTitle() : '-'?></div>
									<div class="col-md-4">
										<b><?php echo __('Religion')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_relative->getReligionTitle()) ? $_relative->getReligionTitle() : '-' ?></div>
									<div class="col-md-4">
										<b><?php echo __('Job')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_relative->getJob()) ? $_relative->getJob() : '-' ?></div>
									<div class="col-md-4">
										<b><?php echo __('Address')?></b>:
									</div>
									<div class="col-md-8"><?php echo  ($_relative->getAddress()) ? $_relative->getAddress() : '-' ?></div>
								</div>
							</div>
						</div>
						<span class="timeline-seperator text-left"> <span><?php echo __('Communications') ?></span>
						</span> <br>
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="col-md-2">
								<i class="fa fa-phone fa-lg" aria-hidden="true"></i>
							</div>
							<div class="col-md-10"><?php echo ($_relative->getPhone()) ? $_relative->getPhone() : '-' ?></div>
							<div class="col-md-2">
								<i class="fa fa-mobile fa-lg" aria-hidden="true"></i>
							</div>
							<div class="col-md-10"><?php echo ($_relative->getMobile()) ? $_relative->getMobile() : '-' ?></div>
							<div class="col-md-2">
								<i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i>
							</div>
							<div class="col-md-10"><?php echo ($_relative->getEmail()) ? $_relative->getEmail() : '-'?></div>

						</div>

						<span class="timeline-seperator text-left"> <span><?php echo __('Account information') ?></span>
						</span> <br>
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="col-md-2">
								<i class="fa fa-user fa-lg" aria-hidden="true"></i>
							</div>
							<div class="col-md-10"><?php echo ($_relative->getUsername()) ? $_relative->getUsername() : '-' ?></div>
						</div>
					</div>
					<!-- end tab -->
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">	
	<?php
	if ($sf_user->hasCredential ( 'PS_STUDENT_RELATIVE_EDIT' ) && $_relative->getDeletedAt () == '') {
		echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_relatives_edit', $_relative, array (
				'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
	}
	?>
	<?php
	if ($sf_user->hasCredential ( 'PS_STUDENT_RELATIVE_RESTORE' ) && $_relative->getDeletedAt () != '') {
		echo '<a class="btn btn-sm btn-default btn-restore" title="' . __ ( 'Detail' ) . '" href="' . url_for ( '@ps_relatives_restore?id=' . $_relative->getId () ) . '"><i class="fa-fw fa fa-rotate-right txt-color-red"></i> ' . __ ( 'Restore' ) . '</a>';
	}
	?>
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>