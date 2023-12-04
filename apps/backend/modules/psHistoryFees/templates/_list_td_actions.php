<td class="text-center">
  <div class="btn-group">
    <?php if ($sf_user->hasCredential(array(  0 => 'PS_FEE_REPORT_HISTORY',))): ?>
	<?php echo link_to(__('<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( 'Detail', array (), 'sf_admin' ) . '"></i>', array(), 'messages'), 'psHistoryFees/detail?id='.$ps_history_fees->getId(), array()) ?>
	<?php endif; ?>    
  </div>
</td>