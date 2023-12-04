<?php

/**
 * BasePsSchoolYear
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property string $note
 * @property boolean $is_default
 * @property integer $iorder
 * @property date $from_date
 * @property date $to_date
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * @property Doctrine_Collection $PsSchoolYears
 * @property Receivable $PsSchoolYear
 * 
 * @method string              getTitle()           Returns the current record's "title" value
 * @method string              getNote()            Returns the current record's "note" value
 * @method boolean             getIsDefault()       Returns the current record's "is_default" value
 * @method integer             getIorder()          Returns the current record's "iorder" value
 * @method date                getFromDate()        Returns the current record's "from_date" value
 * @method date                getToDate()          Returns the current record's "to_date" value
 * @method integer             getUserCreatedId()   Returns the current record's "user_created_id" value
 * @method integer             getUserUpdatedId()   Returns the current record's "user_updated_id" value
 * @method sfGuardUser         getUserCreated()     Returns the current record's "UserCreated" value
 * @method sfGuardUser         getUserUpdated()     Returns the current record's "UserUpdated" value
 * @method Doctrine_Collection getPsSchoolYears()   Returns the current record's "PsSchoolYears" collection
 * @method Receivable          getPsSchoolYear()    Returns the current record's "PsSchoolYear" value
 * @method PsSchoolYear        setTitle()           Sets the current record's "title" value
 * @method PsSchoolYear        setNote()            Sets the current record's "note" value
 * @method PsSchoolYear        setIsDefault()       Sets the current record's "is_default" value
 * @method PsSchoolYear        setIorder()          Sets the current record's "iorder" value
 * @method PsSchoolYear        setFromDate()        Sets the current record's "from_date" value
 * @method PsSchoolYear        setToDate()          Sets the current record's "to_date" value
 * @method PsSchoolYear        setUserCreatedId()   Sets the current record's "user_created_id" value
 * @method PsSchoolYear        setUserUpdatedId()   Sets the current record's "user_updated_id" value
 * @method PsSchoolYear        setUserCreated()     Sets the current record's "UserCreated" value
 * @method PsSchoolYear        setUserUpdated()     Sets the current record's "UserUpdated" value
 * @method PsSchoolYear        setPsSchoolYears()   Sets the current record's "PsSchoolYears" collection
 * @method PsSchoolYear        setPsSchoolYear()    Sets the current record's "PsSchoolYear" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsSchoolYear extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_school_year');
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('note', 'string', 300, array(
             'type' => 'string',
             'length' => 300,
             ));
        $this->hasColumn('is_default', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('iorder', 'integer', 11, array(
             'type' => 'integer',
             'default' => 0,
             'length' => 11,
             ));
        $this->hasColumn('from_date', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('to_date', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('user_updated_id', 'integer', null, array(
             'type' => 'integer',
             ));


        $this->index('title_idx', array(
             'fields' => 
             array(
              0 => 'title',
             ),
             ));
        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser as UserCreated', array(
             'local' => 'user_created_id',
             'foreign' => 'id'));

        $this->hasOne('sfGuardUser as UserUpdated', array(
             'local' => 'user_updated_id',
             'foreign' => 'id'));

        $this->hasMany('FeatureBranch as PsSchoolYears', array(
             'local' => 'id',
             'foreign' => 'school_year_id'));

        $this->hasOne('Receivable as PsSchoolYear', array(
             'local' => 'id',
             'foreign' => 'ps_school_year_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}