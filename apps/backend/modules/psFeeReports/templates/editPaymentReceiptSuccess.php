<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psFeeReports/assets') ?>
<script type="text/javascript">
$(document).ready(function() {

	$('.exportStatisticFeeReports').click(function() {
		if ($("#frm_detail #id").val() > 0) {		
			document.frm_detail.action = '<?php echo url_for('@ps_fee_reports_export?id='.$ps_fee_reports->getId())?>';
			$("#action_type").val('statistic');
			document.frm_detail.submit();
	    }
	});

	$('.exportFeeReports').click(function() {
		if ($("#frm_detail #id").val() > 0) {		
			document.frm_detail.action = '<?php echo url_for('@ps_fee_reports_export?id='.$ps_fee_reports->getId())?>';
			$("#action_type").val('notice');
			document.frm_detail.submit();
	    }
	});
	
});
</script>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php include_partial('psFeeReports/flashes') ?>
			
			<div class="jarviswidget " id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2>
					<?php echo __('Payment fee month %%month%%, %%first_name%% %%last_name%%', array('%%first_name%%' => $student->getFirstName(), '%%last_name%%' => $student->getLastName(), '%%month%%' => format_date($receipt->getReceiptDate(), "MM-yyyy"))) ?>
					(<?php if (false !== strtotime($student->getBirthday())) echo format_date($student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($student->getBirthday(),false).'</code>';?>)
					</h2>
				</header>

				<div>
					<div class="widget-body">
						<div class="widget-body-toolbar text-right">
				        	<?php include_partial('psFeeReports/box/_list_action_detail', array('receipt' => $receipt));?>
				        </div>

						<div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
									<?php include_partial('psFeeReports/box/_list_receivable_student', array('receivable_student' => $receivable_student, 'ps_fee_reports' => $ps_fee_reports ,'receivable_at' => $receivable_at, 'balanceAmount' => $balanceAmount)) ?>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
									<?php include_partial('psFeeReports/box/_payment_invoice', array('receipt' => $receipt, 'ps_fee_reports' => $ps_fee_reports, 'form' => $form));?>
								</div>
							</div>
						</div>

						<div class="form-actions">
				        	<?php include_partial('psFeeReports/box/_list_action_detail', array('receipt' => $receipt));?>
				        </div>
					</div>
				</div>

			</div>
		</article>
	</div>
</section>
