<td class="sf_admin_text sf_admin_list_td_ps_year_month text-center">
  <?php echo date('m-Y', strtotime($ps_fee_news_letters->getPsYearMonth().'01')); ?>
</td>
<td class="sf_admin_text sf_admin_list_td_title">
  <?php echo $ps_fee_news_letters->getTitle() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_public text-center">
    <div id="status-loading-<?php echo $ps_fee_news_letters->getId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div>
  	
  	<span class="onoffswitch fee_news_letters_status" id="fee_news_letters_status-<?php echo $ps_fee_news_letters->getId() ?>" value="<?php echo $ps_fee_news_letters->getId() ?>">
  		<?php echo get_partial('psFeeNewsLetters/list_field_boolean', array('ps_fee_news_letters' => $ps_fee_news_letters)) ?>
  	</span>
  	
</td>
<td class="sf_admin_text sf_admin_list_td_number_push_notication text-center">
  	<div id="ic-loading-<?php echo $ps_fee_news_letters->getId();?>"
		style="display: none;">
		<i class="fa fa-spinner fa-2x fa-spin text-success"
			style="padding: 3px;"></i><?php echo __('Loading...')?>
    </div> 
    <a class="push_notication" id="push_notication-<?php echo $ps_fee_news_letters->getId() ?>"
	href="javascript:;" value="<?php echo $ps_fee_news_letters->getId() ?>" data-value = "<?php echo $ps_fee_news_letters->getIsPublic();?>"> 
		<div class="btn-group">
			<button btnradio="Left" class="btn btn-default ng-untouched ng-pristine ng-valid" type="button" id="box-<?php echo $ps_fee_news_letters->getId() ?>">
				<?php echo get_partial('psFeeNewsLetters/load_number_notication', array('ps_fee_news_letters' => $ps_fee_news_letters))?>
			</button>
			<button btnradio="Justify" class="btn btn-default ng-untouched ng-pristine ng-valid ps-bell" type="button" ><i class="fa fa-bell"></i> <?php echo __('Push notication')?></button>
		</div>
	</a>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo $ps_fee_news_letters->getUpdatedBy() . '<br/>';
   echo false !== strtotime($ps_fee_news_letters->getUpdatedAt()) ? format_date($ps_fee_news_letters->getUpdatedAt(), "HH:mm dd-MM-yyyy") : '&nbsp;' ?>
</td>
