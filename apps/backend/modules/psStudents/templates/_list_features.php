<?php
$tracked_at = '01-' . $ps_month;
$studentFeature = Doctrine::getTable ( 'StudentFeature' )->getFeatureOptionStudent ( $student_id, $tracked_at );
?>
<?php
$date_feature_tmp = $id_tmp = '';
$text_note = $text_option = '';
$array_date = $array_feature = array ();

foreach ( $studentFeature as $feature ) {

	$date_feature = date ( 'd-m-Y', strtotime ( $feature->getTrackedAt () ) );

	if ($date_feature_tmp == $date_feature) {
		$date_feature = $text_note = '';
		$text_option .= $feature->getFeatureOptionName () . ', ';
		if ($feature->getFeatureNote () != '') {
			$text_note .= $feature->getFeatureNote () . ', ';
		}
	} else {
		$text_option = $feature->getFeatureOptionName () . ', ';
		if ($feature->getFeatureNote () != '') {
			$text_note = $feature->getFeatureNote () . ', ';
		}
	}

	$array_date [date ( 'd-m-Y', strtotime ( $feature->getTrackedAt () ) )] = $text_option . '$#***' . $text_note;

	$date_feature_tmp = date ( 'd-m-Y', strtotime ( $feature->getTrackedAt () ) );
}
?>

<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th class="text-center"><?php echo __('Feature branch name') ?></th>
				<th class="text-center"><?php echo __('Feature date') ?></th>
				<th class="text-center"><?php echo __('Feature option feature') ?></th>
				<th class="text-center"><?php echo __('Feature note') ?></th>
			</tr>
		</thead>

		<tbody>
    	<?php
					$date_feature_tmp = $name_tmp = '';
					$text_option = '';
					foreach ( $studentFeature as $feature ) {
						$name_feature = $feature->getFeatureName ();
						$date_feature = date ( 'd-m-Y', strtotime ( $feature->getTrackedAt () ) );
						if ($name_tmp == $name_feature) {
							$name_feature = '';
						}

						if ($date_feature_tmp == $date_feature) {
							$date_feature = '';
							$text_option .= $feature->getFeatureOptionName () . ', ';
						} else {
							$text_option = $feature->getFeatureOptionName () . ', ';
						}

						?>
    	<?php if($date_feature !=''){ ?>
        <tr>
				<td rowspan=""><?php echo $name_feature;$name_tmp = $feature->getFeatureName();?></td>
				<td class="text-center"><?php echo $date_feature;$date_feature_tmp = date('d-m-Y', strtotime($feature->getTrackedAt()));?></td>
				<td>
        	<?php
							foreach ( $array_date as $key => $option_text ) {
								if ($key == date ( 'd-m-Y', strtotime ( $feature->getTrackedAt () ) )) {
									$note = strstr ( $option_text, '$#***' );
									$dem_chuoi1 = strlen ( $option_text );
									$dem_chuoi2 = strlen ( $note );
									echo $option_fea = substr ( $option_text, 0, $dem_chuoi1 - $dem_chuoi2 );
								}
							}
							?>
        	</td>
				<td><?php echo  $str = str_replace( '$#***', '', $note ) ;?></td>
			</tr>
        <?php } ?>
        <?php }?>
    </tbody>
	</table>

</div>