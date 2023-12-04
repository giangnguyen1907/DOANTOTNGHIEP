

<?php use_helper('I18N', 'Date')?>
<?php include_partial('psTimesheetSummarys/assets')?>
<style>
.sunday {
	background: #999 !important;
}

.saturday {
	background: #ccc !important;
}
</style>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-grid="false" data-widget-collapsed="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false" data-widget-togglebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-table"></i></span>
					<h2><?php echo __('Statistic timesheet', array(), 'messages') ?></h2>
				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="dt-toolbar">
							<div class="col-xs-12 col-sm-12">
                            <?php include_partial('psTimesheetSummarys/filters_synthetic', array('formFilter' => $formFilter, 'helper' => $helper)) ?>
                            
                          </div>
						</div>
					</div>
				</div>
			</div>
		</article>
        <?php if($list_student){ ?>
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a class="btn btn-default"
				href="<?php echo url_for('@ps_timesheet_summarys_statistic') ?>"
				id="btn-export-growths"><i class="fa-fw fa fa-cloud-download"></i>&nbsp;<?php echo __('Export xls')?></a>
		</article>
       <?php } ?>
    </div>
</section>