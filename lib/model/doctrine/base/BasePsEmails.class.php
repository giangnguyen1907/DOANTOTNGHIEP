<?php

/**
 * BasePsEmails
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $ps_email
 * @property integer $obj_id
 * @property string $obj_type
 * @property Doctrine_Collection $PsEmails
 * 
 * @method string              getPsEmail()  Returns the current record's "ps_email" value
 * @method integer             getObjId()    Returns the current record's "obj_id" value
 * @method string              getObjType()  Returns the current record's "obj_type" value
 * @method Doctrine_Collection getPsEmails() Returns the current record's "PsEmails" collection
 * @method PsEmails            setPsEmail()  Sets the current record's "ps_email" value
 * @method PsEmails            setObjId()    Sets the current record's "obj_id" value
 * @method PsEmails            setObjType()  Sets the current record's "obj_type" value
 * @method PsEmails            setPsEmails() Sets the current record's "PsEmails" collection
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsEmails extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_emails');
        $this->hasColumn('ps_email', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('obj_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('obj_type', 'string', 1, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 1,
             ));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('PsMember as PsEmails', array(
             'local' => 'ps_email',
             'foreign' => 'email'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}