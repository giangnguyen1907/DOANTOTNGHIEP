<?php

/**
 * BasePsAppAmounts
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property double $amount
 * @property timestamp $expiration_date_at
 * @property string $description
 * @property sfGuardUser $UserAppAmounts
 * 
 * @method integer      getUserId()             Returns the current record's "user_id" value
 * @method double       getAmount()             Returns the current record's "amount" value
 * @method timestamp    getExpirationDateAt()   Returns the current record's "expiration_date_at" value
 * @method string       getDescription()        Returns the current record's "description" value
 * @method sfGuardUser  getUserAppAmounts()     Returns the current record's "UserAppAmounts" value
 * @method PsAppAmounts setUserId()             Sets the current record's "user_id" value
 * @method PsAppAmounts setAmount()             Sets the current record's "amount" value
 * @method PsAppAmounts setExpirationDateAt()   Sets the current record's "expiration_date_at" value
 * @method PsAppAmounts setDescription()        Sets the current record's "description" value
 * @method PsAppAmounts setUserAppAmounts()     Sets the current record's "UserAppAmounts" value
 * 
 * @package    truongnet.com
 * @subpackage model
 * @author     truongnet.com <contact@truongnet.com - ntsc279@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsAppAmounts extends sfDoctrineRecord {

	public function setTableDefinition() {

		$this->setTableName ( 'ps_app_amounts' );
		$this->hasColumn ( 'user_id', 'integer', null, array (
				'type' => 'integer',
				'unique' => false,
				'notnull' => true ) );
		$this->hasColumn ( 'amount', 'double', null, array (
				'type' => 'double',
				'notnull' => true,
				'default' => 0 ) );
		$this->hasColumn ( 'expiration_date_at', 'timestamp', null, array (
				'type' => 'timestamp' ) );
		$this->hasColumn ( 'description', 'string', 255, array (
				'type' => 'string',
				'notnull' => false,
				'length' => 255 ) );

		$this->option ( 'type', 'InnoDB' );
		$this->option ( 'collate', 'utf8_unicode_ci' );
		$this->option ( 'charset', 'utf8' );
	}

	public function setUp() {

		parent::setUp ();
		$this->hasOne ( 'sfGuardUser as UserAppAmounts', array (
				'local' => 'user_id',
				'foreign' => 'id',
				'onDelete' => 'CASCADE' ) );

		$timestampable0 = new Doctrine_Template_Timestampable ();
		$this->actAs ( $timestampable0 );
	}
}