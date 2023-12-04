<?php $array_symbol_id = array();?>
<table id="dt_basic" class="table table-striped table-bordered table-hover no-footer no-padding" width="100%">
	<thead>
		<tr>
			<th rowspan="2" class="text-center"><?php echo __('STT') ?></th>
			<th rowspan="2" class="text-center"><?php echo __('Student') ?></th>
			<?php for ($j=1;$j<=3;$j++){?>
				<?php if($j == 3){
					foreach ($list_symbols as $symbols){
						array_push($array_symbol_id,$symbols->getId());
					}
					?>
				<th colspan="<?php echo count($list_symbols)?>" class="text-center"><?php echo __("Semester all"); ?></th>
				<?php }else{?>
				<th colspan="<?php echo count($list_symbols)?>" class="text-center"><?php echo __("Semester").$j; ?></th>
				<?php }?>
			<?php }?>
       </tr>
       <tr>
			<?php for ($i=0;$i<=2;$i++){?>
				<?php foreach ($list_symbols as $symbols){ ?>
					<th class="text-center"><?php echo $symbols->getTitle(); ?></th>
				<?php }?>
			<?php }?>
       </tr>
	</thead>

	<tbody>
          <?php foreach ($list_student as $key=>$student){?>
          <tr>
          	<td class="text-center"><?php echo $key+1?></td>
            <td><?php echo $student->getStudentName()?><br/>
            <code><?php echo $student->getStudentCode()?></code></td>
            <?php for ($k=1;$k<=3;$k++){?>
            	<?php foreach ($array_symbol_id as $symbol_id){?>
            	<td class="text-center">
            		<?php foreach ($getDataEvaluate as $evaluate){
            			if($student->getId() == $evaluate->getStudentId() && $evaluate->getSymbolId() == $symbol_id && $evaluate->getPsSemester() == $k){
	            			echo $evaluate->getNumber(); 
	            		}
            		}?>
            	</td>
            	<?php }?>
            <?php }?>
         </tr>
         <?php }?>
    </tbody>
</table>