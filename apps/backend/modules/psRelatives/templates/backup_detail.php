<?php use_helper('I18N', 'Date')?>

<?php

$students = Doctrine::getTable ( 'RelativeStudent' )->findByRelativeId ( $relative->getId (), $relative->getPsCustomerId () );
?>

<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('RELATIVE INFORMATION') ?></h4>
			</div>




			<div class="col-md-2 col-sm-3 col-lg-2">
				<img
					alt="<?php echo __('Full name').': '. $_relative->getFirstName().' '.$_relative->getLastName() ?>"
					src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/pschool/'.$_relative->getSchoolCode().'/relative/'.$_relative->getImage()?>"
					id="profile-image1" class="img-circle img-responsive">
			</div>
			<div class="col-md-5 col-xs-12 col-sm-6 col-lg-6">
				<div class="container">
					<h3>

						<b><?php echo __('Full name') ?></b>: <?php echo $_relative->getFirstName().' '.$_relative->getLastName() ?></h3>

					<p>
						<b><?php echo __('Ps customer')?></b>: <?php echo $_relative->getSchoolName() ?></p>
					<p>
						<b><?php echo __('Ward')?></b>: <?php echo $_relative->getWardName() ?></p>
					<p>
						<b><?php echo __('District')?></b>: <?php echo $_relative->getDistrictName() ?></p>
					<p>
						<b><?php echo __('Province')?></b>: <?php echo $_relative->getProvinceName() ?></p>

				</div>



				<hr>
				<div class="col-sm-12 col-md-12 col-lg-6">
					<p>
						<i class="fa fa-birthday-cake fa-lg" aria-hidden="true"></i>&nbsp; <?php echo date('d-m-Y', strtotime($_relative->getBirthday()))?></p>
					<p>
						<i class="fa fa-transgender fa-lg" aria-hidden="true"></i>&nbsp; <?php echo ( $_relative->getSex() == 1 ) ? __('Male') : __('Female') ?></p>
					<p>
						<b><?php echo __('Job')?></b>: <?php echo $_relative->getJob() ?></p>

				</div>
				<p>
					<b><?php echo __('Identity card')?></b>: <?php echo $_relative->getIdentityCard() ?>	</p>
				<p>
					<b><?php echo __('Card date')?></b>: <?php echo date('d-m-Y', strtotime($_relative->getCardDate())) ?></p>
				<p>
					<b><?php echo __('Card local')?></b>: <?php echo $_relative->getCardLocal() ?></p>



				<hr>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-6">


					<p>
						<b><?php echo __('Nationality')?></b>: <?php echo __($_relative->getNationality()) ?></p>
					<p>
						<b><?php echo __('Ethnic')?></b>: <?php echo $_relative->getEthnicTitle() ?></p>
					<p>
						<b><?php echo __('Religion')?></b>: <?php echo $_relative->getReligionTitle() ?></p>
					<p>
						<b><?php echo __('Address')?></b>:  <?php echo $_relative->getAddress() ?></p>

				</div>
				<p>
					<i class="fa fa-phone fa-lg" aria-hidden="true"></i>&nbsp;<?php echo $_relative->getPhone() ?></p>
				<p>
					<i class="fa fa-mobile fa-lg" aria-hidden="true"></i>&nbsp; &nbsp;<?php echo $_relative->getMobile()?></p>
				<p>
					<i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_relative->getEmail()?></p>
				<p>
					<i class="fa fa-user fa-lg" aria-hidden="true"></i>&nbsp; <?php echo $_relative->getUsername() ?></p>

				<br>
			</div>
			<div>
				<div class="container">

					<h3>
							<?php echo __('Relationship with the baby')?>
						</h3>

					<table class="table table-bordered" style="width: 280px;">
						<thead>
							<tr>
								<th><?php echo __('Image') ?></th>
								<th><?php echo __('Full name') ?></th>
								<th><?php echo __('Relation') ?></th>

							</tr>
						</thead>
						<tbody>
							<?php

							foreach ( $students as $key => $student ) {
								?>
								<tr>
								<td width=60px;>	
					<?php
								if ($_relative->getImage () != '') {
									echo image_tag ( '/pschool/' . $_relative->getSchoolCode () . '/student/thumb/' . $student->getImage (), array (
											'style' => 'max-width:45px;text-align:center;' ) );
								}
								?>
				</td>
								<td width=90px;><?php echo $student->getStudentName()?></td>
								<td width=60px;><?php echo $student->getTitle()?></td>

							</tr>
<?php }?>
							</tbody>
					</table>

				</div>
			</div>


			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close') ?></button>				
				
			<?php echo link_to(__('Edit'), 'ps_relatives_edit', $relative, array('class' => 'btn btn-default btn-success btn-sm btn-psadmin')) ?>
			</div>

		</article>
	</div>
</section>