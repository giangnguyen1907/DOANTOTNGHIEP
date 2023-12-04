<?php

/**
 * BasePsHistoryLogtimes
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $student_id
 * @property integer $ps_logtime_id
 * @property string $ps_action
 * @property string $history_content
 * @property PsLogtimes $PsLogtimes
 * 
 * @method integer           getStudentId()       Returns the current record's "student_id" value
 * @method integer           getPsLogtimeId()     Returns the current record's "ps_logtime_id" value
 * @method string            getPsAction()        Returns the current record's "ps_action" value
 * @method string            getHistoryContent()  Returns the current record's "history_content" value
 * @method PsLogtimes        getPsLogtimes()      Returns the current record's "PsLogtimes" value
 * @method PsHistoryLogtimes setStudentId()       Sets the current record's "student_id" value
 * @method PsHistoryLogtimes setPsLogtimeId()     Sets the current record's "ps_logtime_id" value
 * @method PsHistoryLogtimes setPsAction()        Sets the current record's "ps_action" value
 * @method PsHistoryLogtimes setHistoryContent()  Sets the current record's "history_content" value
 * @method PsHistoryLogtimes setPsLogtimes()      Sets the current record's "PsLogtimes" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsHistoryLogtimes extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_history_logtimes');
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('ps_logtime_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('ps_action', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             ));
        $this->hasColumn('history_content', 'string', 2000, array(
             'type' => 'string',
             'length' => 2000,
             ));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
        $this->option('symfony', array(
             'form' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('PsLogtimes', array(
             'local' => 'ps_logtime_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}