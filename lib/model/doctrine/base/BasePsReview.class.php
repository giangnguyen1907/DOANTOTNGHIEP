<?php

/**
 * BasePsReview
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $ps_customer_id
 * @property integer $ps_workplace_id
 * @property integer $member_id
 * @property integer $student_id
 * @property integer $ps_class_id
 * @property integer $category_review_id
 * @property integer $review_relative_id
 * @property string $note
 * @property integer $status
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * 
 * @method integer  getPsCustomerId()       Returns the current record's "ps_customer_id" value
 * @method integer  getPsWorkplaceId()      Returns the current record's "ps_workplace_id" value
 * @method integer  getMemberId()           Returns the current record's "member_id" value
 * @method integer  getStudentId()          Returns the current record's "student_id" value
 * @method integer  getPsClassId()          Returns the current record's "ps_class_id" value
 * @method integer  getCategoryReviewId()   Returns the current record's "category_review_id" value
 * @method integer  getReviewRelativeId()   Returns the current record's "review_relative_id" value
 * @method string   getNote()               Returns the current record's "note" value
 * @method integer  getStatus()             Returns the current record's "status" value
 * @method integer  getUserCreatedId()      Returns the current record's "user_created_id" value
 * @method integer  getUserUpdatedId()      Returns the current record's "user_updated_id" value
 * @method PsReview setPsCustomerId()       Sets the current record's "ps_customer_id" value
 * @method PsReview setPsWorkplaceId()      Sets the current record's "ps_workplace_id" value
 * @method PsReview setMemberId()           Sets the current record's "member_id" value
 * @method PsReview setStudentId()          Sets the current record's "student_id" value
 * @method PsReview setPsClassId()          Sets the current record's "ps_class_id" value
 * @method PsReview setCategoryReviewId()   Sets the current record's "category_review_id" value
 * @method PsReview setReviewRelativeId()   Sets the current record's "review_relative_id" value
 * @method PsReview setNote()               Sets the current record's "note" value
 * @method PsReview setStatus()             Sets the current record's "status" value
 * @method PsReview setUserCreatedId()      Sets the current record's "user_created_id" value
 * @method PsReview setUserUpdatedId()      Sets the current record's "user_updated_id" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePsReview extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ps_review');
        $this->hasColumn('ps_customer_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ps_workplace_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
		$this->hasColumn('date_at', 'date', null, array(
             'type' => 'date',
             'notnull' => false,
             ));
        $this->hasColumn('member_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('student_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('ps_class_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('category_review_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('review_relative_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('note', 'string', null, array(
             'type' => 'string',
             'notnull' => false,
             ));
        $this->hasColumn('status', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             'default' => 1,
             ));
        $this->hasColumn('user_created_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('user_updated_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));

        $this->option('type', 'InnoDB');
        $this->option('collate', 'utf8mb4_unicode_ci');
        $this->option('charset', 'utf8mb4');
    }

    public function setUp()
    {
        parent::setUp();

        $this->hasOne('PsMember', array(
             'local' => 'member_id',
             'foreign' => 'id'));

        $this->hasOne('Student', array(
             'local' => 'student_id',
             'foreign' => 'id'));

        $this->hasOne('PsReviewRelative', array(
             'local' => 'review_relative_id',
             'foreign' => 'id'));

        $this->hasOne('MyClass', array(
             'local' => 'ps_class_id',
             'foreign' => 'id'));

        $this->hasOne('PsCategoryReview', array(
             'local' => 'category_review_id',
             'foreign' => 'id'));

        $this->hasOne('PsCustomer', array(
             'local' => 'ps_customer_id',
             'foreign' => 'id'));

        $this->hasOne('PsWorkPlaces', array(
             'local' => 'ps_workplace_id',
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