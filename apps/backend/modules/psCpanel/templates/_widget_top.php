<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
	<div class="info-box">
		<span class="info-box-icon bg-aqua"><i
			class="fa fa-building-o txt-color-white"></i></span>

		<div class="info-box-content">
			<span class="info-box-text"><?php echo __('Work places')?></span> <span
				class="info-box-number"><?php echo $total_workplaces;?></span>
		</div>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
	<div class="info-box">
		<span class="info-box-icon bg-yellow"><i
			class="fa fa-calendar txt-color-white"></i></span>
		<div class="info-box-content">
			<span class="info-box-text"><?php echo __('School year')?></span> <span
				class="info-box-number"><?php echo $schoolYears;?></span>
		</div>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
	<div class="info-box">
		<span class="info-box-icon bg-red"><i
			class="fa fa-television txt-color-white"></i></span>
		<div class="info-box-content">
			<span class="info-box-text"><?php echo __('Class')?></span> <span
				class="info-box-number"><?php echo $total_class;?></span>
		</div>
	</div>
</div>

<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
	<div class="info-box">
		<span class="info-box-icon bg-green"><i
			class="fa fa-group txt-color-white"></i></span>
		<div class="info-box-content">
			<span class="info-box-text"><?php echo __('Student')?> <?php echo __('Official')?> & <?php echo __('School test')?></span>
			<span class="info-box-number"><?php echo $total_student;?></span>
		</div>
	</div>
</div>

<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
	<div class="info-box">
		<span class="info-box-icon bg-yellow"><i
			class="fa fa-group txt-color-white"></i></span>
		<div class="info-box-content">
			<span class="info-box-text"><?php echo __('Student not class')?></span>
			<span class="info-box-number"><?php echo $total_student_not_in_class;?></span>
		</div>
	</div>
</div>

<?php if (myUser::credentialPsCustomers('PS_SYSTEM_CUSTOMER_FILTER_SCHOOL')):?>
<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
	<div class="info-box">
		<?php
	$form_filter = new sfFormFilter ();
	$form_filter->disableLocalCSRFProtection ();

	$form_filter->setWidget ( 'cabcid', new sfWidgetFormDoctrineChoice ( array (
			'model' => 'PsCustomer',
			'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
			'add_empty' => __ ( '-Select-' ) ), array (
			'onchange' => 'this.form.submit();' ) ) );

	$cabcid = $sf_request->getParameter ( 'cabcid', myUser::getPscustomerID () );

	$form_filter->setDefault ( 'cabcid', $cabcid );
	?>
		<form id="ADpsCustomerID_form"
			action="<?php echo url_for('@homepage')?>" method="post">
		<?php echo $form_filter->renderHiddenFields();?>
		<?php echo $form_filter['cabcid']->render(array('class' => 'select2'));?>
		</form>
	</div>
</div>
<?php endif;?>