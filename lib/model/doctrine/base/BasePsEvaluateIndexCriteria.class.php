<?php

/**
 * BasePsEvaluateIndexCriteria
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $evaluate_subject_id
 * @property string $title
 * @property string $criteria_code
 * @property boolean $is_activated
 * @property integer $iorder
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property PsEvaluateClassTime $PsEvaluateClassTime
 * @property PsEvaluateSubject $PsEvaluateSubject
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $PsEvaluateIndexStudent
 * 
 * @method integer                 getEvaluateSubjectId()      Returns the current record's "evaluate_subject_id" value
 * @method string                  getTitle()                  Returns the current record's "title" value
 * @method string                  getCriteriaCode()           Returns the current record's "criteria_code" value
 * @method boolean                 getIsActivated()            Returns the current record's "is_activated" value
 * @method integer                 getIorder()                 Returns the current record's "iorder" value
 * @method integer                 getUserCreatedId()          Returns the current record's "user_created_id" value
 * @method integer                 getUserUpdatedId()          Returns the current record's "user_updated_id" value
 * @method PsEvaluateClassTime     getPsEvaluateClassTime()    Returns the current record's "PsEvaluateClassTime" value
 * @method PsEvaluateSubject       getPsEvaluateSubject()      Returns the current record's "PsEvaluateSubject" value
 * @method sfGuardUser             getUserCreated()            Returns the current record's "UserCreated" value
 * @method sfGuardUser             getUserUpdated()            Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection     getPsEvaluateIndexStudent() Returns the current record's "PsEvaluateIndexStudent" collection
 * @method PsEvaluateIndexCriteria setEvaluateSubjectId()      Sets the current record's "evaluate_subject_id" value
 * @method PsEvaluateIndexCriteria setTitle()                  Sets the current record's "title" value
 * @method PsEvaluateIndexCriteria setCriteriaCode()           Sets the current record's "criteria_code" value
 * @method PsEvaluateIndexCriteria setIsActivated()            Sets the current record's "is_activated" value
 * @method PsEvaluateIndexCriteria setIorder()                 Sets the current record's "iorder" value
 * @method PsEvaluateIndexCriteria setUserCreatedId()          Sets the current record's "user_created_id" value
 * @method PsEvaluateIndexCriteria setUserUpdatedId()          Sets the current record's "user_updated_id" value
 * @method PsEvaluateIndexCriteria setPsEvaluateClassTime()    Sets the current record's "PsEvaluateClassTime" value
 * @method PsEvaluateIndexCriteria setPsEvaluateSubject()      Sets the current record's "PsEvaluateSubject" value
 * @method PsEvaluateIndexCriteria setUserCreated()            Sets the current record's "UserCreated" value
 * @method PsEvaluateIndexCriteria setUserUpdated()            Sets the current record's "UserUpdated" value
 * @method PsEvaluateIndexCriteria setPsEvaluateIndexStudent() Sets the current record's "PsEvaluateIndexStudent" collection
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsEvaluateIndexCriteria extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_evaluate_index_criteria');
        $this->hasColumn('evaluate_subject_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('criteria_code', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 0,
             'length' => 255,
             ));
        $this->hasColumn('is_activated', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('iorder', 'integer', 11, array(
             'type' => 'integer',
             'default' => 1,
             'length' => 11,
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
        $this->hasOne('PsEvaluateClassTime', array(
             'local' => 'id',
             'foreign' => 'criteria_id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('PsEvaluateSubject', array(
             'local' => 'evaluate_subject_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasMany('PsEvaluateIndexStudent', array(
             'local' => 'id',
             'foreign' => 'evaluate_index_criteria_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}