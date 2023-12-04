<div id="cssmenu">
<?php if ($sf_user->isAuthenticated())	: ?>
	<ul>
		<?php if (PreSchool::checkModule(array('student','studentservicediary','studentfee','psClass','receipt','relative'))) {?>
			
			<li
			<?php echo (PreSchool::currentMenu('student,studentservicediary', 'has-sub active')) ? 'class="has-sub active"' : 'class="has-sub"';?>>
				<?php echo link_to('<span>Học sinh</span>', '@student')?>
				<ul>
				<li>
						<?php echo link_to('<span>' . __('Diary') . '</span>', '@student_service_diary')?>
					</li>
				<li>
						<?php echo link_to('<span>' . __('Register Profile') . '</span>', 'student/new')?>
					</li>
			</ul>
		</li>

		<li <?php echo PreSchool::currentMenu('psClass', 'active');?>><?php echo link_to('<span>'.__('Class').'</span>', '@ps_class');?></li>

		<li <?php echo PreSchool::currentMenu('studentfee', 'active');?>>
				<?php echo link_to('<span>' . __('Batch fee') . '</span>', '@student_fee_temp')?>
			</li>

		<li <?php echo PreSchool::currentMenu('receipt', 'active');?>>
				<?php echo link_to('<span>' . __('Search receipt') . '</span>', '@receipt')?>
			</li>

		<li <?php echo PreSchool::currentMenu('relative', 'active');?>>
				<?php echo link_to('<span>' . __('Relative manager', null, 'relative') . '</span>', '@relative')?>
			</li>
		<?php }?>
		
		<?php if (PreSchool::checkModule(array('psMember'))) {?>	
			<li <?php echo PreSchool::currentMenu('psMember','active');?>>
				<?php echo link_to('<span>' . __('HR') . '</span>', '@ps_member')?>
			</li>

		<li <?php echo PreSchool::currentMenu('psMember','active');?>>
				<?php echo link_to('<span>' . __('Công tác') . '</span>', '@ps_member')?>
			</li>

		<li <?php echo PreSchool::currentMenu('psMember', 'active');?>>
				<?php echo link_to('<span>' . __('Lương') . '</span>', '@ps_member')?>
			</li>
						
		<?php }?>	
		
		<?php
	$list = array (
			'psCpanel',
			'sfGuardUser',
			'sfGuardGroup',
			'sfGuardPermission',
			'psConstant',
			'psConstantOption',
			'psSchoolYear',
			'country',
			'relationship',
			'servicegroup',
			'service',
			'psService',
			'psServiceSplit',
			'receivable',
			'feature',
			'featureoption',
			'featureoptionfeature',
			'psTypeSchool',
			'psCustomer',
			'psApp',
			'psAppPermission',
			'psEthnic',
			'psReligion' );
	if (PreSchool::checkModule ( $list )) {
		?>
			
			<li
			<?php echo (PreSchool::currentMenu('psService,servicegroup,psServiceSplit,receivable', 'has-sub active')) ? 'class="has-sub active"' : 'class="has-sub"';?>>
			<a href="javascript:void(0)"><span><?php echo __('Services');?></span></a>

			<ul>
				<li><?php echo link_to('<span>'.__('Service group').'</span>', '@service_group');?>
						</li>
				<li><?php echo link_to('<span>'.__('Services').'</span>', '@ps_service');?></li>
				<li><?php echo link_to('<span>'.__('Receivable').'</span>', '@receivable');?></li>
			</ul>

		</li>

		<li
			<?php echo PreSchool::currentMenu('psCustomer,sfGuardUser,sfGuardGroup,sfGuardPermission')? 'class="has-sub active"' : 'class="has-sub"';?>>
			<?php echo link_to('<span>'.__('Users manager').'</span>', 'sf_guard_user')?>
			
				<ul>
				<li><?php echo link_to('<span>'.__('Customers').'</span>', '@ps_customer')?></li>

				<li>
						<?php echo link_to('<span>'.__('Users').'</span>', '@sf_guard_user');?>
					</li>
				<li>
						<?php echo link_to('<span>'.__('Groups').'</span>', '@sf_guard_group');?>
					</li>
				<li>
						<?php echo link_to('<span>'.__('Permissions').'</span>', '@sf_guard_permission');?>
					</li>
			</ul>

		</li>
		<!--
			<li <?php echo PreSchool::currentMenu('country,relationship,feature,featureoption') ? 'class="has-sub active"' : 'class="has-sub"';?>>
			<?php echo '<a href="#"><span>'.__('Declaration list').'</span></a>';?>
				
					<ul>
						<li><?php echo link_to('<span>'.__('Relationship').'</span>', '@relationship');?></li>
						<li><?php echo link_to('<span>'.__('Declaring the unit').'</span>', '@feature_option');?></li>
						<li><?php echo link_to('<span>'.__("Children's Activities").'</span>', '@feature');?></li>
					</ul>
				
			</li>-->
			
			<?php 
// if (myUser::isAdministrator()) {
		?>
			<li
			<?php echo PreSchool::currentMenu('psConstant,psConstantOption,psSchoolYear,psTypeSchool,psApp,psAppPermission,psEthnic,psReligion,feature,featureoption,featureoptionfeature')? 'class="has-sub active"' : 'class="has-sub"';?>>				
				<?php echo '<a href="#"><span>'.__('System catalog').'</span></a>';?>				
					<ul>
				<li class="has-sub"><a href="javascript:void(0)"><span><?php echo __('Track activities');?></span></a>
					<ul>
						<li><?php echo link_to('<span>'.__('Declaring the unit').'</span>', '@feature_option');?></li>
						<li><?php echo link_to('<span>'.__("Children's Activities").'</span>', '@feature');?></li>
					</ul></li>

				<li class="has-sub"><a href="javascript:void(0)"><span><?php echo __('Catalog general');?></span></a>

					<ul>
									<?php if (myUser::isAdministrator()):?>
									
									<li><?php echo link_to('<span>'.__('Constant').'</span>', '@ps_constant')?></li>
									<?php else:?>
									<li><?php echo link_to('<span>'.__('Constant').'</span>', '@ps_constant_option')?></li>
									<?php endif;?>
									
									<li><?php echo link_to('<span>'.__('School year').'</span>', '@ps_school_year')?></li>


						<li><?php echo link_to('<span>'.__('Relationship').'</span>', '@relationship');?></li>
						<li><?php echo link_to('<span>'.__('Catalog type school').'</span>', '@ps_type_school');?></li>

						<li><?php echo link_to('<span>'.__('Country').'</span>', '@country');?></li>
						<li><?php echo link_to('<span>'.__('Catalog Province').'</span>', '@ps_province');?></li>
						<li><?php echo link_to('<span>'.__('Catalog District').'</span>', '@ps_district');?></li>

						<li><?php echo link_to('<span>'.__("Catalog Ethnic").'</span>', '@ps_ethnic');?></li>
						<li><?php echo link_to('<span>'.__("Catalog Religion").'</span>', '@ps_religion');?></li>

						<li><?php echo link_to('<span>'.__("Catalog Contract").'</span>', '@ps_contract');?></li>

						<li><?php echo link_to('<span>'.__("Catalog Professional").'</span>', '@ps_professional');?></li>
						<li><?php echo link_to('<span>'.__("Catalog Certificate").'</span>', '@ps_certificate');?></li>
						<li><?php echo link_to('<span>'.__("Catalog Department").'</span>', '@ps_department');?></li>
						<li><?php echo link_to('<span>'.__("Catalog Function").'</span>', '@ps_function');?></li>
					</ul></li>
				<li><?php echo link_to('<span>'.__('Application').'</span>', '@ps_app')?></li>
				<li><?php echo link_to('<span>'.__('Application permission').'</span>', '@ps_app_permission')?></li>
			</ul>

		</li>
			<?php 
// }
		?>
			
		<?php }?>
			
	</ul>
	<?php if (myUser::isAdministrator() && isset($ADpsCustomer_form_filter)) {?>
	<div class="search">
		<?php include_partial('global/loadCustomers', array('ADpsCustomer_form_filter' => $ADpsCustomer_form_filter));?>		
	</div>
	<?php }?>
<?php endif;?>
</div>
