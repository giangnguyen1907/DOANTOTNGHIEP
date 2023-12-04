<header id="header">
	<div id="logo-group">
		<span id="logo">
		<a href="<?php echo sfContext::getInstance()->getRequest()->getUriPrefix();?>"><?php echo image_tag('banner_kidsschool.vn.png', array('alt' => 'kidsschool.vn')) ?></a>
		</span>
	</div>
	<?php if (($ps_mobile_detect_type == PreSchool::PS_DETECTION_TYPE_COMPUTER) || $ps_mobile_detect_type == PreSchool::PS_DETECTION_TYPE_TABLE):?>
	<div class="project-context">
	<?php include '_ps_header_form_filter.php';?>
	</div>
	<?php endif;?>	
	<div class="pull-right">
		<div id="hide-menu" class="btn-header pull-right">
			<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a></span>
		</div>
		<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
			<li>
				<a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown">			
				<?php
				
				// Lay thong tin cua User
				$profile_short = myUser::getUser ()->getProfileShort ();
				
				// Lay ten co so dao tao cua User
				$work_places_name = $profile_short->getWpName();				
				
				if ($profile_short->getImage () != '') :
					if ((myUser::getUser ()->getUserType () == PreSchool::USER_TYPE_TEACHER) || (myUser::getUser ()->getUserType () == PreSchool::USER_TYPE_MANAGER)) {
						$path_file = '/media-web/01/' . $profile_short->getSchoolCode () . '/' . $profile_short->getYearData () . '/' . $profile_short->getImage ();
					} elseif (myUser::getUser ()->getUserType () == PreSchool::USER_TYPE_RELATIVE) {
						$path_file = '/media-web/02/' . $profile_short->getSchoolCode () . '/' . $profile_short->getYearData () . '/' . $profile_short->getImage ();
					}
				?>
				<img style="max-width: 45px; text-align: center;" src="<?php echo $path_file;?>"/>
				<?php
				else :
					echo image_tag ( 'cache/avatar_default.png', array ('alt' => $sf_user->getUsername (),'style' => 'max-width: 45px; text-align: center;' ) );
				endif;
				?>
				</a>
				<ul class="dropdown-menu pull-right">
					<li><a href="javascript:void(0);"
						class="padding-10 padding-top-0 padding-bottom-0"
						data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full<u>S</u>creen</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo url_for('@ps_profile')?>"
						class="padding-10 padding-top-0 padding-bottom-0"> <i
							class="fa fa-user"></i> <?php echo __('Profile')?></a></li>
					<li class="divider"></li>
					<li><a href="<?php echo url_for('@ps_profile_change_password')?>"
						class="padding-10 padding-top-0 padding-bottom-0"> <i
							class="fa fa-pencil-square-o"></i> <?php echo __('Change password')?></a></li>
					<li class="divider"></li>
					<li><a href="<?php echo url_for('@sf_guard_signout')?>"
						class="padding-10 padding-top-5 padding-bottom-5"
						data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><?php echo __('Sign out')?></strong></a>
					</li>
				</ul>
			</li>
		</ul>		
		<div id="logout" class="btn-header transparent pull-right">
			<span>
			<a href="<?php echo url_for('@sf_guard_signout')?>" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i>
			</a>
			</span>
		</div>
		<div id="search-mobile" class="btn-header transparent pull-right">
			<span><a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a></span>
		</div>				
		<div id="info" class="header-search pull-right">
			<span class="ps-text-info"><?php echo __('School year').': ';?><?php echo $sf_user->getAttribute ( 'ps_school_year_default') ? $sf_user->getAttribute ( 'ps_school_year_default')->title : ''; echo ', '.$work_places_name;?></span>
		</div>		
	</div>	
</header>