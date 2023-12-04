<?php
/**
 * PsWorkPlaces form.
 *
 * @package quanlymamnon.vn
 * @subpackage form
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsWorkPlacesForm extends BasePsWorkPlacesForm {

	public function configure() {

		$this->widgetSchema['is_reduce']->setAttributes(array(
	        'class' => 'form-control',
	        'type' => 'number',
	        'min' => '0'
	    ));

		$this->widgetSchema['email']->setOption('type', 'email');
		
		$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsActivity()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['receipt_code']->setAttributes(array(
				'maxlength' => 50
		));
		
		$this->widgetSchema['title']->setAttributes(array(
				'maxlength' => 255
		));
		
		$this->widgetSchema['address']->setAttributes(array(
				'maxlength' => 255
		));
		$this->widgetSchema['phone']->setAttributes(array(
				'maxlength' => 50
		));
		
		$this->widgetSchema['note']->setAttributes(array(
				'maxlength' => 255
		));
		
		$this->widgetSchema['description']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 500
		));
		
		$this->widgetSchema['config_chat_relative_to_relative'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['config_chat_relative_to_teacher'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['type_receipt'] = new sfWidgetFormSelect(array(
				'choices' => PreSchool::loadPsTypeReceipt()
		), array(
				'class' => 'select2',
				'required' => true
		));

		/*
		$this->widgetSchema['config_view_relative_attendance'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		*/
		
		$this->widgetSchema['config_choose_attendances_view_app'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['config_view_relative_attendance'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['config_msg_relative_to_relative'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['config_choose_attendances_relative'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsChoiseRelative()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['config_class_late'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['config_time_chat_relative_to_teacher'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['config_time_chat_relative_to_teacher']->setAttributes(array(
				'class' => 'startTime timepicker',
				'data-mode' => "24h",
				'required' => false
		));
		
		$this->validatorSchema['config_time_chat_relative_to_teacher'] = new sfValidatorTime(array(
				'required' => false
		));
		
		$this->widgetSchema['config_default_login'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['config_default_login']->setAttributes(array(
				'class' => 'startTime timepicker',
				'data-mode' => "24h",
				'required' => true
		));
		
		$this->validatorSchema['config_default_login'] = new sfValidatorTime(array(
				'required' => true
		));
		
		$this->widgetSchema['config_default_logout'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['config_default_logout']->setAttributes(array(
				'class' => 'endTime timepicker',
				'data-mode' => "24h",
				'required' => true
		));
		
		$this->validatorSchema['config_default_logout'] = new sfValidatorTime(array(
				'required' => true
		));
		
		// $this->widgetSchema ['config_late_money']->setOption ( 'type', 'number' );
		
		// $this->setDefault('config_late_money', 0);
		
		// Cach hien thi quy đổi giờ về muộn trên phiếu: 0: Phút - 1: Giờ
		$this->widgetSchema['config_choose_charge_showlate'] = new sfWidgetFormSelect(array(
				'choices' => PreSchool::loadPsTypeMinuteHour()
		), array(
				'class' => 'select2',
				'required' => true
		));
		
		$this->validatorSchema['config_choose_charge_showlate'] = new sfValidatorChoice(array(
				'choices' => array_keys(PreSchool::loadPsTypeMinuteHour()),
				'required' => true
		));
		
		// Cach hien thi phi ra app phu huynh 0: Xem chi tiet - 1 Xem tong
		$this->widgetSchema['config_choose_charge_fee_mobile'] = new sfWidgetFormSelect(array(
				'choices' => PreSchool::loadPsViewFeeMobile()
		), array(
				'class' => 'select2',
				'required' => true
		));
		
		$this->validatorSchema['config_choose_charge_fee_mobile'] = new sfValidatorChoice(array(
				'choices' => array_keys(PreSchool::loadPsViewFeeMobile()),
				'required' => true
		));
		
		// chon bieu mau xuat phieu thu
		$this->widgetSchema['config_template_receipt_export'] = new sfWidgetFormSelect(array(
				'choices' => PreSchool::loadPsTemplateReceipts()
		), array(
				'class' => 'select2',
				'required' => true
		));
		
		$this->validatorSchema['config_template_receipt_export'] = new sfValidatorChoice(array(
				'choices' => array_keys(PreSchool::loadPsTemplateReceipts()),
				'required' => true
		));
		
		// Chon cach tinh thu phi nop hoc phi muon
		$this->widgetSchema['config_choose_charge_paylate'] = new sfWidgetFormSelect(array(
				'choices' => PreSchool::loadPsTypeFeePayLate()
		), array(
				'class' => 'select2',
				'required' => true
		));
		
		$this->validatorSchema['config_choose_charge_paylate'] = new sfValidatorChoice(array(
				'choices' => array_keys(PreSchool::loadPsTypeFeePayLate()),
				'required' => true
		));
		
		// Chon cach tinh tien ve muon
		$this->widgetSchema['config_choose_charge_late'] = new sfWidgetFormSelect(array(
				'choices' => PreSchool::loadPsTypeFeeLate()
		), array(
				'class' => 'select2',
				'required' => true
		));
		
		// Chon cach hien thi thuc don
		$this->widgetSchema['config_template_menus'] = new sfWidgetFormSelect(array(
		    'choices' => PreSchool::loadPsTypeMenus()
		), array(
		    'class' => 'select2',
		    'required' => true
		));
		
		$this->validatorSchema['config_template_menus'] = new sfValidatorChoice(array(
		    'choices' => array_keys(PreSchool::loadPsTypeMenus()),
				'required' => true
		));
		
		$this->setDefault('config_choose_charge_late', PreSchool::ACTIVE);
		
		$this->widgetSchema['config_normal_day']->setOption('type', 'number');
		
		$this->widgetSchema['config_normal_day']->setAttributes(array(
				'min' => 10,
				'max' => 31
		));
		
		$this->widgetSchema['config_full_day']->setOption('type', 'number');
		
		$this->widgetSchema['config_full_day']->setAttributes(array(
				'min' => 10,
				'max' => 31
		));
		
		//
		$attendance = range(1, 7);
		$this->widgetSchema['config_number_attendance'] = new sfWidgetFormSelect(array(
				'choices' => array_combine($attendance, $attendance)
		), array(
				'class' => 'select2',
				'required' => false
		));
		
		$this->validatorSchema['config_number_attendance'] = new sfValidatorChoice(array(
				'choices' => array_combine($attendance, $attendance),
				'required' => false
		));
		
		/*
		 * $this->widgetSchema ['config_closing_date_fee']->setOption ( 'type', 'number' );
		 * $this->widgetSchema ['config_closing_date_fee']->setAttributes ( array (
		 * 'min' => 1,
		 * 'max' => 28
		 * ) );
		 */
		
		$this->widgetSchema['config_start_date_system_fee'] = new psWidgetFormInputDate();
		
		$this->widgetSchema['config_start_date_system_fee']->setAttributes(array(
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required'
		));
		$this->validatorSchema['config_start_date_system_fee'] = new sfValidatorDate(array(
				'required' => true
		));
		
		$closing_date_fees = array(
				'1' => 1
		);
		$this->widgetSchema['config_closing_date_fee'] = new sfWidgetFormSelect(array(
				'choices' => $closing_date_fees
		), array(
				'class' => 'form-control',
				'required' => true
		));
		
		$this->validatorSchema['config_closing_date_fee'] = new sfValidatorChoice(array(
				'choices' => array_keys($closing_date_fees),
				'required' => true
		));
		
		$this->widgetSchema['config_time_receive_valid'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['config_time_receive_valid']->setAttributes(array(
				'class' => 'endTime timepicker',
				'data-mode' => "24h",
				'required' => true
		));
		
		$this->validatorSchema['config_time_receive_valid'] = new sfValidatorTime(array(
				'required' => true
		));
		
		$this->widgetSchema['config_time_cancel_saturday_valid'] = new sfWidgetFormChoice(array(
				'choices' => PreSchool::loadPsTimeCancelSaturday()
		), array(
				'class' => 'form-control'
		));
		$this->validatorSchema['config_time_cancel_saturday_valid'] = new sfValidatorChoice(array(
				'choices' => array_keys(PreSchool::loadPsTimeCancelSaturday())
		));
		/*
		 * $this->widgetSchema ['config_template_export'] = new sfWidgetFormChoice ( array (
		 * 'choices' => array('' => '') + PreSchool::loadPsTemplateExport ()
		 * ), array (
		 * 'class' => 'form-control'
		 * ) );
		 * $this->validatorSchema ['config_template_export'] = new sfValidatorChoice ( array (
		 * 'choices' => array_keys ( PreSchool::$ps_template_export )
		 * ) );
		 */
		
		$this->widgetSchema['is_notication_activities'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['config_push_notication_update_attendance'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->validatorSchema['config_push_notication_update_attendance'] = new sfValidatorChoice(array(
				'choices' => array_keys(array(
						'0' => 0,
						'1' => 1
				)),
				'required' => false
		));
		
		$this->widgetSchema['config_multiple_teacher_process_album'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->validatorSchema['config_multiple_teacher_process_album'] = new sfValidatorChoice(array(
				'choices' => array_keys(array(
						'0' => 0,
						'1' => 1
				)),
				'required' => false
		));
		
		$this->widgetSchema['is_receipt'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadIsSourceReceipt()
		), array(
				'class' => 'radiobox'
		));
		
		$this->validatorSchema['is_receipt'] = new sfValidatorChoice(array(
				'choices' => array_keys(PreSchool::loadIsSourceReceipt()),
				'required' => true
		));
		
		$this->widgetSchema['from_time_notication_activities'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['from_time_notication_activities']->setAttributes(array(
				'class' => 'startTime timepicker',
				'data-mode' => "24h",
				'required' => false
		));
		
		$this->widgetSchema['to_time_notication_activities'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['to_time_notication_activities']->setAttributes(array(
				'class' => 'endTime timepicker',
				'data-mode' => "24h",
				'required' => false
		));
		
		$this->widgetSchema['from_time_notication_attendances'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['from_time_notication_attendances']->setAttributes(array(
				'class' => 'startTime timepicker',
				'data-mode' => "24h",
				'required' => false
		));
		
		$this->widgetSchema['to_time_notication_attendances'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['to_time_notication_attendances']->setAttributes(array(
				'class' => 'endTime timepicker',
				'data-mode' => "24h",
				'required' => false
		));
		
		$this->widgetSchema['from_time_class_delay'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['from_time_class_delay']->setAttributes(array(
				'class' => 'startTime timepicker',
				'data-mode' => "24h",
				'required' => false
		));
		
		$this->widgetSchema['to_time_class_delay'] = new psWidgetFormInputTime();
		
		$this->widgetSchema['to_time_class_delay']->setAttributes(array(
				'class' => 'endTime timepicker',
				'data-mode' => "24h",
				'required' => false
		));
		
		$this->setDefault('to_time_notication_activities', '');
		
		$this->addBootstrapForm();
		
		$this->addPsCustomerFormNotEdit('PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL');
		
		$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
				'model' => 'PsCustomer',
				'required' => true
		));
		
		if (! myUser::credentialPsCustomers('PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL' ) || ! $this->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
