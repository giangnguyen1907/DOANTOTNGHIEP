<?php

/**
 * PsWorkPlaces form base class.
 *
 * @method PsWorkPlaces getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsWorkPlacesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                                       => new sfWidgetFormInputHidden(),
      'ps_customer_id'                           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_ward_id'                               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWard'), 'add_empty' => true)),
      'title'                                    => new sfWidgetFormInputText(),
      'address'                                  => new sfWidgetFormInputText(),
      'phone'                                    => new sfWidgetFormInputText(),
      'principal'                                => new sfWidgetFormInputText(),
      'email'                                    => new sfWidgetFormInputText(),
      'note'                                     => new sfWidgetFormInputText(),
      'description'                              => new sfWidgetFormTextarea(),
      'iorder'                                   => new sfWidgetFormInputText(),
      'is_activated'                             => new sfWidgetFormInputCheckbox(),
      'is_receipt'                               => new sfWidgetFormInputText(),
      'is_reduce'                                => new sfWidgetFormInputText(),
      'receipt_code'                             => new sfWidgetFormInputText(),
      'type_receipt'                             => new sfWidgetFormInputText(),
      'config_default_login'                     => new sfWidgetFormTime(),
      'config_default_logout'                    => new sfWidgetFormTime(),
      'config_choose_charge_paylate'             => new sfWidgetFormInputCheckbox(),
      'config_choose_charge_late'                => new sfWidgetFormInputCheckbox(),
      'config_choose_charge_showlate'            => new sfWidgetFormInputCheckbox(),
      'config_choose_charge_fee_mobile'          => new sfWidgetFormInputText(),
      'config_choose_attendances_view_app'       => new sfWidgetFormInputCheckbox(),
      'config_choose_attendances_relative'       => new sfWidgetFormInputCheckbox(),
      'config_time_receive_valid'                => new sfWidgetFormTime(),
      'config_normal_day'                        => new sfWidgetFormInputText(),
      'config_full_day'                          => new sfWidgetFormInputText(),
      'config_number_attendance'                 => new sfWidgetFormInputText(),
      'config_time_cancel_saturday_valid'        => new sfWidgetFormInputText(),
      'config_closing_date_fee'                  => new sfWidgetFormInputText(),
      'config_start_date_system_fee'             => new sfWidgetFormDate(),
      'config_class_late'                        => new sfWidgetFormInputCheckbox(),
      'config_chat_relative_to_relative'         => new sfWidgetFormInputCheckbox(),
      'config_chat_relative_to_teacher'          => new sfWidgetFormInputCheckbox(),
      'config_time_chat_relative_to_teacher'     => new sfWidgetFormTime(),
      'config_msg_relative_to_relative'          => new sfWidgetFormInputCheckbox(),
      'config_view_relative_attendance'          => new sfWidgetFormInputCheckbox(),
      'is_notication_activities'                 => new sfWidgetFormInputCheckbox(),
      'from_time_notication_activities'          => new sfWidgetFormTime(),
      'to_time_notication_activities'            => new sfWidgetFormTime(),
      'from_time_notication_attendances'         => new sfWidgetFormTime(),
      'to_time_notication_attendances'           => new sfWidgetFormTime(),
      'from_time_class_delay'                    => new sfWidgetFormTime(),
      'to_time_class_delay'                      => new sfWidgetFormTime(),
      'config_note_receipt'                      => new sfWidgetFormInputText(),
      'config_template_receipt_export'           => new sfWidgetFormInputText(),
      'config_template_report_export'            => new sfWidgetFormInputText(),
      'config_template_menus'                    => new sfWidgetFormInputCheckbox(),
      'is_warning_attendance'                    => new sfWidgetFormInputCheckbox(),
      'time_warning_attendance'                  => new sfWidgetFormTime(),
      'mail_warning_attendance'                  => new sfWidgetFormInputText(),
      'config_multiple_teacher_process_album'    => new sfWidgetFormInputCheckbox(),
      'config_push_notication_update_attendance' => new sfWidgetFormInputCheckbox(),
      'config_email_report'                      => new sfWidgetFormInputText(),
      'user_created_id'                          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'                          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                               => new sfWidgetFormDateTime(),
      'updated_at'                               => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                                       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'                           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_ward_id'                               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWard'), 'required' => false)),
      'title'                                    => new sfValidatorString(array('max_length' => 255)),
      'address'                                  => new sfValidatorString(array('max_length' => 255)),
      'phone'                                    => new sfValidatorString(array('max_length' => 50)),
      'principal'                                => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'email'                                    => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'note'                                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'                              => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'iorder'                                   => new sfValidatorInteger(array('required' => false)),
      'is_activated'                             => new sfValidatorBoolean(array('required' => false)),
      'is_receipt'                               => new sfValidatorInteger(array('required' => false)),
      'is_reduce'                                => new sfValidatorInteger(array('required' => false)),
      'receipt_code'                             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type_receipt'                             => new sfValidatorInteger(array('required' => false)),
      'config_default_login'                     => new sfValidatorTime(array('required' => false)),
      'config_default_logout'                    => new sfValidatorTime(array('required' => false)),
      'config_choose_charge_paylate'             => new sfValidatorBoolean(array('required' => false)),
      'config_choose_charge_late'                => new sfValidatorBoolean(array('required' => false)),
      'config_choose_charge_showlate'            => new sfValidatorBoolean(array('required' => false)),
      'config_choose_charge_fee_mobile'          => new sfValidatorInteger(array('required' => false)),
      'config_choose_attendances_view_app'       => new sfValidatorBoolean(array('required' => false)),
      'config_choose_attendances_relative'       => new sfValidatorBoolean(array('required' => false)),
      'config_time_receive_valid'                => new sfValidatorTime(array('required' => false)),
      'config_normal_day'                        => new sfValidatorInteger(array('required' => false)),
      'config_full_day'                          => new sfValidatorInteger(array('required' => false)),
      'config_number_attendance'                 => new sfValidatorInteger(array('required' => false)),
      'config_time_cancel_saturday_valid'        => new sfValidatorInteger(array('required' => false)),
      'config_closing_date_fee'                  => new sfValidatorInteger(array('required' => false)),
      'config_start_date_system_fee'             => new sfValidatorDate(),
      'config_class_late'                        => new sfValidatorBoolean(array('required' => false)),
      'config_chat_relative_to_relative'         => new sfValidatorBoolean(array('required' => false)),
      'config_chat_relative_to_teacher'          => new sfValidatorBoolean(array('required' => false)),
      'config_time_chat_relative_to_teacher'     => new sfValidatorTime(array('required' => false)),
      'config_msg_relative_to_relative'          => new sfValidatorBoolean(array('required' => false)),
      'config_view_relative_attendance'          => new sfValidatorBoolean(array('required' => false)),
      'is_notication_activities'                 => new sfValidatorBoolean(array('required' => false)),
      'from_time_notication_activities'          => new sfValidatorTime(array('required' => false)),
      'to_time_notication_activities'            => new sfValidatorTime(array('required' => false)),
      'from_time_notication_attendances'         => new sfValidatorTime(array('required' => false)),
      'to_time_notication_attendances'           => new sfValidatorTime(array('required' => false)),
      'from_time_class_delay'                    => new sfValidatorTime(array('required' => false)),
      'to_time_class_delay'                      => new sfValidatorTime(array('required' => false)),
      'config_note_receipt'                      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'config_template_receipt_export'           => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'config_template_report_export'            => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'config_template_menus'                    => new sfValidatorBoolean(array('required' => false)),
      'is_warning_attendance'                    => new sfValidatorBoolean(array('required' => false)),
      'time_warning_attendance'                  => new sfValidatorTime(array('required' => false)),
      'mail_warning_attendance'                  => new sfValidatorPass(array('required' => false)),
      'config_multiple_teacher_process_album'    => new sfValidatorBoolean(array('required' => false)),
      'config_push_notication_update_attendance' => new sfValidatorBoolean(array('required' => false)),
      'config_email_report'                      => new sfValidatorPass(array('required' => false)),
      'user_created_id'                          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'                          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'                               => new sfValidatorDateTime(),
      'updated_at'                               => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_work_places[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsWorkPlaces';
  }

}
