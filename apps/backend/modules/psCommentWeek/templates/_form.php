<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_comment_week', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

	<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>

      <div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<p style="margin-bottom: 25px;color: #f00;font-size: 16px;"><?php echo __('You can choise month or week') ?></p>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<div class="form-group">
				<div class='col-md-3'>
              	<?php echo $form['ps_year']->renderLabel()?>
              </div>
				<div class="col-md-9">
            	<?php echo $form['ps_year']->render()?>
            	<?php echo $form['ps_year']->renderError()?>
				</div>
			</div>

		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<div class="form-group">
				<div class='col-md-3'>
              	<?php echo $form['ps_month']->renderLabel()?>
              </div>
				<div class="col-md-9">
            	<?php echo $form['ps_month']->render()?>
            	<?php echo $form['ps_month']->renderError()?>
				</div>
			</div>

		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<div class="form-group">
				<div class='col-md-3'>
              	<?php echo $form['ps_week']->renderLabel()?>
              </div>
				<div class="col-md-9">
            	<?php echo $form['ps_week']->render()?>
            	<?php echo $form['ps_week']->renderError()?>
				</div>
			</div>

		</div>

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<div class="form-group">
				<div class='col-md-3'>
              	<?php echo $form['is_activated']->renderLabel()?>
              </div>
				<div class="col-md-9">
            	<?php echo $form['is_activated']->render()?>
            	<?php echo $form['is_activated']->renderError()?>
				</div>
			</div>

		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="form-group">
				<div class='col-md-2' style="width: 12.666667%;">
              	<?php echo $form['title']->renderLabel()?>
              </div>
				<div class="col-md-10" style="width: 87.333333%;">
            	<?php echo $form['title']->render()?>
            	<?php echo $form['title']->renderError()?>
				</div>
			</div>

		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<div class="form-group">
				<div class='col-md-2' style="width: 12.666667%;">
              	<?php echo $form['comment']->renderLabel()?>
              </div>
				<div class="col-md-10" style="width: 87.333333%;">
            	<?php echo $form['comment']->render()?>
            	<?php echo $form['comment']->renderError()?>
				</div>
			</div>

		</div>
	</div>
    <?php endforeach; ?>

    <?php include_partial('psCommentWeek/form_actions', array('ps_comment_week' => $ps_comment_week, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
