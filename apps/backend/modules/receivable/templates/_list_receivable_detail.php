<?php

$receivable_id = $form->getObject ()
	->getId ();
$receivable_detail = Doctrine::getTable ( 'ReceivableDetail' )->getAllReceivableDetail ( $receivable_id );
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar text-right">    	
        <?php if ($sf_user->hasCredential(array('PS_FEE_RECEIVABLE_ADD', 'PS_FEE_RECEIVABLE_EDIT'))):?>
        <a rel="tooltip" data-placement="left"
			data-original-title="<?php echo __('Add receipt');?>"
			data-html="true" data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin"
			href="<?php echo url_for('@ps_receivable_detail_new?fbid='.$form->getObject()->getId())?>"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
        <?php endif;?>
	</div>
</div>
<div class="custom-scroll table-responsive">
	<table class="table table-bordered table-hover no-footer no-padding">
		<thead>
			<tr>
				<th class="text-center col-md-3"><?php echo __('Description')?></th>
				<th class="text-center col-md-3"><?php echo __('Amount')?></th>
				<th class="text-center col-md-2"><?php echo __('By number')?></th>
				<th class="text-center col-md-3"><?php echo __('From date')?></th>
				<th class="text-center col-md-3"><?php echo __('To date')?></th>
				<th class="text-center col-md-1"><?php echo __('Action')?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($receivable_detail as $re_detail){ ?>
		<tr>

			<td class="text-center">
				<?php echo $re_detail->getDescription();?>
			</td>
			<td class="text-center">
				<?php echo number_format($re_detail->getAmount(),0,",",".");?>
			</td>
				<td class="text-center">
				<?php echo number_format($re_detail->getByNumber(),0,",",".");?>
			</td>
				<td class="text-center">
				<?php echo false !== strtotime($re_detail->getDetailAt()) ? format_date($re_detail->getDetailAt(), "dd-MM-yyyy") : '&nbsp;' ?>
			</td>
				<td class="text-center">
				<?php echo false !== strtotime($re_detail->getDetailEnd()) ? format_date($re_detail->getDetailEnd(), "dd-MM-yyyy") : '&nbsp;' ?>
			</td>
				<td style="border-left: none;" class="text-center">
					<div class="btn-group">
    				<?php if ($sf_user->hasCredential('PS_FEE_RECEIVABLE_EDIT')):?>
    				<a data-toggle="modal" data-target="#remoteModal"
							data-backdrop="static" class="btn btn-xs btn-default"
							href="<?php echo url_for('@ps_receivable_detail_edit?id='.$re_detail->getId())?>"><i
							class="fa-fw fa fa-pencil txt-color-orange"
							title="<?php echo __('Edit', array())?>"></i></a>
    				<?php endif; ?>
    				<?php if ($sf_user->hasCredential('PS_FEE_RECEIVABLE_DELETE')):?>
    				<a class="btn btn-xs btn-default"
							onclick="if (confirm('<?php echo __('Are you sure?') ?>')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', 'ce8c811c6018ee0a1e446f2e5de3a8ae'); f.appendChild(m);f.submit(); };return false;"
							href="<?php echo url_for(@ps_receivable_detail).'/'.$re_detail->getId(); ?>"><i
							class="fa-fw fa fa-trash-o" title="<?php echo __('Delete')?>"></i></a>
    				<?php endif; ?>
    			</div>
				</td>
			</tr>
		<?php }?>
	</tbody>
	</table>
</div>