<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAlbums/assets') ?>
<style>
.column .hover-shadow:hover {
	cursor: pointer;
}
.column img{width: 100%;}
.col-md-2 .column{margin-bottom: 30px;}
</style>


<script>
 $(document).ready(function() {
 	$('.download_album').click(function() {
 		$('#export_album_id').val(<?php echo $ps_album->getId() ?>); 		        		
 		$('#frm_export_01').submit();
 		return true;
     });
 });
</script>
 
 
<link rel="stylesheet" type="text/css" href="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/fsgal/css/fs-gal.css" />
<form id="ps-form" method="post" action="<?php echo url_for('@ps_album_item_activated')?>">
	<input type="hidden" name="sf_method" value="post" />
	<input type="hidden" name="id" />
</form>
<form id="frm_export_01" action="<?php echo url_for('@ps_albums_archive_download') ?>">
	<input type="hidden" name="export_ps_customer_id" id="export_ps_customer_id">
	<input type="hidden" name="sf_method" value="post" />
	<input type="hidden" name="export_album_id" id="export_album_id">

<?php include_partial('psAlbums/flashes') ?>

<div class="modal-content">
	<div class="modal-header">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-lg fa-fw fa-picture-o" aria-hidden="true"></i> <?php echo __('Album Items: %%title%%', array('%%title%%' =>$ps_album->getTitle()), 'messages') ?></h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
				<?php echo $helper->linkToList2(array('params' => array(), 'class_suffix' => 'list', 'label' => 'Back to list')) ?>
				<button type="button" class="btn btn-default btn-success btn-sm download_album" id="download_album"><i class="fa fa-cloud-download"></i> <?php echo __('Download album')?></button>
			</div>				
		</div>	
	</div>
	<div class="modal-body border-bottom">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div id="ic-loading-<?php echo $ps_album->getId();?>" style="display: none;"><i class="fa fa-spinner fa-2x fa-spin text-success" style="padding: 3px;"></i><?php echo __('Loading...')?></div>
			<div>
				<span id="box-<?php echo $ps_album->getId();?>">
	                <?php echo get_partial('psAlbums/list_field_boolean', array('type' => 'list','value' => $ps_album->getIsActivated(), 'album_id'=> $ps_album->getId())) ?>
	            </span>
				<i class="fa fa-clock-o"></i>
				<?php 
				echo '<span class="date">' . format_date ( $ps_album->getCreatedAt(), "H:mm:ss dd-MM-yyyy" ) . '</span>';
				?>
				&emsp;|&emsp; <i class="fa fa-user"></i>&ensp; <?php echo $ps_album->getUpdatedBy();?>
				&emsp;|&emsp; <?php echo __('Number image').': '?> <span style="color: red"><?php echo count($album_items);?></span>
				&emsp;|&emsp; <?php echo __('Number view').': '?> <span style="color: red"><?php echo $ps_album->getNumberView()?></span>
			</div>
			<div><?php echo __('Ps customer').': '.$ps_album->getSchoolName().' ,&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; '.__('Ps workplace').': '.$ps_album->getWpTitle().' ,&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; '.__('Class').': '.$ps_album->getClassName();?></div>
			<div><?php echo __('Description: %%note%%', array('%%note%%' =>$ps_album->getNote()), 'messages') ?></div>
			</div>
		</div>
	</div>	
	<div class="modal-body">		
		<div class="row">
			<div class="demo-gallery">
	            <ul id="lightgallery" class="list-unstyled row">
	                <?php foreach ($album_items as $ky=> $album_item) {
	                	$url_file = ($album_item->getUrlFile ()) ? ($album_item->getUrlFile ()) : ($album_item->getUrlThumbnail ());
	                	?>
	                
	                <li class="col-md-2 col-sm-3 col-xs-12" photo-id="<?php echo $album_item->getId()?>" data-responsive="<?php echo $url_file;?>" data-src="<?php echo $url_file;?>" data-sub-html="<?php echo $album_item->getCreatedAt()?>">	                    
	                    <div id="box-status-<?php echo $album_item->getId(); ?>" style="margin-bottom: 5px;">
						<?php include_partial('psAlbums/box_status_2', array('a' => $album_item)); ?>							
			 			</div>
	                    <div class="column">
						  <div class="ps-album-thumb hover-shadow fs-gal" data-url="<?php echo $url_file;?>"  id="item-<?php echo $album_item->getId(); ?>" style="background-image: url(<?php echo $url_file;?>);"></div>
							<br/>
							<p><i class="fa fa-user"></i>&ensp; <?php echo $album_item->getUpdatedBy();?></p>
							<p><i class="fa fa-clock-o"></i> <?php echo get_partial('global/field_custom/_field_format_datetime', array('value' => $album_item->getUpdatedAt())) ?></p>
						</div>
						<img class="photo hidden" id="photo_image<?php echo $album_item->getId()?>"  index="<?php echo $album_item->getId()?>" src="<?php echo $url_file;?>">
	                </li>
	                <?php }?>
	            </ul>
        	</div>
		</div>
	</div>
	<div class="fs-gal-view">
         <h1></h1>
         <img class="fs-gal-prev fs-gal-nav" src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/fsgal/img/prev.svg" alt="Previous picture" title="Previous picture" />
         <img class="fs-gal-next fs-gal-nav" src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/fsgal/img/next.svg" alt="Next picture" title="Next picture" />
         <img class="fs-gal-close" src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/fsgal/img/close.svg" alt="Close gallery" title="Close gallery" />
         <img class="fs-gal-main" src="" alt="" />
    </div>

    
    <script type="text/javascript" src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/fsgal/js/fs-gal.js"></script>
	
	<div class="modal-footer" style="padding: 15px;">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
				<?php echo $helper->linkToList2(array('params' => array(), 'class_suffix' => 'list', 'label' => 'Back to list')) ?>
				<button type="button" class="btn btn-default btn-success btn-sm download_album" id="download_album"><i class="fa fa-cloud-download"></i> <?php echo __('Download album')?></button>
			</div>
		</div>
	</div>
</div>
</form