<?php
class PsEncryptDecrypt {

	public static $cipher_mcrypt = MCRYPT_RIJNDAEL_128;

	public static $mode_mcrypt = MCRYPT_DEV_URANDOM;

	public static function encryptData($val, $keyEncrypt) {

		if (empty ( $val )) {
			return '';
		}

		$key = self::mysqlAesKey ( $keyEncrypt );
		$pad_value = 16 - (strlen ( $val ) % 16);
		$val = str_pad ( $val, (16 * (floor ( strlen ( $val ) / 16 ) + 1)), chr ( $pad_value ) );
		return strtoupper ( bin2hex ( mcrypt_encrypt ( self::$cipher_mcrypt, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv ( mcrypt_get_iv_size ( self::$cipher_mcrypt, MCRYPT_MODE_ECB ), self::$mode_mcrypt ) ) ) );
	}

	public static function decryptData($val, $keyEncrypt) {

		if (empty ( $val )) {
			return '';
		}

		$key = self::mysqlAesKey ( $keyEncrypt );
		$val = hex2bin ( strtolower ( $val ) );
		$val = mcrypt_decrypt ( self::$cipher_mcrypt, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv ( mcrypt_get_iv_size ( self::$cipher_mcrypt, MCRYPT_MODE_ECB ), self::$mode_mcrypt ) );
		return rtrim ( $val, "\x00..\x10" );
	}

	public static function mysqlAesKey($key) {

		$new_key = str_repeat ( chr ( 0 ), 16 );
		for($i = 0, $len = strlen ( $key ); $i < $len; $i ++) {
			$new_key [$i % 16] = $new_key [$i % 16] ^ $key [$i];
		}

		return $new_key;
	}

	/**
	 * Ham giai ma du lieu
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $fieldName -
	 *        	field of table DB
	 * @param $keyEncrypt -
	 *        	key giai ma
	 * @return $string
	 */
	function genSearchQueryEncrypt($fieldName, $keyEncrypt) {

		return "CONVERT( AES_DECRYPT( UNHEX( " . $fieldName . " ) , '" . mysqlAesKey ( $keyEncrypt ) . "' ) USING utf8)";
	}
}

