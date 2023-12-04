<th class="sf_admin_text sf_admin_list_th_ps_year_month" style="max-width: 70px!important;" >
  <?php if ('ps_year_month' == $sort[0]): ?>
    <?php echo link_to(__('Ps year month', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=ps_year_month&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Ps year month', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=ps_year_month&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<th class="sf_admin_text sf_admin_list_th_title" >
  <?php if ('title' == $sort[0]): ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=title&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Title', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=title&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<th class="sf_admin_boolean sf_admin_list_th_is_public" >
  <?php if ('is_public' == $sort[0]): ?>
    <?php echo link_to(__('Is public', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=is_public&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Is public', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=is_public&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<th class="sf_admin_text sf_admin_list_th_number_push_notication" >
  <?php if ('number_push_notication' == $sort[0]): ?>
    <?php echo link_to(__('Number push notication', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=number_push_notication&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Number push notication', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=number_push_notication&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<th class="sf_admin_date sf_admin_list_th_updated_at" >
  <?php if ('updated_at' == $sort[0]): ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=updated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo '<i class="fa fa-chevron-'.($sort[1] == 'asc' ? 'down' : 'up').' txt-color-greenDark" aria-hidden="true"></i>' ?>
	
  <?php else: ?>
    <?php echo link_to(__('Updated by', array(), 'messages'), '@ps_fee_news_letters', array('query_string' => 'sort=updated_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>