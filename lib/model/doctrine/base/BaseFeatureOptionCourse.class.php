<?php

/**
 * BaseFeatureOptionCourse
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $type
 * @property integer $order_by
 * @property integer $feature_option_id
 * @property integer $ps_service_id
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property StudentServiceCourseComment $StudentServiceCourseComment
 * @property FeatureOption $FeatureOption
 * @property Service $Service
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $FeatureOptionCourse
 * 
 * @method integer                     getType()                        Returns the current record's "type" value
 * @method integer                     getOrderBy()                     Returns the current record's "order_by" value
 * @method integer                     getFeatureOptionId()             Returns the current record's "feature_option_id" value
 * @method integer                     getPsServiceId()                 Returns the current record's "ps_service_id" value
 * @method integer                     getUserCreatedId()               Returns the current record's "user_created_id" value
 * @method integer                     getUserUpdatedId()               Returns the current record's "user_updated_id" value
 * @method StudentServiceCourseComment getStudentServiceCourseComment() Returns the current record's "StudentServiceCourseComment" value
 * @method FeatureOption               getFeatureOption()               Returns the current record's "FeatureOption" value
 * @method Service                     getService()                     Returns the current record's "Service" value
 * @method sfGuardUser                 getUserCreated()                 Returns the current record's "UserCreated" value
 * @method sfGuardUser                 getUserUpdated()                 Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection         getFeatureOptionCourse()         Returns the current record's "FeatureOptionCourse" collection
 * @method FeatureOptionCourse         setType()                        Sets the current record's "type" value
 * @method FeatureOptionCourse         setOrderBy()                     Sets the current record's "order_by" value
 * @method FeatureOptionCourse         setFeatureOptionId()             Sets the current record's "feature_option_id" value
 * @method FeatureOptionCourse         setPsServiceId()                 Sets the current record's "ps_service_id" value
 * @method FeatureOptionCourse         setUserCreatedId()               Sets the current record's "user_created_id" value
 * @method FeatureOptionCourse         setUserUpdatedId()               Sets the current record's "user_updated_id" value
 * @method FeatureOptionCourse         setStudentServiceCourseComment() Sets the current record's "StudentServiceCourseComment" value
 * @method FeatureOptionCourse         setFeatureOption()               Sets the current record's "FeatureOption" value
 * @method FeatureOptionCourse         setService()                     Sets the current record's "Service" value
 * @method FeatureOptionCourse         setUserCreated()                 Sets the current record's "UserCreated" value
 * @method FeatureOptionCourse         setUserUpdated()                 Sets the current record's "UserUpdated" value
 * @method FeatureOptionCourse         setFeatureOptionCourse()         Sets the current record's "FeatureOptionCourse" collection
 * 
 * @package    kidsschool.vn
 * @subpackage model
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFeatureOptionCourse extends sfDoctrineRecord {

	public function setTableDefinition() {

		$this->setTableName ( 'feature_option_course' );
		$this->hasColumn ( 'type', 'integer', 11, array (
				'type' => 'integer',
				'length' => 11 ) );
		$this->hasColumn ( 'order_by', 'integer', 11, array (
				'type' => 'integer',
				'length' => 11 ) );
		$this->hasColumn ( 'feature_option_id', 'integer', null, array (
				'type' => 'integer' ) );
		$this->hasColumn ( 'ps_service_id', 'integer', null, array (
				'type' => 'integer' ) );
		$this->hasColumn ( 'user_created_id', 'integer', null, array (
				'type' => 'integer' ) );
		$this->hasColumn ( 'user_updated_id', 'integer', null, array (
				'type' => 'integer' ) );

		$this->option ( 'type', 'InnoDB' );
		$this->option ( 'collate', 'utf8_unicode_ci' );
		$this->option ( 'charset', 'utf8' );
	}

	public function setUp() {

		parent::setUp ();
		$this->hasOne ( 'StudentServiceCourseComment', array (
				'local' => 'id',
				'foreign' => 'student_service_course_comment_id' ) );

		$this->hasOne ( 'FeatureOption', array (
				'local' => 'feature_option_id',
				'foreign' => 'id',
				'onDelete' => 'CASCADE' ) );

		$this->hasOne ( 'Service', array (
				'local' => 'ps_service_id',
				'foreign' => 'id',
				'onDelete' => 'CASCADE' ) );

		$this->hasOne ( 'sfGuardUser as UserCreated', array (
				'local' => 'user_created_id',
				'foreign' => 'id' ) );

		$this->hasOne ( 'sfGuardUser as UserUpdated', array (
				'local' => 'user_updated_id',
				'foreign' => 'id' ) );

		$this->hasMany ( 'StudentServiceCourseComment as FeatureOptionCourse', array (
				'local' => 'id',
				'foreign' => 'feature_option_course_id' ) );

		$timestampable0 = new Doctrine_Template_Timestampable ();
		$this->actAs ( $timestampable0 );
	}
}