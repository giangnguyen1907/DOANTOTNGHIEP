<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<?php //echo $isread?>
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true"
		<?php if ($filter_value['type'] == 'received' && $isread < 1) :?>
		onClick="window.location.reload()" <?php endif;?>>×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Notification infomation') ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Title') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p>
					<strong><?php echo $notification->getTitle()?></strong>
				</p>
			</div>
		</div>
		<?php if ($notification->is_status == 'sent') :?>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Date at') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $notification->getDateAt() ? date('H:i d-m-Y',strtotime($notification->getDateAt())) : '';?></p>
			</div>
		</div>
		<?php endif;?>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Description') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<?php echo $notification->getDescription() ? sfOutputEscaperGetterDecorator::unescape($notification->getDescription()) : ''?>
				<p></p>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Is system') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $notification->getIsSystem()  ?  __('yes') :  __('no');?></p>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Send school') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php

if ($notification->getIsAll () == 1) {
					echo __ ( 'Is school' );
				} elseif ($notification->getIsAll () == 2) {
					echo __ ( 'Is workplace' );
				} else {
					echo __ ( 'no' );
				}
				?></p>

			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Is object') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php
				if ($notification->getIsObject () == 1) {
					echo __ ( 'Is teacher' );
				} elseif ($notification->getIsObject () == 2) {
					echo __ ( 'Is relative' );
				} else {
					echo __ ( 'no' );
				}
				?></p>

			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Number received') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $notification->getTotalObjectReceived();?></p>

			</div>
		</div>

		<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('Created by') ?></label>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<p><?php echo $notification->getCreatedBy();?></p>

			</div>
		</div>
<?php if( $notification->getIsAll() > 0 || $notification->getIsSystem() > 0 || $notification->getIsObject()) {}else{?>	
<div class="form-group">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('List teacher') ?></label>
			</div>
	<?php
	$list_received_id = explode ( ',', $notification->getTextObjectReceived () );
	$user_list = Doctrine::getTable ( 'sfGuardUser' )->getUserNotification ( $list_received_id, PreSchool::USER_TYPE_TEACHER );
	?>
	<div
				class="col-xs-12 col-sm-12 col-md-9 col-lg-9 custom-scroll table-responsive"
				style="max-height: 200px; min-height: 30px; overflow-y: scroll;">		
    <?php  foreach ($user_list as $user) { ?>
    <?php if($user->getUserType() == PreSchool::USER_TYPE_TEACHER){?>
      <div class="col-md-3" style="padding: 0px 5px 0px 0px">
       	   <?php echo $user->getFullname() . '<br/> (' . $user->getUsername() . ') '; ?>
       </div> 
       <?php } ?>
    <?php } ?> 
    
	</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<label class="control-label"><?php echo __('List relative') ?></label>
			</div>

			<div
				class="col-xs-12 col-sm-12 col-md-9 col-lg-9 custom-scroll table-responsive"
				style="max-height: 200px; overflow-y: scroll;">
	<?php
	foreach ( $user_list as $users ) {
		?>
	<?php if($users->getUserType() == PreSchool::USER_TYPE_RELATIVE){?>
    	<div class="col-md-3" style="padding: 0px 5px 0px 0px">
       	   <?php echo $users->getFullname() . '<br/> (' . $users->getUsername() . ') '; ?>
       </div> 
       <?php }?>
    <?php }?>
	</div>
		</div>
<?php } ?>		
</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"
		<?php if ($filter_value['type'] == 'received' && $isread < 1) :?>
		onClick="window.location.reload()" <?php endif;?>>
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?>
	</button>				
	<?php
	if ($filter_value ['type'] == 'drafts') {
		echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_cms_notifications_edit', $notification, array (
				'class' => 'btn btn-default btn-success btn-sm btn-psadmin'
    ));
    }
    ?>
</div>


