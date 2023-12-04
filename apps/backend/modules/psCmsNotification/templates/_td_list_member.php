<?php
$class_id = $class->getId ();
// lay ra danh sach giao vien trong lop
$list_member = Doctrine::getTable ( 'PsMember' )->getTeachersInClass ( $class_id );
// $list_member = Doctrine::getTable('sfGuardUser')->getUserIdTeacherByClassId($class_id);
// echo count($list_member);
?>
<div class="form-group1">
	<div class="">
		<div class=""
			id="block_student_service_<?php echo $class_id;?>">
		<?php foreach ($list_member as $key => $member): ?>
			<div class="checkbox">
				<label> <input
					class="checkbox _check_all _check_teacher _check_all_<?php echo $class_id;?>"
					type="checkbox" name="teacher_class[]"
					value="<?php echo $member->getUserId();?>"
					data-value="<?php echo $class_id;?>" style="display: none;" /> <span><?php echo $member->getTitle()?></span>
				</label>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
</div>
