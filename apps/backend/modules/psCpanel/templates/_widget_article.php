<div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false"
	data-widget-colorbutton="false" data-widget-editbutton="false"
	data-widget-togglebutton="false" data-widget-deletebutton="false"
	data-widget-fullscreenbutton="false" data-widget-custombutton="false"
	data-widget-collapsed="false" data-widget-sortable="false">
	<header role="heading">
		<span class="widget-icon"> <i
			class="fa fa-newspaper-o faa-tada animated"></i>
		</span>
		<h2><?php echo __('News')?></h2>
		<ul class="nav nav-tabs pull-right in" id="myTab">
			<li class="active"><a data-toggle="tab" href="#article_base"
				aria-expanded="false"><i
					class="glyphicon glyphicon-link hidden-md hidden-lg"></i> <span
					class="hidden-mobile hidden-tablet"><?php echo __('News base')?></span></a>
			</li>

			<li><a data-toggle="tab" href="#article_sc" aria-expanded="false"><i
					class="glyphicon glyphicon-file hidden-md hidden-lg"></i> <span
					class="hidden-mobile hidden-tablet"><?php echo __('News school')?></span></a>
			</li>

			<li><a data-toggle="tab" href="#article_ks" aria-expanded="true"><i
					class="fa fa-slack hidden-md hidden-lg"></i> <span
					class="hidden-mobile hidden-tablet"><?php echo __('News KidsSchool.vn')?></span></a>
			</li>
		</ul>
	</header>
	<div>
		<div class="widget-body no-padding">
			<div class="table-responsive no-margin custom-scroll"
				style="height: 228px; overflow-y: scroll;">
				<div class="tab-content">
					<div class="tab-pane fade active in padding-5 no-padding-bottom"
						id="article_base">
						<table class="table table-striped table-hover table-condensed">
							<tbody>
							<?php foreach($articles as $article): ?>
							<tr>
									<td style="width: 70%;"><a href="javascript:void(0);"
										rel="popover-hover" data-placement="right"
										data-content="<?php echo $article->getNote();?>">
									<?php echo $article->getTitle();?>
									</a></td>
									<td style="width: 30%;" class="text-right"><?php echo format_date($article->getUpdatedAt(), 'hh:mm - dd.MM.yyyy') ?> </td>
								</tr>
							<?php endforeach ?>
							</tbody>
						</table>
					</div>
					<div class="tab-pane fade in padding-5 no-padding-bottom"
						id="article_sc"></div>
					<div class="tab-pane fade in padding-5 no-padding-bottom"
						id="article_ks"></div>
				</div>
			</div>
		</div>
	</div>
</div>