<?php

class EncryptDecrypt {

	function encryptData($val, $keyEncrypt)
    {
        if (empty($val)) {
            return '';
        }
        
        $key = mysqlAesKey($keyEncrypt);
        $pad_value = 16-(strlen($val) % 16);
        $val = str_pad($val, (16*(floor(strlen($val) / 16)+1)), chr($pad_value));
        return strtoupper(bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM))));
    }
	
	function decryptData($val, $keyEncrypt)
    {
        if (empty($val)) {
            return '';
        }
        
        $key = mysqlAesKey($keyEncrypt);
        $val = hex2bin(strtolower($val));
        $val = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM));
        return rtrim($val, "\x00..\x10");
    }

	function mysqlAesKey($key)
	{
		$new_key = str_repeat(chr(0), 16);
		for($i=0,$len=strlen($key);$i<$len;$i++)
		{
			$new_key[$i%16] = $new_key[$i%16] ^ $key[$i];
		}
		
		return $new_key;
	}
	
	function genSearchQueryEncrypt($fieldName) {
        return "CONVERT( AES_DECRYPT( UNHEX( " . $fieldName . " ) , '" . mysqlAesKey('tHuYaUEYnENUaNOnMh') . "' ) USING utf8)";
    }
	
	$keyEncrypt = 'tHuYaUEYnENUaNOnMh';
$searchStr = $_REQUEST ['search_param'];

$testString = "Encrypts plaintext with given parameters";
$encryptedString = encryptData ( $testString, $keyEncrypt );

echo "<br />Original string: " . $testString;
echo "<br />Encrypted string: " . $encryptedString;
echo "<br />Decrypted string: " . decryptData ( $encryptedString, $keyEncrypt );

$link = mysqli_connect ( "localhost", "root", "vertrigo", "test_encrypt_data" );

if ($link === false) {
	die ( "ERROR: Could not connect. " . mysqli_connect_error () );
}

// ###########################################################################INSERT

$arr = array (
		" orange ",
		"apple",
		"Cherry",
		"durian" );

foreach ( $arr as &$value ) {
	$strName = 'Peter';
	$strEmail = 'Parker@email.com';
	$strLongText = 'You can also insert multiple rows into a table with a single insert query at once. To do this, include multiple lists of column values within the INSERT INTO statement, where column values for each row must be enclosed within parentheses and separated by a comma.';

	$strEmail = encryptData ( $strEmail, $keyEncrypt );
	$strLongText = encryptData ( $value . $strLongText, $keyEncrypt );

	// Attempt insert query execution
	$sql = "INSERT INTO persons (person_name, email, long_text_content) VALUES ('" . $strName . "', '" . $strEmail . "', '" . $strLongText . "')";
	mysqli_query ( $link, $sql );
}

// ###########################################################################SEARCH DATA:
// Search link: http://localhost/encrypt_decrypt_string.php?search_param=pp

$sql = "SELECT * FROM persons WHERE " . genSearchQueryEncrypt ( 'long_text_content' ) . " LIKE '%" . $searchStr . "%'";
if ($result = mysqli_query ( $link, $sql )) {
	if (mysqli_num_rows ( $result ) > 0) {
		while ( $row = mysqli_fetch_array ( $result ) ) {
			echo "<br />ID: " . $row ['person_id'];
			echo "<br />email: " . $row ['email'];
			echo "<br /><b>Decrypted text:" . decryptData ( $row ['long_text_content'], $keyEncrypt ) . "</b>";
			echo "<br />";
		}
	}
}
}			