<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psApp/assets') ?>

<div id="sf_admin_container">
	<h1><?php echo __('Application list', array(), 'messages') ?></h1>

  <?php include_partial('psApp/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('psApp/list_header', array('pager' => $pager)) ?>
  </div>

	<div id="sf_admin_bar">
    <?php include_partial('psApp/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div>

	<div id="sf_admin_content">
    <?php include_partial('psApp/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('psApp/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('psApp/list_actions', array('helper' => $helper)) ?>
    </ul>
	</div>

	<div id="sf_admin_footer">
    <?php include_partial('psApp/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
