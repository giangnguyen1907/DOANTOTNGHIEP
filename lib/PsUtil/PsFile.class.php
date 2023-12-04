<?php
/**
 * @project_name:
 * @file_name: PsFile.php
 * @descr: Function used process file
 * 
 * @author thangnc <thangnc@newwaytech.vn>
 * @version 1.0	 2017, thangnc, created
 */
class PsFile {

	/**
	 * check file exits
	 *
	 * @author thangnc@newwaytech.vn
	 * @param : $str_file
	 *        	- String
	 * @return boolean
	 *
	 */
	public static function isCheckFile($str_file) {

		if (file_exists ( $str_file ))
			return true;
		else
			return false;
	}

	/**
	 * delete file
	 *
	 * @author thangnc@newwaytech.vn
	 * @param
	 *        	String - $strFile
	 * @return boolean
	 *
	 */
	public static function deleteFile($str_file) {

		if (PsFile::isCheckFile ( $str_file )) {

			return unlink ( $str_file );
		} else {

			return false;
		}
	}

	/**
	 * Function: getFileType($file_name)
	 * Description: returns content type for the file type Arguments: $file_name as extension file string Returns: String **********************************************************************
	 */
	public static function getFileType($file_name) {

		if (ereg ( "\.", $file_name )) {
			return substr ( $file_name, (strrpos ( $file_name, "." ) + 1), strlen ( $file_name ) );
		}
	}

	/**
	 * Function: getContentType($ext ,$song=false)
	 * Description: returns content type for the file type Arguments: $ext as extension file string $funcPath as name of function get path
	 * Returns: String **********************************************************************
	 */
	public static function getContentType($ext, $song = false) {

		switch (strtolower ( $ext )) {
			case "gif" :
				return "image/gif";
				break;
			case "png" :
				return "image/png";
				break;
			case "pnz" :
				return "image/png";
				break;
			case "jpg" :
				return "image/jpeg";
				break;
			case "jpz" :
				return "image/jpeg";
				break;
			case "jpeg" :
				return "image/jpeg";
				break;
			case "mld" :
				return "application/x-mld";
			case "mid" :
				return "audio/mid";
				break;
			case "mmf" :
				return "application/x-smaf";
			case "pmd" :
				return "application/x-pmd";
			case "asf" :
				return "video/x-ms-asf";
				break;
			case "flv" :
				return "video/x-flv";
				break;
			case "amc" :
				return "application/x-mpeg";
				break;
			case "3gp" :

				if ($song == true) {
					return "audio/3gpp";
					break;
				} else {
					return "video/3gpp";
					break;
				}
			case "3g2" :
				return "audio/3gpp2";
				break;
			case "mp4" :
				return "video/mp4";
				break;
			case "swf" :
				return "application/x-shockwave-flash";
				break;
			case "dmt" :
				return "application/octet-stream";
				break;
			case "txt" :
				return "application/x-tex";
				break;
			default :
				return "application/octet-stream";
		}
	}

	/**
	 * get content file image from url and export in API
	 * folder_type = relative; => phu huynh folder_type = teacher; => giao vien hoac nhan su cua nha truong
	 */
	public static function memberImageShow($school_code, $path_file_img) {

		if ($path_file_img != '') {

			$path_file = sfConfig::get ( 'app_ps_data_dir' ) . '\\' . $school_code . '\hr\\' . $path_file_img;

			if (PsFile::isCheckFile ( $path_file )) {

				$str_fileType = PsFile::getFileType ( $path_file );

				$mime_fileType = PsFile::getContentType ( $str_fileType );

				@$fp = fopen ( $path_file, "r" );

				@$content_length = filesize ( $path_file );

				@$data = fread ( $fp, $content_length );

				@fclose ( $fp );

				header ( "Content-Type: " . $mime_fileType . "\n" );

				header ( "Content-Length: " . $content_length . "\n" );

				echo $data;
				exit ( 0 );
			} else {
				echo 'BBBB';
			}
			// exit(0);
		}
	}

	/**
	 * Kiem tra su ton tai cua 1 url*
	 */
	public static function urlExists($url) {

		return file_get_contents ( $url ) ? true : false;
	}

	// Tra ve data endcode 64 cuar url file anh
	public static function endCodeDataImage($url_file) {

		$data = file_get_contents ( $url_file );

		$finfo = new finfo ( FILEINFO_MIME_TYPE );
		$type = $finfo->file ( $url_file );
		$content_length = $finfo->buffer ( $url_file );

		header ( "Content-Type: " . $type . "\n" );
		header ( "Content-Length: " . $content_length . "\n" );
		echo $data;
	}

	// Tra ve data endcode 64 cuar url file anh
	public static function baseCode64DataImage($url_file) {
		if ($url_file == '')
			return '';
		
		$finfo = new finfo ( FILEINFO_MIME_TYPE );
		$type = $finfo->file ( $url_file );
		return 'data:' . $type . ';base64,' . base64_encode ( file_get_contents ( $url_file ) );
	}

	public static function curlDataImage($url_file) {

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_URL, $url_file );
		curl_setopt ( $ch, CURLOPT_HTTPGET, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_BINARYTRANSFER, 1 );
		$str_binary = curl_exec ( $ch );
		$info_arr = curl_getinfo ( $ch );
		
		curl_close($ch);

		header ( "Pragma: public" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Cache-Control: private", false );
		header ( "Content-Description: File Transfer" );
		header ( 'Content-Type:' . $info_arr ['content_type'] );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Content-Length:" . $info_arr ['download_content_length'] );
		ob_clean ();
		flush ();
		return $str_binary;
	}
}// end class