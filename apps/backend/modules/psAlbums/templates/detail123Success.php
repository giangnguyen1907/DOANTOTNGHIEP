<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAlbums/assets') ?>
<style>
.column .hover-shadow:hover {
	cursor: pointer;
}
.col-md-2 .column{margin-bottom: 30px;}
</style>
<form id="ps-form" method="post" action="<?php echo url_for('@ps_album_item_activated')?>">
	<input type="hidden" name="sf_method" value="posst" />
	<input type="hidden" name="id" />
</form>
<script>
        function lightbox(idx) {
            //show the slider's wrapper: this is required when the transitionType has been set to "slide" in the ninja-slider.js
            var ninjaSldr = document.getElementById("ninja-slider");
            ninjaSldr.parentNode.style.display = "block";

            nslider.init(idx);

            var fsBtn = document.getElementById("fsBtn");
            fsBtn.click();
        }

        function fsIconClick(isFullscreen, ninjaSldr) { //fsIconClick is the default event handler of the fullscreen button
            if (isFullscreen) {
                ninjaSldr.parentNode.style.display = "none";
            }
        }

        $(document).ready(function() {
        	$('#download_album').click(function() {
        		$('#export_ps_customer_id').val(<?php echo $ps_album->getPsCustomerId() ?>);
        		$('#export_album_id').val(<?php echo $ps_album->getId() ?>);        		
        		$('#frm_export_01').submit();
        		return true;
            });
        });
        
    </script>
<form id="frm_export_01" action="<?php echo url_for('@ps_albums_archive_download') ?>">
	<input type="hidden" name="export_ps_customer_id" id="export_ps_customer_id">
	<input type="hidden" name="export_album_id" id="export_album_id">
<div style="display: none;">
	<div id="ninja-slider">
		<div class="slider-inner">
			<ul>
            <?php foreach ($album_items as $item){?>
            <?php $url = ($item->getUrlFile ()) ? ($item->getUrlFile ()) : ($item->getUrlThumbnail ());?>
                <li><a class="ns-img" href="<?php echo $url; ?>"></a></li>
             <?php }?>
            </ul>
			<div id="fsBtn" class="fs-icon" title="Expand/Close"></div>
		</div>
	</div>
</div>

<div class="modal-content">
	<div class="modal-header">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-lg fa-fw fa-picture-o" aria-hidden="true"></i> <?php echo __('Album Items: %%title%%', array('%%title%%' =>$ps_album->getTitle()), 'messages') ?></h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
				<?php echo $helper->linkToList2(array('params' => array(), 'class_suffix' => 'list', 'label' => 'Back to list')) ?>
				<button type="button" class="btn btn-default btn-success btn-sm" id="download_album"><i class="fa fa-cloud-download"></i> <?php echo __('Download album')?></button>
			</div>				
		</div>	
	</div>
	<div class="modal-body border-bottom">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div>
				<i class="fa fa-clock-o"></i> <?php echo get_partial('global/field_custom/_field_format_datetime', array('value' => $ps_album->getCreatedAt())) ?>
				&emsp;|&emsp; <i class="fa fa-user"></i>&ensp; <?php echo $ps_album->getUpdatedBy();?>
				&emsp;|&emsp; <?php echo __('Number image').': '?> <span style="color: red"><?php echo count($album_items);?></span>
				&emsp;|&emsp; <?php echo __('Number view').': '?> <span style="color: red"><?php echo $ps_album->getNumberView()?></span>				
			</div>
			<div><?php echo __('Ps customer').': '.$ps_album->getSchoolName().' ,&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; '; ?> <?php echo __('Ps workplace').': '.$ps_album->getWpTitle()?></div>
			<div><?php echo __('Description: %%note%%', array('%%note%%' =>$ps_album->getNote()), 'messages') ?></div>
			</div>
		</div>
	</div>	
	<div class="modal-body">		
		<div class="row">
			<div class="gallery">
			<?php foreach ($album_items as $ky=> $album_item) {
				$url_file = ($album_item->getUrlFile ()) ? ($album_item->getUrlFile ()) : ($album_item->getUrlThumbnail ());
			?>
			<div class="col-md-2 col-sm-3 col-xs-12">
				<div id="box-status-<?php echo $album_item->getId(); ?>"
					style="margin-bottom: 5px;">
					<?php include_partial('psAlbums/box_status_2', array('a' => $album_item)); ?>
					<!--  -->
					<div class="btn-group" style="float: right;">
						<a class="btn btn-default" target="_blank" href="<?php echo $url_file;?>" style="padding: 3px 9px;" download><i class="fa fa-cloud-download"></i> <?php echo __('Download image')?></a>
					</div>
	 			</div>
	 			<div class="column">
					<div class="ps-album-thumb hover-shadow" id="item-<?php echo $album_item->getId(); ?>" style="background-image: url(<?php echo $url_file;?>);" onclick="lightbox(<?php echo $ky;?>)"></div>
				</div>
			</div>
			<?php }?>							
			</div>
		</div>
	</div>
	<div class="modal-footer" style="padding: 15px;">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
				<?php echo $helper->linkToList2(array('params' => array(), 'class_suffix' => 'list', 'label' => 'Back to list')) ?>
				<button type="button" class="btn btn-default btn-success btn-sm" id="download_album"><i class="fa fa-cloud-download"></i> <?php echo __('Download album')?></button>
			</div>
		</div>
	</div>
</div>
</form>
<?php foreach ($album_items as $ky=> $album_item) {
					$url_file = ($album_item->getUrlFile ()) ? ($album_item->getUrlFile ()) : ($album_item->getUrlThumbnail ());
				?>
				<div class="col-md-2 col-sm-3 col-xs-12" data-src="<?php echo $url_file;?>">
					<div id="box-status-<?php echo $album_item->getId(); ?>"
						style="margin-bottom: 5px;">
						<?php include_partial('psAlbums/box_status_2', array('a' => $album_item)); ?>
						<!--  -->
						<div class="btn-group" style="float: right;">
							<a class="btn btn-default" target="_blank" href="<?php echo $url_file;?>" style="padding: 3px 9px;" download><i class="fa fa-cloud-download"></i> <?php echo __('Download image')?></a>
						</div>
		 			</div>
					<div class="column">
						<div class="ps-album-thumb hover-shadow" id="item-<?php echo $album_item->getId(); ?>" style="background-image: url(<?php echo $url_file;?>);"></div>
					</div>
				</div>	
				<?php }?>