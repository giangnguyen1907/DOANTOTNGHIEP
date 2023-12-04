<?php
/**
* @package truongnet.com
* @subpackage API app 
* @file BaseModel.php
* 
* @author thangnc
* @version 1.0 2017/03/17
*/
namespace  App\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
	
	public static function beginTransaction()
	{
		self::getConnectionResolver()->connection()->beginTransaction();
	}
	
	public static function commit()
	{
		self::getConnectionResolver()->connection()->commit();
	}
	
	public static function rollBack()
	{
		self::getConnectionResolver()->connection()->rollBack();
	}
}