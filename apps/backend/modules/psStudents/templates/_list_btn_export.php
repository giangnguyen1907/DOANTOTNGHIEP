<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
	<div class="btn-group display-inline pull-right text-align-left">
		<button
			class="btn btn-sm btn-default btn-success txt-color-white dropdown-toggle"
			data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-caret-down fa-lg"></i> <?php echo __('Export data')?> 
		</button>
		<ul class="dropdown-menu dropdown-menu-sm pull-right">
			<li><a href="javascript:void(0);" id="btn-export-class"><i
					class="fa fa-file fa-lg fa-fw txt-color-greenLight" data-class=""></i><?php echo __('Export student data by class')?></a></li>

			<li><a href="javascript:void(0);" id="btn-export-workplace"><i
					class="fa fa-file fa-lg fa-fw txt-color-greenLight"
					data-workplace="" data-schoolyear=""></i> <?php echo __('Export student data by workplace')?></a></li>

			<li><a href="javascript:void(0);" id="btn-export-relavtive-class"><i
					class="fa fa-file fa-lg fa-fw txt-color-greenLight" data-class=""></i> <?php echo __('Export student with relatives data by class')?></a></li>

			<li><a href="javascript:void(0);" id="btn-export-relavtive-workplace"><i
					class="fa fa-file fa-lg fa-fw txt-color-greenLight"
					data-workplace="" data-schoolyear=""></i> <?php echo __('Export student with relatives data by workplace')?></a></li>

			<li><a href="javascript:void(0);"
				id="btn-export-student-statistic-workplace"><i
					class="fa fa-file fa-lg fa-fw txt-color-greenLight"
					data-workplace="" data-schoolyear=""></i> <?php echo __('Export student statistics by workplace')?></a></li>

			<li><a href="javascript:void(0);"
				id="btn-export-student-statistic-customer"><i
					class="fa fa-file fa-lg fa-fw txt-color-greenLight"
					data-customer="" data-schoolyear=""></i> <?php echo __('Export student statistics by customer')?></a></li>
		</ul>
	</div>
</div>