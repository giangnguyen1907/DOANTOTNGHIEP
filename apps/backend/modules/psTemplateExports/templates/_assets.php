<?php $app_upload_max_size = (int)sfConfig::get('app_upload_max_size');?>
<script type="text/javascript">
var msg_file_invalid 	= '<?php

echo __ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
		'%value%' => $app_upload_max_size * 5 ) )?>';
var PsMaxSizeFile = '<?php echo $app_upload_max_size;?>';
</script>