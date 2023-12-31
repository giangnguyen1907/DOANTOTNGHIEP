<?php

/**
 * BaseStudentFeature
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $student_id
 * @property integer $feature_option_feature_id
 * @property timestamp $tracked_at
 * @property string $note
 * @property timestamp $time_at
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property Student $Student
 * @property FeatureOptionFeature $FeatureOptionFeature
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method integer              getStudentId()                 Returns the current record's "student_id" value
 * @method integer              getFeatureOptionFeatureId()    Returns the current record's "feature_option_feature_id" value
 * @method timestamp            getTrackedAt()                 Returns the current record's "tracked_at" value
 * @method string               getNote()                      Returns the current record's "note" value
 * @method timestamp            getTimeAt()                    Returns the current record's "time_at" value
 * @method integer              getUserCreatedId()             Returns the current record's "user_created_id" value
 * @method integer              getUserUpdatedId()             Returns the current record's "user_updated_id" value
 * @method Student              getStudent()                   Returns the current record's "Student" value
 * @method FeatureOptionFeature getFeatureOptionFeature()      Returns the current record's "FeatureOptionFeature" value
 * @method sfGuardUser          getUserCreated()               Returns the current record's "UserCreated" value
 * @method sfGuardUser          getUserUpdated()               Returns the current record's "UserUpdated" value
 * @method StudentFeature       setStudentId()                 Sets the current record's "student_id" value
 * @method StudentFeature       setFeatureOptionFeatureId()    Sets the current record's "feature_option_feature_id" value
 * @method StudentFeature       setTrackedAt()                 Sets the current record's "tracked_at" value
 * @method StudentFeature       setNote()                      Sets the current record's "note" value
 * @method StudentFeature       setTimeAt()                    Sets the current record's "time_at" value
 * @method StudentFeature       setUserCreatedId()             Sets the current record's "user_created_id" value
 * @method StudentFeature       setUserUpdatedId()             Sets the current record's "user_updated_id" value
 * @method StudentFeature       setStudent()                   Sets the current record's "Student" value
 * @method StudentFeature       setFeatureOptionFeature()      Sets the current record's "FeatureOptionFeature" value
 * @method StudentFeature       setUserCreated()               Sets the current record's "UserCreated" value
 * @method StudentFeature       setUserUpdated()               Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseStudentFeature extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('student_feature');
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('feature_option_feature_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('tracked_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => false,
             ));
        $this->hasColumn('note', 'string', 300, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 300,
             ));
        $this->hasColumn('time_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => false,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_updated_id', 'integer', null, array(
             'type' => 'integer',
             ));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Student', array(
             'local' => 'student_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('FeatureOptionFeature', array(
             'local' => 'feature_option_feature_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}