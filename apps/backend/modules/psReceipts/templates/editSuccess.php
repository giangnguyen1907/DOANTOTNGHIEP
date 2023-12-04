<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psReceipts/assets') ?>

<section id="widget-grid">
	<!--  sf_admin_container -->
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">      
      <?php include_partial('psReceipts/flashes') ?>      
      <div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psReceipts/form_header', array('receipt' => $receipt, 'form' => $form, 'configuration' => $configuration)) ?>
      </div>
			<div class="jarviswidget " id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Edit PsReceipts: %%receipt_date%%', array('%%receipt_date%%' => false !== strtotime($receipt->getReceiptDate()) ? format_date($receipt->getReceiptDate(), "MM-yyyy") : '&nbsp;'), 'messages') ?>
        <?php echo __('of').' '.$student->getFirstName().' '.$student->getLastName().' <code>'.$student->getStudentCode().'</code> '?>
        <span>
		(<?php if (false !== strtotime($student->getBirthday())) echo format_date($student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($student->getBirthday(),false).'</code>';?>)
		</span>
					</h2>
				</header>

				<div class="row">
					<div class="alert alert-warning fade in"
						style="margin-bottom: 0px;">
						<i class="fa-fw fa fa-warning ps-fa-2x" aria-hidden="true"></i> <?php echo __('You are correct report card fees.')?>
			</div>
				</div>

				<div id="sf_admin_content">
		     <?php include_partial('psReceipts/form', array('receipt' => $receipt, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper));?>		         
	         <?php include_partial('psReceipts/fees/_list_receivable_student_form_fieldset', array('receipt' => $receipt,'receivable_student' => $receivable_student, 'receivable_at' => $receivable_at));?>
	    </div>
				<div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psReceipts/form_footer', array('receipt' => $receipt, 'form' => $form, 'configuration' => $configuration)) ?>
        </div>
			</div>
		</article>
	</div>
</section>