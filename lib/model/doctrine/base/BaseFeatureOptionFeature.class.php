<?php

/**
 * BaseFeatureOptionFeature
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $type
 * @property integer $order_by
 * @property integer $feature_option_id
 * @property integer $feature_branch_id
 * @property boolean $is_activated
 * @property integer $user_created_id
 * @property integer $user_updated_id
 * @property StudentFeature $StudentFeature
 * @property FeatureOption $FeatureOption
 * @property FeatureBranch $FeatureBranch
 * @property sfGuardUser $UserCreated
 * @property sfGuardUser $UserUpdated
 * 
 * @method integer              getType()              Returns the current record's "type" value
 * @method integer              getOrderBy()           Returns the current record's "order_by" value
 * @method integer              getFeatureOptionId()   Returns the current record's "feature_option_id" value
 * @method integer              getFeatureBranchId()   Returns the current record's "feature_branch_id" value
 * @method boolean              getIsActivated()       Returns the current record's "is_activated" value
 * @method integer              getUserCreatedId()     Returns the current record's "user_created_id" value
 * @method integer              getUserUpdatedId()     Returns the current record's "user_updated_id" value
 * @method StudentFeature       getStudentFeature()    Returns the current record's "StudentFeature" value
 * @method FeatureOption        getFeatureOption()     Returns the current record's "FeatureOption" value
 * @method FeatureBranch        getFeatureBranch()     Returns the current record's "FeatureBranch" value
 * @method sfGuardUser          getUserCreated()       Returns the current record's "UserCreated" value
 * @method sfGuardUser          getUserUpdated()       Returns the current record's "UserUpdated" value
 * @method FeatureOptionFeature setType()              Sets the current record's "type" value
 * @method FeatureOptionFeature setOrderBy()           Sets the current record's "order_by" value
 * @method FeatureOptionFeature setFeatureOptionId()   Sets the current record's "feature_option_id" value
 * @method FeatureOptionFeature setFeatureBranchId()   Sets the current record's "feature_branch_id" value
 * @method FeatureOptionFeature setIsActivated()       Sets the current record's "is_activated" value
 * @method FeatureOptionFeature setUserCreatedId()     Sets the current record's "user_created_id" value
 * @method FeatureOptionFeature setUserUpdatedId()     Sets the current record's "user_updated_id" value
 * @method FeatureOptionFeature setStudentFeature()    Sets the current record's "StudentFeature" value
 * @method FeatureOptionFeature setFeatureOption()     Sets the current record's "FeatureOption" value
 * @method FeatureOptionFeature setFeatureBranch()     Sets the current record's "FeatureBranch" value
 * @method FeatureOptionFeature setUserCreated()       Sets the current record's "UserCreated" value
 * @method FeatureOptionFeature setUserUpdated()       Sets the current record's "UserUpdated" value
 * 
 * @package    KidsSchool.vn
 * @subpackage model
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFeatureOptionFeature extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('feature_option_feature');
        $this->hasColumn('type', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('order_by', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));
        $this->hasColumn('feature_option_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('feature_branch_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('is_activated', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
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
        $this->hasOne('StudentFeature', array(
             'local' => 'id',
             'foreign' => 'feature_option_feature_id'));

        $this->hasOne('FeatureOption', array(
             'local' => 'feature_option_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('FeatureBranch', array(
             'local' => 'feature_branch_id',
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