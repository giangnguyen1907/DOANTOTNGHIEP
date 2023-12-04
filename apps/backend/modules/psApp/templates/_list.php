<div class="sf_admin_list">
  <?php if (!$pager->getNbResults()): ?>
    <p><?php echo __('No result', array(), 'sf_admin') ?></p>
  <?php else: ?>
    <table id="dt_basic"
		class="table table-striped table-bordered table-hover no-footer no-padding"
		width="100%">
		<thead>
			<tr>
				<th id="sf_admin_list_batch_actions"><input
					id="sf_admin_list_batch_checkbox" type="checkbox"
					onclick="checkAll();" /></th>
          <?php include_partial('psApp/list_th_tabular', array('sort' => $sort)) ?>
          <th id="sf_admin_list_th_actions"><?php echo __('Actions', array(), 'sf_admin') ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="9">
					<div class="text-results">
          <?php echo __('View %%from_item%% - %%to_item%% /%%nbResults%% results', array('%%from_item%%' => $pager->getFirstIndice(), '%%to_item%%' => $pager->getLastIndice(), '%%nbResults%%' => $pager->getNbResults())) ?>
          </div>
				</th>
			</tr>
		</tfoot>
		<tbody>
        <?php foreach ($pager->getResults() as $i => $ps_app): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
            <?php include_partial('psApp/list_td_batch_actions', array('ps_app' => $ps_app, 'helper' => $helper)) ?>
            <?php include_partial('psApp/list_td_tabular', array('ps_app' => $ps_app)) ?>
            <?php include_partial('psApp/list_td_actions', array('ps_app' => $ps_app, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
	</table>
  <?php endif; ?>
</div>
<script type="text/javascript">
/* <![CDATA[ */
function checkAll()
{
  var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
}
/* ]]> */
</script>
