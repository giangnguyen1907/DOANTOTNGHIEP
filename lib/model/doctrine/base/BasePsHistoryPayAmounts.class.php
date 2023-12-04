<?php

/**
 * BasePsHistoryPayAmounts
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property double $amount
 * @property timestamp $pay_created_at
 * @property string $pay_type
 * @property string $description
 * @property sfGuardUser $UserHistoryPayAmounts
 * 
 * @method integer             getUserId()                Returns the current record's "user_id" value
 * @method double              getAmount()                Returns the current record's "amount" value
 * @method timestamp           getPayCreatedAt()          Returns the current record's "pay_created_at" value
 * @method string              getPayType()               Returns the current record's "pay_type" value
 * @method string              getDescription()           Returns the current record's "description" value
 * @method sfGuardUser         getUserHistoryPayAmounts() Returns the current record's "UserHistoryPayAmounts" value
 * @method PsHistoryPayAmounts setUserId()                Sets the current record's "user_id" value
 * @method PsHistoryPayAmounts setAmount()                Sets the current record's "amount" value
 * @method PsHistoryPayAmounts setPayCreatedAt()          Sets the current record's "pay_created_at" value
 * @method PsHistoryPayAmounts setPayType()               Sets the current record's "pay_type" value
 * @method PsHistoryPayAmounts setDescription()           Sets the current record's "description" value
 * @method PsHistoryPayAmounts setUserHistoryPayAmounts() Sets the current record's "UserHistoryPayAmounts" value
 * 
 * @package    truongnet.com
 * @subpackage model
 * @author     truongnet.com <contact@truongnet.com - ntsc279@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsHistoryPayAmounts extends sfDoctrineRecord {

	public function setTableDefinition() {

		$this->setTableName ( 'ps_history_pay_amounts' );
		$this->hasColumn ( 'user_id', 'integer', null, array (
				'type' => 'integer',
				'unique' => false,
				'notnull' => true ) );
		$this->hasColumn ( 'amount', 'double', null, array (
				'type' => 'double',
				'notnull' => true,
				'default' => 0 ) );
		$this->hasColumn ( 'pay_created_at', 'timestamp', null, array (
				'type' => 'timestamp' ) );
		$this->hasColumn ( 'pay_type', 'string', 10, array (
				'type' => 'string',
				'notnull' => false,
				'length' => 10 ) );
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
		$this->hasOne ( 'sfGuardUser as UserHistoryPayAmounts', array (
				'local' => 'user_id',
				'foreign' => 'id',
				'onDelete' => 'CASCADE' ) );

		$timestampable0 = new Doctrine_Template_Timestampable ();
		$this->actAs ( $timestampable0 );
	}
}