<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
 
 <?php echo form_tag_for($form, '@ps_cms_notifications', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>
<fieldset>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<div class='col-md-3 control-label'>
<?php echo $form['title']->renderLabel() ?>
</div>
					<div class="col-md-9">
	<?php echo $form['title']->render() ?>
	<span id='remainingInput_text' class="note pull-right">0/150</span> <small
							class="help-block" data-fv-result="INVALID"><?php echo $form['title']->renderError() ?></small>
					</div>
				</div>


				<div class="form-group">
					<div class='col-md-3 control-label'>
<?php echo $form['description']->renderLabel() ?>
</div>
					<div class="col-md-9">
	<?php echo $form['description']->render() ?>
	<span id='remainingInput_textarea' class="note pull-right">0/500</span>
						<small class="help-block" data-fv-result="INVALID"><?php echo $form['description']->renderError() ?></small>
					</div>
				</div>
<?php if (myUser::credentialPsCustomers('PS_CMS_NOTIFICATIONS_SYSTEM')) :?>
<div class="form-group">
					<div class='col-md-3 control-label'>
<?php echo $form['is_system']->renderLabel() ?>
</div>
					<div class="col-md-9">
	<?php echo $form['is_system']->render() ?>
	<small class="help-block" data-fv-result="INVALID"><?php echo $form['is_system']->renderError() ?></small>
					</div>
				</div>
<?php endif;?>
<?php if (myUser::credentialPsCustomers('PS_CMS_NOTIFICATIONS_ALL')) :?>
<div class="form-group">
					<div class='col-md-3 control-label'>
	<?php echo $form['is_all']->renderLabel() ?>
	</div>
					<div class="col-md-9">
	<?php echo $form['is_all']->render() ?>
	<small class="help-block" data-fv-result="INVALID"><?php echo $form['is_all']->renderError() ?></small>
					</div>
				</div>
<?php endif;?>

<div class="form-group">
					<div class='col-md-3 control-label'>
	<?php echo $form['is_basic']->renderLabel() ?>
	</div>
					<div class="col-md-9">
	<?php echo $form['is_basic']->render() ?>
	<small class="help-block" data-fv-result="INVALID"><?php echo $form['is_basic']->renderError() ?></small>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<div class='col-md-3 control-label'>
<?php echo $form['list_received_teacher']->renderLabel() ?>
</div>
					<div class="col-md-9 custom-scroll table-responsive"
						style="height: 200px; overflow-y: scroll;">
	<?php echo $form['list_received_teacher']->render() ?>
	<small class="help-block" data-fv-result="INVALID"><?php echo $form['list_received_teacher']->renderError() ?></small>
					</div>
				</div>

				<div class="form-group">
					<div class='col-md-3 control-label'>
<?php echo $form['list_received_relative']->renderLabel() ?>
</div>
					<div class="col-md-9 custom-scroll table-responsive"
						style="height: 200px; overflow-y: scroll;">
	<?php echo $form['list_received_relative']->render() ?>
	<small class="help-block" data-fv-result="INVALID"><?php echo $form['list_received_relative']->renderError() ?></small>
					</div>
				</div>

			</div>
		</div>
	</fieldset>
    <?php include_partial('psCmsNotifications/form_actions', array('ps_cms_notifications' => $ps_cms_notifications, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
