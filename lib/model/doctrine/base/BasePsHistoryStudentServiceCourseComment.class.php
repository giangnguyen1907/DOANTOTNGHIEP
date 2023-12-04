<?php

/**
 * BasePsHistoryStudentServiceCourseComment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $student_id
 * @property integer $ps_service_course_schedule_id
 * @property string $ps_action
 * @property string $history_content
 * @property Student $Student
 * 
 * @method integer                              getStudentId()                     Returns the current record's "student_id" value
 * @method integer                              getPsServiceCourseScheduleId()     Returns the current record's "ps_service_course_schedule_id" value
 * @method string                               getPsAction()                      Returns the current record's "ps_action" value
 * @method string                               getHistoryContent()                Returns the current record's "history_content" value
 * @method Student                              getStudent()                       Returns the current record's "Student" value
 * @method PsHistoryStudentServiceCourseComment setStudentId()                     Sets the current record's "student_id" value
 * @method PsHistoryStudentServiceCourseComment setPsServiceCourseScheduleId()     Sets the current record's "ps_service_course_schedule_id" value
 * @method PsHistoryStudentServiceCourseComment setPsAction()                      Sets the current record's "ps_action" value
 * @method PsHistoryStudentServiceCourseComment setHistoryContent()                Sets the current record's "history_content" value
 * @method PsHistoryStudentServiceCourseComment setStudent()                       Sets the current record's "Student" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsHistoryStudentServiceCourseComment extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_history_student_service_course_comment');
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('ps_service_course_schedule_id', 'integer', null, array(
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
        $this->hasOne('Student', array(
             'local' => 'student_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}