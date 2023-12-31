<?php

/**
 * BaseDayInMonth
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property tinyint $iday
 * @property PsLogtimes $PsLogtimes
 * @property StudentClass $StudentClass
 * 
 * @method integer      getId()           Returns the current record's "id" value
 * @method tinyint      getIday()         Returns the current record's "iday" value
 * @method PsLogtimes   getPsLogtimes()   Returns the current record's "PsLogtimes" value
 * @method StudentClass getStudentClass() Returns the current record's "StudentClass" value
 * @method DayInMonth   setId()           Sets the current record's "id" value
 * @method DayInMonth   setIday()         Sets the current record's "iday" value
 * @method DayInMonth   setPsLogtimes()   Sets the current record's "PsLogtimes" value
 * @method DayInMonth   setStudentClass() Sets the current record's "StudentClass" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDayInMonth extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('day_in_month');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('iday', 'tinyint', 1, array(
             'type' => 'tinyint',
             'length' => 1,
             ));

        $this->option('type', 'MyISAM');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('PsLogtimes', array(
             'local' => 'iday',
             'foreign' => 'login_at'));

        $this->hasOne('StudentClass', array(
             'local' => 'iday',
             'foreign' => 'start_at'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}