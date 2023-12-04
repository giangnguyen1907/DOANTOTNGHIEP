<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psMenus/assets') ?>
<?php include_partial('global/include/_box_modal_warning');?>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
      <?php include_partial('psMenus/flashes') ?>

      <div class="jarviswidget" id="wid-id-0"
				data-widget-editbutton="false" data-widget-colorbutton="false"
				data-widget-togglebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"><i class="fa fa-pencil-square-o"></i></span>
					<h2><?php echo __('Menus', array(), 'messages') ?></h2>
				</header>
				<div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							<div class="jarviswidget" id="wid-id-1"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-editbutton="false" data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false" data-widget-collapsed="false"
								data-widget-sortable="false">
								<header>
							<?php if ($form->isNew()):?>
                            <h2><?php echo __('New PsMenus', array(), 'messages') ?></h2>
                            <?php else:?>
                            <h2><?php echo __('Edit PsMenus', array(), 'messages') ?></h2>
                            <?php endif;?>
                            </header>
								<div>	
				        	<?php include_partial('psMenus/form', array('ps_menus' => $ps_menus, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
			        	</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
							<div class="jarviswidget" id="wid-id-2"
								data-widget-editbutton="false" data-widget-colorbutton="false"
								data-widget-editbutton="false" data-widget-togglebutton="false"
								data-widget-deletebutton="false"
								data-widget-fullscreenbutton="false"
								data-widget-custombutton="false" data-widget-collapsed="false"
								data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-calendar"></i>
									</span>
									<h2><?php echo __('Menu of the week', array(), 'messages') ?></h2>
								</header>				
						<?php include_partial('psMenus/week_menu', array('list_meal' => $list_meal, 'list_menu'=>$list_menu, 'week_list' => $week_list, 'width_th' => (100 / (count($week_list) + 1)),'formFilter' => $formFilter, 'form' => $form, 'ps_menus' => $ps_menus));?>
					</div>
						</div>

					</div>
				</div>
			</div>
		</article>
	</div>
</section>