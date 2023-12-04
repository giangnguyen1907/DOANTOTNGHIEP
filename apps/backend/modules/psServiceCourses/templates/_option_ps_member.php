

<?php
echo '<option selected="selected" value=""></option>';
foreach ( $ps_member as $key => $_ps_member ) {
	echo $key;
	echo '<option value="' . $_ps_member->getId () . '" >' . $_ps_member->getTitle () . '</option>';
}
?>
