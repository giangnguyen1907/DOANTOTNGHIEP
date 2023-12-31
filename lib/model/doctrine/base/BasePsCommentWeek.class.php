<?php

/**
 * BasePsCommentWeek
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property integer $student_id
 * @property integer $ps_year
 * @property string $ps_month
 * @property integer $ps_week
 * @property string $title
 * @property string $comment
 * @property boolean $is_activated
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property Student $Student
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method integer       getPsCustomerId()    Returns the current record's "ps_customer_id" value
 * @method integer       getStudentId()       Returns the current record's "student_id" value
 * @method integer       getPsYear()          Returns the current record's "ps_year" value
 * @method string        getPsMonth()         Returns the current record's "ps_month" value
 * @method integer       getPsWeek()          Returns the current record's "ps_week" value
 * @method string        getTitle()           Returns the current record's "title" value
 * @method string        getComment()         Returns the current record's "comment" value
 * @method boolean       getIsActivated()     Returns the current record's "is_activated" value
 * @method integer       getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer       getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method Student       getStudent()         Returns the current record's "Student" value
 * @method sfGuardUser   getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser   getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method PsCommentWeek setPsCustomerId()    Sets the current record's "ps_customer_id" value
 * @method PsCommentWeek setStudentId()       Sets the current record's "student_id" value
 * @method PsCommentWeek setPsYear()          Sets the current record's "ps_year" value
 * @method PsCommentWeek setPsMonth()         Sets the current record's "ps_month" value
 * @method PsCommentWeek setPsWeek()          Sets the current record's "ps_week" value
 * @method PsCommentWeek setTitle()           Sets the current record's "title" value
 * @method PsCommentWeek setComment()         Sets the current record's "comment" value
 * @method PsCommentWeek setIsActivated()     Sets the current record's "is_activated" value
 * @method PsCommentWeek setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsCommentWeek setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method PsCommentWeek setStudent()         Sets the current record's "Student" value
 * @method PsCommentWeek setUserCreated()     Sets the current record's "UserCreated" value
 * @method PsCommentWeek setUserUpdated()     Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsCommentWeek extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_comment_week');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('ps_year', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('ps_month', 'string', 7, array(
             'type' => 'string',
             'length' => 7,
             ));
        $this->hasColumn('ps_week', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('comment', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('is_activated', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_updated_id', 'integer', null, array(
             'type' => 'integer',
             ));
		$this->hasColumn('number_push_notication', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             'length' => 4,
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
             'foreign' => 'id'));

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