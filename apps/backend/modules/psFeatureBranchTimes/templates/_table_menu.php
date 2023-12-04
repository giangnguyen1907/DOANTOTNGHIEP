<?php use_helper('I18N', 'Date') ?>
<?php
$edit_ps_menu_id = (isset ( $ps_menus ) && $ps_menus->getId () > 0) ? $ps_menus->getId () : null;
?>

<?php if ($formFilter->hasGlobalErrors()): ?>
	<?php echo $formFilter->renderGlobalErrors()?>
	<?php endif;?>
	
	<?php echo $formFilter->renderHiddenFields() ?>
<table id="tbl-List-User" class="table table-bordered table-striped">
	<input type="hidden" id="count_list_menus"
		value="<?php echo count($list_menu)?>">
	<thead>
		<tr>
			<?php foreach ($week_list as $date => $monday):?>
			
			<th class="text-center <?php if (date('N', strtotime($date)) == 6) echo 'bg-color-yellow'; elseif (date('N', strtotime($date)) == 7) echo 'bg-color-pink';?>" style="width: <?php echo $width_th?>%;"><b><?php echo __($monday)?><br>
					<div class="date"><?php echo format_date($date, "dd-MM-yyyy");?></div></b></th>
			<?php endforeach;?>
		</tr>
	</thead>

	<tbody> 
		<?php foreach ($list_menu as $key => $fbtimes):?>
		
		<?php $classActivities = Doctrine::getTable('PsFeatureBranchTimeMyClass')->getBasicInfoByPsFeatureBranchTimeId($fbtimes->getId());?>
		<?php $str = null; ?>
		<?php foreach ($classActivities as $activities):?>
		<?php
				$str .= $activities->getClassName () . ': &emsp;';
				// $str.= $activities->getPsClassRoom() . '&emsp;' ;
				$str .= $activities->getNote ();
				$str .= "<br>";
				?>
		<?php endforeach;?>
		
		<?php
			// Xu ly chuoi khong qua 200 ky tu
			$myString = substr ( $str, 0, 200 );

			$lastIndex = strripos ( $myString, ' ' );

			// $str = substr($myString, 0, $lastIndex);
			?>
		<tr>
			<?php foreach ($week_list as $date => $monday): ?> 
			
			<td>
				<ul class="media-list" style="list-style-type: none;">
					<?php
				// Xac dinh lich hoat dong co vao thu 7 hoac chu nhat khong
				if ($date >= $fbtimes->getStartAt () && $date <= $fbtimes->getEndAt ()) :
					if ($fbtimes->getIsSaturday () == 0 && date ( 'N', strtotime ( $date ) ) == 6) :
						continue;
					 elseif ($fbtimes->getIsSunday () == 0 && date ( 'N', strtotime ( $date ) ) == 7) :
						continue;
					endif;
					?>
					<p><?php echo $fbtimes->getFbName()?>
						<a class="btn btn-default btn-xs txt-color-blueLight"
							rel="popover-hover" data-placement="bottom"
							data-original-title="<?php echo $fbtimes->getFbName() . '&emsp;' . format_date($fbtimes->getStartTime(), "HH:mm") . '&rarr;' . format_date($fbtimes->getEndTime(), "HH:mm") ?>"
							data-content="<?php if($str) echo $str; else echo $fbtimes->getNote(); ?>"
							data-html="true"><i class="fa fa-gear"></i></a>
					</p>
					<p><?php echo format_date($fbtimes->getStartTime(), "HH:mm") ?> &rarr; <?php echo format_date($fbtimes->getEndTime(), "HH:mm") ?></p>
					
					<?php endif;?>
				</ul>
			</td>
			<?php endforeach;?>			
		</tr>
		<?php endforeach;?>
	</tbody>
</table>

<script type="text/javascript">
$(document).ready(function() {

	if($('#menus_filter_school_year_id').val()>0){
		$('#menus_filter_school_year_id').trigger('change');
	}
	$.datepicker.setDefaults($.datepicker.regional[ "vi" ]);
	
	// START AND FINISH DATE	
	$('#menus_filter_date_at').datepicker({	
		prevText : '<i class="fa fa-chevron-left"></i>',
	    nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy'
	}).on('changeDate', function(e) {
	     $('#psnew-filter').formValidation('revalidateField', 'menus_filter_date_at');
	});

	$('[rel="popover-hover"]').popover({trigger: "hover"});  

});
</script>