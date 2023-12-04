<?php
/**
* @project_name
* @subpackage     interpreter 
*
* @file authentication.php
* @filecomment filecomment
* @package_declaration package_declaration
* @author thangnc
* @version 1.0 17-03-2017 -  12:02:42
*/
namespace App\Authentication;

use App\PsUtil\PsString;
use App\Controller\BaseController;

use Api\Users\Model\UserModel;

class PsAuthentication extends BaseController {

	/**
	 *  hashPassword($str_pass, $algorithm_callable = 'sha1') - Ham ma hoa mat khau boi thuat toan
	 * 
	 * @param $salt - string, salt
	 * @param $password - string, mat khau
	 * @param $algorithm_callable - string, thuat toan ma hoa
	 * 
	 * @return string - mat khau da ma hoa
	 **/
	public static function hashPassword($salt = '', $password = '', $algorithm_callable = 'sha1') {
		
		$algorithm = $algorithm_callable;
		
		if (false !== $pos = strpos($algorithm, '::'))
		{
			$algorithm = array(substr($algorithm, 0, $pos), substr($algorithm, $pos + 2));
		}
		
		if (!is_callable($algorithm))
		{
			throw new UnauthorizedException(sprintf('The algorithm callable "%s" is not callable.', $algorithm));
		}
		
		return call_user_func_array($algorithm_callable, array($salt.$password));
	}
	
	
	public function checkPassword($password1, $password2)
	{
		return $password1 == $password2;
	}

	/*
	 * This method will generate a unique api key
	 */
    public static function generateApiKey($salt){
        $api_key = hash('sha256', (time() . $salt . PsString::randomString()));
        return $api_key;
    }

    /**
     * Search user on database by authorization api_token
     */
    public function getUserColumnByToken($api_token)
    {
        // Call to user DB
        $user = UserModel::getUserColumnByToken($api_token);
        
        if(!$user) {
        	throw new UnauthorizedException(PS_TEXT_INVALID_TOKEN);
        }

        return $user;
    }

    /**
     * Neu user is USER_TYPE_TEACHER => Ko can check device_id (return true)
     * 
     * @return boolean 
    **/
    public static function checkDevice($user, $device_id) {
		
		$check = true; 

		if (($user->user_type == USER_TYPE_RELATIVE) && ($user->app_device_id != $device_id)) {
			$check = false;
		}

		return $check;
    }
    
    /**
     * Neu user is USER_TYPE_TEACHER => Ko can check device_id (return true)
     *
     * @return boolean
     **/
    public static function checkDeviceUserRelative($user, $device_id) {
    	
    	return (($user->user_type == USER_TYPE_RELATIVE) && ($user->app_device_id == $device_id));
    }
}