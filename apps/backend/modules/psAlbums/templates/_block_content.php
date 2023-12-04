
<div class="col-md-2 col-lg-2 col-sm-4 col-xs-12">
	<div class="ps_albums_intro">
		<div class="superbox">
    		<?php
						//$items = Doctrine::getTable ( 'PsAlbumItems' )->findOneByAlbumId ( $ps_albums->getId () );
			    		$items_thumb = ($ps_albums) ? ($ps_albums->getUrlThumbnail ()) : '';
			    		$items_origin = ($ps_albums) ? ($ps_albums->getUrlFile ()) : '';

						$title = $ps_albums->getTitle () ? $ps_albums->getTitle () : '';
						$note = $ps_albums->getNote () ? $ps_albums->getNote () : '';
						$number_img = $ps_albums->getNumberImg () ? $ps_albums->getNumberImg () : 0;
						$created_by = $ps_albums->getCreatorBy () ? $ps_albums->getCreatorBy () : '';
						$created_at = $ps_albums->getCreatedAt () ? $ps_albums->getCreatedAt () : '';
						$is_activated = $ps_albums->getIsActivated ();
						$view = $ps_albums->getNumberView ();
						$like = $ps_albums->getNumberLike ();

						if ($items_origin != '')
							$url_thumbail_file = $items_origin;
						else
							$url_thumbail_file = $items_thumb;

						?>
    		<div style="width: 100%;">
				<a href="<?php echo url_for('@ps_albums_detail?id='.$ps_albums->getId())?>">
					<div class="ps-album-thumb img-response" style="background-image: url(<?php echo $url_thumbail_file;?>);"></div>
				</a>
			</div>
			<div class="caption padding-top-10" style="line-height: 1.7em;">

				<div><?php echo __('Number view');?>: <span style="color: red;"><?php echo $view ?></span>
					| <span style="color: red;"><?php echo $number_img;?></span> <?php echo __('picture');?></div>
				<div>
					<i class="fa fa-user"></i> <?php echo $created_by;?></div>
				
				<div>
					<i class="fa fa-building"></i>
					<a class=""rel="popover-hover" data-placement="bottom"
					data-original-title="<?php echo __('Infomation') ?>"
					data-content="<?php echo __('Ps customer').': '. $ps_albums->getSchoolName().'<br/>'.__('Ps workplace').': '.$ps_albums->getWpTitle().'<br/>'.__('Class').': '.$ps_albums->getMcName() ?>"
					data-html="true"><?php echo $ps_albums->getMcName();?></a>
				</div>
				<div>
					<i class="fa fa-clock-o"></i> <i><?php echo get_partial('global/field_custom/_field_format_datetime', array('value' => $created_at)) ?></i>
				</div>

				<div id="ic-loading-<?php echo $ps_albums->getId();?>"
					style="display: none;">
					<i class="fa fa-spinner fa-2x fa-spin text-success"
						style="padding: 3px;"></i><?php echo __('Loading...')?>
                </div>

				<div style="float: left;">
					<span style="float: left;"
						id="box-<?php echo $ps_albums->getId() ?>">
	                	<?php echo get_partial('psAlbums/list_field_boolean', array('type' => 'list','value' => $is_activated, 'album_id'=> $ps_albums->getId())) ?>
	                </span>
	                <?php include_partial('psAlbums/list_td_actions', array('ps_albums' => $ps_albums, 'helper' => $helper)) ?>                
                </div>
				<div style="width: 100%; display: inline-block; margin-top: 10px;">
					<p>
						<a href="<?php echo url_for('@ps_albums_detail?id='.$ps_albums->getId())?>">
						<h6 style="margin-bottom: 5px; overflow-wrap: break-word; font-weight: bolder;"><?php echo $title ?></h6></a>
					</p>
					<p class="custom-scroll table-responsive"
						style="margin-bottom: 10px; word-break: break-all; white-space: pre-line;max-height: 150px;overflow-y: scroll;">
						<i style="font-weight: bold"><?php echo __('Note') . ': '?></i><?php echo $note?></p>
				</div>
			</div>
		</div>
	</div>
</div>
