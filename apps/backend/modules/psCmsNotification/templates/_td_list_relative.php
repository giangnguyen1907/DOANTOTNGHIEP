<?php
$class_id = $class->getId ();
// lay ra danh sach giao vien trong lop
$list_relative = Doctrine::getTable ( 'RelativeStudent' )->getRelativeByClassId ( $class_id );
// echo count($list_member);
?>
<div class="form-group1">
	<div class="row">
		<div class="" id="block_relative">
		<?php foreach ($list_relative as $key => $relative): ?>
			<div class="checkbox col-md-12">
				<label> <input
					class="checkbox _check_all _check_relative _check_all_<?php echo $class_id;?>"
					type="checkbox" name="relative_class[]"
					value="<?php echo $relative->getUserId();?>"
					data-value="<?php echo $class_id;?>" style="display: none;" /> <span><?php echo $relative->getFullName()?> (<?php echo $relative->getRssTitle().': '.$relative->getStudentName()?>)</span>
				</label>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
</div>
