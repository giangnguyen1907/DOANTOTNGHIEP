<fieldset
	id="sf_fieldset_<?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?>">
  <?php if ('NONE' != $fieldset): ?>
    <legend><?php echo __($fieldset, array(), 'messages') ?></legend>
  <?php endif; ?>

  <?php $index = 1;?>

  <?php foreach ($fields as $name => $field): ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>

    <?php if($index%2 != 0):?>
    <div class="row">
    <?php endif; ?>

    <?php

include_partial ( 'psEvaluateSemester/form_field', array (
					'name' => $name,
					'attributes' => $field->getConfig ( 'attributes', array () ),
					'label' => $field->getConfig ( 'label' ),
					'help' => $field->getConfig ( 'help' ),
					'form' => $form,
					'field' => $field,
					'class' => 'sf_admin_form_row sf_admin_' . strtolower ( $field->getType () ) . ' sf_admin_form_field_' . $name ) )?>

    <?php if($index%2 == 0):?>
    </div>
    <?php endif; $index++;?>

  <?php endforeach; ?>
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<a
			class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left"
			id="preview" href="javascript:;"> <i class="fa-fw fa fa-eye"
			title="<?php echo __('Preview')?>"></i>
    <?php echo __('Preview')?>
</a>
	</div>
	<script>
$(document).ready(function() {
	$('#preview').click(function() {
		var url = $('#ps_evaluate_semester_url_file').val();
		if(url == ''){
			alert('Vui lòng nhập đường dẫn để xem trước file')
		}else{
			window.open(url,'_blank');
		}
	});
});
</script>
</fieldset>
