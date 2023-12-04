<?php
//echo phpinfo();
/*
$salt  = 'b16850516d3fde60d85709241f43cb87';

$password = 'Ad12&or*D&S69';

echo SHA1($salt.$password);

echo "<br/>".$_SERVER['PHP_AUTH_USER'];*/

function getUrlFileThumbailOfAblumItemFile($url_file, $path_album){
    	
    	// Duong dan den thu muc anh
    	$root_dirname = pathinfo($url_file, PATHINFO_DIRNAME);
    	
    	$url_format = rawurldecode($url_file);
    	
    	$parse_url = parse_url($url_format);
    	
    	$query_file = $parse_url['query'];
    	
    	// Ten file
    	$name_file = basename($parse_url['path']);
    	
    	$url_thumbail = $root_dirname.'/'. rawurlencode($path_album.'/thumbail/').$name_file.'?'.$query_file;
    	
    	return $url_thumbail;
}


//echo (int)file_get_contents('https://www.w3schools.com/php/func_filesystem_file_get_contents.asp');

$url_file = 'https://firebasestorage.googleapis.com/v0/b/kidsschool-8a92c.appspot.com/o/1679091c5a880faf6fb5e6087eb1b2dc%2F201904%2F25%2F95e13e8073b1f7b2554d6c888f535ca1%2F1679091c5a880faf6fb5e6087eb1b2dc_167_4_20190517091214893_0.jpg?alt=media&token=c4804c16-e2a2-493c-9836-916896d2e76d';

echo getUrlFileThumbailOfAblumItemFile($url_file, '1679091c5a880faf6fb5e6087eb1b2dc/201904/25/95e13e8073b1f7b2554d6c888f535ca1');


?>
