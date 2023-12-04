<?php
namespace App\PsUtil;
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
	 * @author thangnc@newwaytech.vn
	 * 
	 * @param: $str_file - String
	 * @return boolean
	 **/
	public static function isCheckFile($str_file) {
		
		if (file_exists($str_file))
			return true;
		else
			return false;
	}

	/**
	 * delete file
	 * 
	 * @author	thangnc@newwaytech.vn
	 *
	 * @param String - $strFile
	 * @return boolean
	 **/
	public static function deleteFile($str_file) {
		
		if (PsFile::isCheckFile($str_file)) {
			
			return unlink($str_file);

		} else {
			
			return false;

		}
	}

	/***********************************************************************
    Function:		getFileType($file_name) 
    Description:    returns content type for the file type 
    Arguments:      $file_name as extension file string
    Returns:        String
	************************************************************************/
	public static function getFileType($file_name) {
		$str = '';
		if (ereg ( "\.", $file_name )) {
			return substr ( $file_name, (strrpos ( $file_name, "." ) + 1), strlen ( $file_name ) );
		}
	}

	/***********************************************************************
    Function:		getContentType($ext ,$song=false) 
    Description:    returns content type for the file type 
    Arguments:      $ext as extension file string 
					$funcPath as name of function get path
    Returns:        String
	************************************************************************/
	public static function getContentType( $ext, $song=false ) {
		
		switch ( strtolower( $ext ) ) {
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
			case "mid":
				return "audio/mid";
				break;
			case "mmf" :
				return "application/x-smaf";
			case "pmd" :
				return "application/x-pmd";
			case "asf":
				return "video/x-ms-asf";
				break;
			case "flv":
				return "video/x-flv";
				break;
			case "amc":
				return "application/x-mpeg";
				break;
			case "3gp":
				
				if ($song == true) {
					return "audio/3gpp";
					break;
				}else {
					return "video/3gpp";
					break;
				}
			case "3g2":
				return "audio/3gpp2";
				break;
			case "mp4":
				return "video/mp4";
				break;
			case "swf":
				return "application/x-shockwave-flash";
				break;
			case "dmt":
				return "application/octet-stream";
				break;
			case "txt":
				return "application/x-tex";
				break;
			default :
				return "application/octet-stream";
		}
	}

	/** Kiem tra su ton tai cua 1 url**/
	public static function urlExists($url) {
		return file_get_contents($url,0,NULL,0,1) ? true : false;
	}

}// end class