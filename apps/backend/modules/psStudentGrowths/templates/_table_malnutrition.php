<style>
//tr.header1.hidden-xs.hidden-sm{display: none;}
@media (min-width: 768px){
section.table_scroll {
  position: relative;
  padding-top: 74px;
  background: #ddd;
}
section.positioned {
  position: absolute;
  top:100px;
  left:100px;
  width:800px;
  box-shadow: 0 0 15px #333;
}
.container_table {
  overflow-y: auto;
  max-height: 500px;
  border-top: 1px solid #eee;
}
table {
  border-spacing: 0;
  width:100%;
}

td + td {
  border-left:1px solid #eee;
}
td, th {
  border-bottom:1px solid #eee;
  background: #fff;
  color: #000;
  padding: 10px 10px;
}
th {
  height: 0;
  line-height: 0;
  padding-top: 0;
  padding-bottom: 0;
  color: transparent;
  border: none;
  white-space: nowrap;
}

th div{
  position: absolute;
  background: transparent;
  color: #000;
  padding: 10px 10px;
  top: 0;
  margin-left: -10px;
  line-height: normal;
  border-left: 1px solid #eee;
}
tr.header1 th div{
  top: 37px;
  border-top: 1px solid #eee;
}
th:first-child div{
  border: none;
}
tr.header1 th:first-child div{
  border-left: 1px solid #eee;
}

}
@media (max-width: 767px){
	tbody>tr>td, tbody>tr>th, tfoot>tr>td, tfoot>tr>th, thead>tr>td,thead>tr>th {
    padding: 8px 10px;border: 1px solid #ddd;}

}

</style>
<section class="table_scroll">
<div class="container_table custom-scroll table-responsive">
	<table id="dt_basic" width="100%">
		<thead>
			<tr class="hidden-md hidden-lg">
				<th rowspan="2" class="text-center">STT</th>
				<th rowspan="2" class="text-center"><?php echo __('Student')?></th>
				<th rowspan="2" class="text-center"><?php echo __('Class')?></th>
				<th rowspan="2" class="text-center"><?php echo __('Month year')?></th>
				<th rowspan="2" class="text-center"><?php echo __('Sex')?></th>
				<th colspan="2" class="text-center"><?php echo __('Height comment')?></th>
				<th colspan="2" class="text-center"><?php echo __('Weight comment')?></th>
				<th colspan="2" class="text-center"><?php echo __('Weights comment')?></th>
				<th colspan="2" class="text-center"><?php echo __('Growth comment')?></th>
			</tr>
			<tr class="hidden-md hidden-lg">
				<th class="text-center"><?php echo __('Lever 1')?></th>
				<th class="text-center"><?php echo __('Lever 2')?></th>
				<th class="text-center"><?php echo __('Lever 1')?></th>
				<th class="text-center"><?php echo __('Lever 2')?></th>
				<th class="text-center"><?php echo __('Lever 1')?></th>
				<th class="text-center"><?php echo __('Lever 2')?></th>
				<th class="text-center"><?php echo __('Chieu cao') ?></th>
				<th class="text-center"><?php echo __('Can nang') ?></th>
			</tr>
			<tr class="header hidden-xs hidden-sm">
				<th rowspan="2" class="text-center">STT
				<div style="padding: 30px 10px;">STT</div></th>
				<th rowspan="2" class="text-center"><?php echo __('Student')?>
				<div style="padding: 30px 10px;"><?php echo __('Student', array(), 'messages') ?></div></th>
				<th rowspan="2" class="text-center"><?php echo __('Class')?>
				<div style="padding: 30px 10px;"><?php echo __('Class', array(), 'messages') ?></div></th>
				<th rowspan="2" class="text-center"><?php echo __('Month year')?>
				<div style="padding: 30px 10px;"><?php echo __('Month year', array(), 'messages') ?></div></th>
				<th rowspan="2" class="text-center"><?php echo __('Sex')?>
				<div style="padding: 30px 10px;"><?php echo __('Sex', array(), 'messages') ?></div></th>
				<th colspan="2" class="text-center" style="width: 200px;"><?php echo __('Height comment')?>
				<div style="width: 200px;"><?php echo __('Height comment', array(), 'messages') ?></div></th>
				<th colspan="2" class="text-center" style="width: 200px;"><?php echo __('Weight comment')?>
				<div style="width: 200px;"><?php echo __('Weight comment', array(), 'messages') ?></div></th>
				<th colspan="2" class="text-center" style="width: 200px;"><?php echo __('Weights comment')?>
				<div style="width: 200px;"><?php echo __('Weights comment', array(), 'messages') ?></div></th>
				<th colspan="2" class="text-center" style="width: 280px;"><?php echo __('Growth comment')?>
				<div style="width: 280px;"><?php echo __('Growth comment', array(), 'messages') ?></div></th>
			</tr>
			<tr class="header1 hidden-xs hidden-sm">
				<th class="text-center" style="width: 100px;"><?php echo __('Lever 1')?>
				<div style="width: 100px;"><?php echo __('Lever 1') ?></div></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Lever 2')?>
				<div style="width: 100px;"><?php echo __('Lever 2') ?></div></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Lever 1')?>
				<div style="width: 100px;"><?php echo __('Lever 1') ?></div></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Lever 2')?>
				<div style="width: 100px;"><?php echo __('Lever 2') ?></div></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Lever 1')?>
				<div style="width: 100px;"><?php echo __('Lever 1') ?></div></th>
				<th class="text-center" style="width: 100px;"><?php echo __('Lever 2')?>
				<div style="width: 100px;"><?php echo __('Lever 2') ?></div></th>
				<th class="text-center" style="width: 140px;"><?php echo __('Chieu cao') ?>
				<div style="width: 140px;"><?php echo __('Chieu cao') ?></div></th>
				<th class="text-center" style="width: 140px;"><?php echo __('Can nang') ?>
				<div style="width: 140px;"><?php echo __('Can nang') ?></div></th>
			</tr>
		</thead>
		
		<tbody id="list_student">
			<?php include_partial('psStudentGrowths/list_student', array('list_student_malnutrition' => $list_student_malnutrition)) ?>
		</tbody>
	</table>
</div>
</section>