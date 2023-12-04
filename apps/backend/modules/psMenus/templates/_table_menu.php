<?php use_helper('I18N', 'Date') ?>
<?php
$edit_ps_menu_id = (isset ( $ps_menus ) && $ps_menus->getId () > 0) ? $ps_menus->getId () : null;
?>

<?php if ($formFilter->hasGlobalErrors()): ?>
	<?php echo $formFilter->renderGlobalErrors()?>
	<?php endif;?>
	
	<?php echo $formFilter->renderHiddenFields() ?>
<?php if (myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_EDIT' )):?>
<table id="tbl-List-User" class="table table-bordered table-striped">
	<input type="hidden" id="count_list_menus"
		value="<?php echo count($list_menu)?>">
	<thead>
		<tr>
			<th style="width: <?php echo $width_th?>%;">&nbsp;</th>
			<?php foreach ($week_list as $date => $monday):?>
			<th class="text-center <?php if (date('N', strtotime($date)) == 6) echo 'bg-color-yellow'; elseif (date('N', strtotime($date)) == 7) echo 'bg-color-pink';?>" style="width: <?php echo $width_th?>%;"><b><?php echo __($monday)?><br>
					<div class="date"><?php echo format_date($date, "dd-MM-yyyy");?></div></b></th>
			<?php endforeach;?>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($list_meal as $meal):?>
		<tr>
			<td class="text-center"><?php echo $meal->getTitle();?></td>
			<?php foreach ($week_list as $date => $monday):?>
			<td>
				<ul class="media-list" style="list-style-type: none;">
					<?php foreach ($list_menu as $menu):?>
					
					<?php if ($menu->getMealId() == $meal->getId() && date('Y-m-d', strtotime($menu->getDateAt())) == $date)  :?>
					<li
						class="media <?php if ($edit_ps_menu_id == $menu->getId()) echo 'bg-color-orange txt-color-white';?>">					
						<?php
					$image_file = $menu->getFileName ();
					
					if($menu->getFileImage() != ''){
						echo '<span class="pull-left">' . image_tag ( '/uploads/ps_nutrition/thumb/' . $menu->getFileImage(), array (
								'style' => 'max-width:30px;text-align:center;',
								'class' => 'media-object' ) ) . '</span>';
					}elseif ($image_file != '') {
						echo '<span class="pull-left">' . image_tag ( '/sys_icon/' . $image_file, array (
								'style' => 'max-width:30px;text-align:center;',
								'class' => 'media-object' ) ) . '</span>';
					}
					?>
					
						<div class="media-body">
							<a
								href="<?php echo url_for('@ps_menus_edit?id='.$menu->getId())?>"><?php echo $menu->getFoodTitle()?></a>
						</div>
					</li>
					<?php endif;?>
					
					<?php endforeach;?>
				</ul>
			</td>
			<?php endforeach;?>			
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php else:?>
<table id="tbl-List-User" class="table table-bordered table-striped">
	<input type="hidden" id="count_list_menus"
		value="<?php echo count($list_menu)?>">
	<thead>
		<tr>
			<th style="width: <?php echo $width_th?>%;">&nbsp;</th>
			<?php foreach ($week_list as $date => $monday):?>
			<th class="text-center <?php if (date('N', strtotime($date)) == 6) echo 'bg-color-yellow'; elseif (date('N', strtotime($date)) == 7) echo 'bg-color-pink';?>" style="width: <?php echo $width_th?>%;"><b><?php echo __($monday)?><br>
					<div class="date"><?php echo format_date($date, "dd-MM-yyyy");?></div></b></th>
			<?php endforeach;?>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($list_meal as $meal):?>
		<tr>
			<td class="text-center"><?php echo $meal->getTitle();?></td>
			<?php foreach ($week_list as $date => $monday):?>
			<td>
				<ul class="media-list" style="list-style-type: none;">
					<?php foreach ($list_menu as $menu):?>
					
					<?php if ($menu->getMealId() == $meal->getId() && date('Y-m-d', strtotime($menu->getDateAt())) == $date)  :?>
					<li
						class="media <?php if ($edit_ps_menu_id == $menu->getId()) echo 'bg-color-orange txt-color-white';?>">					
						<?php
					$image_file = $menu->getFileName ();
					if ($image_file != '') {
						echo '<span class="pull-left">' . image_tag ( '/sys_icon/' . $image_file, array (
								'style' => 'max-width:30px;text-align:center;',
								'class' => 'media-object' ) ) . '</span>';
					}
					?>
					
						<div class="media-body">
						<?php echo $menu->getFoodTitle()?>
						</div>
					</li>
					<?php endif;?>
					
					<?php endforeach;?>
				</ul>
			</td>
			<?php endforeach;?>			
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php endif;?>
