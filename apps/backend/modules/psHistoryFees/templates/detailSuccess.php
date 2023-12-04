<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psHistoryFees/assets') ?>

<section id="widget-grid">
  <div class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <?php include_partial('psHistoryFees/flashes') ?>

    <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false">
        <header><span class="widget-icon"><i class="fa fa-eye"></i></span>
        <h2><?php echo __("Detail ". $this->getModuleName()) ?></h2>
        </header>

        <div id="sf_admin_header" class="no-margin no-padding no-border">
          <?php include_partial('psHistoryFees/form_header', array('ps_history_fees' => $ps_history_fees, 'configuration' => $configuration)) ?>
        </div>

        <div id="sf_admin_content">
          <div class="sf_admin_form widget-body">
         
          XXXXXXXXXXX
          <?php include_partial('psHistoryFees/form_actions', array('ps_history_fees' => $ps_history_fees, 'configuration' => $configuration, 'helper' => $helper)) ?>
          
          </div>
        </div>

        <div id="sf_admin_footer" class="no-border no-padding">
          <?php include_partial('psHistoryFees/form_footer', array('ps_history_fees' => $ps_history_fees, 'configuration' => $configuration)) ?>
        </div>
      </div>
    </article>
  </div>
</div>