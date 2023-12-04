
<?php if(myUser::credentialPsCustomers('PS_EVALUATE_INDEX_STUDENT_EDIT')):?>
	<?php //print_r($evaluate_cell); die;?>
<label class="select" style="width: 100%"> <select class="form-control"
	style="width: 100%;" name="evaluate-cell"
	id="criteria-<?php echo $evaluate_cell['criteria_id'] ?>-cell-<?php echo $key ?>"
	data-criteria="<?php echo $evaluate_cell['criteria_id'] ?>"
	data-student="<?php echo $evaluate_cell['student_id'] ?>"
	data-symbol="<?php echo $evaluate_cell['symbol_id'] ?>">
		<option selected value=""><?php echo __('-') ?></option>
    		<?php foreach ($symbols  as $symbol) : ?>
    		
    		<?php if($symbol->getId() == $evaluate_cell['symbol_id']):?>
    		<option selected value="<?php echo $symbol->getId(); ?>"><?php echo $symbol->getSymbolCode(); ?></option>
    		<?php else: ?>
    		<option value="<?php echo $symbol->getId(); ?>"><?php echo $symbol->getSymbolCode(); ?></option>
    		<?php endif;?>
    		<?php endforeach;?>
    </select>
</label>
<?php else: ?>
	<?php //if($evaluate_cell['is_public'] > 0 ):?>
<label class=""><?php echo $evaluate_cell['symbol_code']?></label>
<?php //endif; ?>
<?php endif;?>

