<?php use_helper('I18N', 'Date')?>
<section id="widget-grid">

	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<?php include_partial('global/include/_flashes') ?>
		<div class="col-sm-12 col-md-12 col-lg-2">
				<div style="width: 100%; text-align: center; margin: 0 auto;">
				<?php
				if ($ps_member->getImage () != '') :
					$path_file = '/media-web/root/' . PreSchool::MEDIA_TYPE_TEACHER . '/' . $ps_member->getSchoolCode () . '/' . $ps_member->getYearData () . '/' . $ps_member->getImage ();
					echo '<img class="img-circle img-responsive" style="margin: 0 auto; max-width: 150px; max-height: 150px;" src="' . $path_file . '">';
				else :
					echo image_tag ( 'cache/avatar_default.png', array (
							'alt' => $sf_user->getUsername (),
							'style' => 'max-width: 150px; text-align: center;' ) );
				endif;
				?>				
			</div>
			</div>
			<div class="col-sm-12 col-md-12 col-lg-10">
				<div class="padding-10">

					<ul class="nav nav-tabs tabs-pull-right">
						<li class="active"><a href="#a1" data-toggle="tab"
							aria-expanded="true"><?php echo __('Infomation detail') ?></a></li>
						<li class="pull-left"><span class="margin-top-10 display-inline">&nbsp;<?php echo $ps_member->getFirstName().' '.$ps_member->getLastName() ?></span></li>
					</ul>
					<div class="tab-content padding-top-10">
						<div class="tab-pane fade in active" id="a1">
							<span class="timeline-seperator text-left"> <span><?php echo __('Account information') ?></span>
							</span> <br>
							<div class="col-sm-12 col-md-12 col-lg-12">
								<div class="col-md-2">
									<b><?php echo __('Username')?></b>:
								</div>
								<div class="col-md-10"><?php echo $ps_member->getUsername() ?></div>
								<div class="col-md-2">
									<b><?php echo __('Password')?></b>:
								</div>
								<div class="col-md-10">***********</div>
								<div class="col-md-2"></div>
								<div class="col-md-10">
    		<?php

						echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Change password' ), '@ps_profile_change_password', array (
								'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );

						?>
								
						</div>
							</div>
							<span class="timeline-seperator text-left"> <span><?php echo __('School information') ?></span>
							</span> <br>
							<div class="col-sm-12 col-md-12 col-lg-12">
								<div class="col-md-2">
									<b><?php echo __('Member code')?></b>:
								</div>
								<div class="col-md-10"> <?php echo ($ps_member->getMemberCode()) ? $ps_member->getMemberCode() : '-' ?></div>
								<div class="col-md-2">
									<b><?php echo __('Ps customer')?></b>:
								</div>
								<div class="col-md-10"> <?php echo ($ps_member->getSchoolName()) ? $ps_member->getSchoolName() : '-' ?></div>
								<div class="col-md-2">
									<b><?php echo __('Address')?></b>:
								</div>
								<div class="col-md-10"><?php echo ($ps_member->getSchoolAddress()) ? $ps_member->getSchoolAddress() : '-' ?></div>
								<div class="col-md-2">
									<b><?php echo __('Ward')?></b>:
								</div>
								<div class="col-md-10"><?php echo ($ps_member->getWardName()) ? $ps_member->getWardName() : '-' ?></div>
								<div class="col-md-2">
									<b><?php echo __('District')?></b>:
								</div>
								<div class="col-md-10"><?php echo ($ps_member->getDistrictName()) ? $ps_member->getDistrictName() : '-'?></div>
								<div class="col-md-2">
									<b><?php echo __('Province')?></b>:
								</div>
								<div class="col-md-10"><?php echo ($ps_member->getProvinceName()) ? $ps_member->getProvinceName() : '-' ?></div>

							</div>

							<span class="timeline-seperator text-left"> <span><?php echo __('Personal information') ?></span>
							</span><br>
							<div class="col-sm-12 col-md-12 col-lg-12">

								<div class="col-sm-12 col-md-12 col-lg-6">
									<div class="row">
										<div class="col-md-4">
											<b><?php echo __('Birthday')?></b>:
										</div>
										<div class="col-md-8"> <?php echo ($ps_member->getBirthday()) ? date('d-m-Y', strtotime($ps_member->getBirthday())) : '--/--/----'?></div>
										<div class="col-md-4">
											<b><?php echo __('Sex')?></b>:
										</div>
										<div class="col-md-8"><?php echo ( $ps_member->getSex() == 1 ) ? __('Male') : __('Female') ?></div>
										<div class="col-md-4">
											<b><?php echo __('Identity card')?></b>:
										</div>
										<div class="col-md-8"><?php echo ($ps_member->getIdentityCard()) ? $ps_member->getIdentityCard() : '-' ?>	</div>
										<div class="col-md-4">
											<b><?php echo __('Card date')?></b>:
										</div>
										<div class="col-md-8"><?php echo ($ps_member->getCardDate()) ? date('d-m-Y', strtotime($ps_member->getCardDate())) : '--/--/----'?></div>
										<div class="col-md-4">
											<b><?php echo __('Card local')?></b>:
										</div>
										<div class="col-md-8"><?php echo ($ps_member->getCardLocal()) ? $ps_member->getCardLocal() : '-'?></div>
									</div>
								</div>

								<div class="col-sm-12 col-md-12 col-lg-6">
									<div class="row">
										<div class="col-md-4">
											<b><?php echo __('Nationality')?></b>:
										</div>
										<div class="col-md-8"> <?php

if ($ps_member->getNationality ()) {
											$culture = sfContext::getInstance ()->getUser ()
												->getCulture ();
											echo __ ( sfCultureInfo::getInstance ( $culture )->getCountry ( $ps_member->getNationality () ) );
										} else
											echo '-';
										?></div>
										<div class="col-md-4">
											<b><?php echo __('Ethnic')?></b>:
										</div>
										<div class="col-md-8"><?php echo ($ps_member->getEthnicTitle()) ? $ps_member->getEthnicTitle() : '-'?></div>
										<div class="col-md-4">
											<b><?php echo __('Religion')?></b>:
										</div>
										<div class="col-md-8"><?php echo ($ps_member->getReligionTitle()) ? $ps_member->getReligionTitle() : '-' ?></div>
										<div class="col-md-4">
											<b><?php echo __('Address')?></b>:
										</div>
										<div class="col-md-8"><?php echo  ($ps_member->getAddress()) ? $ps_member->getAddress() : '-' ?></div>
									</div>
								</div>
							</div>

							<span class="timeline-seperator text-left"> <span><?php echo __('Communications') ?></span>
							</span> <br>
							<div class="col-sm-12 col-md-12 col-lg-12">
								<div class="col-md-2">
									<i class="fa fa-phone fa-lg" aria-hidden="true"></i>
								</div>
								<div class="col-md-10"><?php echo ($ps_member->getPhone()) ? $ps_member->getPhone() : '-' ?></div>
								<div class="col-md-2">
									<i class="fa fa-mobile fa-lg" aria-hidden="true"></i>
								</div>
								<div class="col-md-10"><?php echo ($ps_member->getMobile()) ? $ps_member->getMobile() : '-' ?></div>
								<div class="col-md-2">
									<i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i>
								</div>
								<div class="col-md-10"><?php echo ($ps_member->getEmail()) ? $ps_member->getEmail() : '-'?></div>


							</div>

						</div>
						<!-- end tab -->
					</div>
				</div>
			</div>
		</article>
	</div>

</section>
