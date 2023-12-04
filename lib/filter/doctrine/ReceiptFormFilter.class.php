<?php
/**
 * Receipt filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceiptFormFilter extends BaseReceiptFormFilter {

	public function configure() {

		$this->disableLocalCSRFProtection ();

		$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => "PsSchoolYear",
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => 'min-width:110px;',
				'data-placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( '-Select school year-' ) ) );

		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$ps_school_year_id = $this->getDefault ( 'ps_school_year_id' );

		$this->addPsCustomerFormFilter ( 'PS_FEE_REPORT_FILTER_SCHOOL', true );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {
			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		$param_class = array (
				'ps_school_year_id' => $ps_school_year_id,
				'ps_customer_id'  => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' 	  => PreSchool::ACTIVE);

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'MyClass',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$ps_class_id = $this->getDefault ( 'ps_class_id' );

		$schoolYearsDefault = null;
		if ($ps_school_year_id > 0)
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $ps_school_year_id );

		if (! $schoolYearsDefault) {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->widgetSchema ['ps_year_month'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) );

		// $this->setDefault ( 'ps_year_month', date ( "m-Y" ));

		$this->validatorSchema ['ps_year_month'] = new sfValidatorPass ( array (
				'required' => true ) );

		$this->widgetSchema ['receivable_at'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['receivable_at'] = new sfValidatorPass ();

		$config_closing_date_fee = '01';

		if ($ps_workplace_id > 0) {

			$ps_work_places = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $ps_workplace_id );

			$config_closing_date_fee = $ps_work_places->getConfigClosingDateFee ();

			if ($config_closing_date_fee <= 0)
				$config_closing_date_fee = '01';
			elseif ($config_closing_date_fee < 10)
				$config_closing_date_fee = '0' . $config_closing_date_fee;
		}

		$this->setDefault ( 'receivable_at', $config_closing_date_fee . '-' . $this->getDefault ( 'ps_year_month' ) );

		$this->widgetSchema ['payment_status'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => 'Payment status' ) + PreSchool::loadPsPaymentStatus () ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['payment_status'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::loadPsPaymentStatus () ),
				'required' => false ) );

		$this->widgetSchema ['is_public'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => 'Public status' ) + PreSchool::loadPsIsPublic () ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['is_public'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::loadPsIsPublic () ),
				'required' => false ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ),
				'rel' => 'tooltip',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Input: Student code, Fullname' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( $a . '.ps_customer_id = ?', $value );

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		$query->andWhere ( 'sc.myclass_id = ?', $value );

		return $query;
	}

	public function addReceivableAtColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		return $query;
	}

	// TRang thai thanh toan
	public function addPaymentStatusColumnQuery($query, $field, $value) {

		if ($value == PreSchool::ACTIVE)
			$query->andWhere ( 're.payment_status = ?', $value );
		elseif ($value == PreSchool::NOT_ACTIVE)
			$query->andWhere ( 're.id IS NULL OR re.payment_status = ?', $value );
		return $query;
	}

	// Trang thai Hien thi ra App
	public function addIsPublicColumnQuery($query, $field, $value) {

		if ($value == PreSchool::ACTIVE)
			$query->andWhere ( 're.is_public = ?', $value );
		elseif ($value == PreSchool::NOT_ACTIVE)
			$query->andWhere ( 're.is_public = ?', $value );
		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		$query->addSelect ( 'fr.id AS fr_id,fr.ps_fee_report_no AS ps_fee_report_no,fr.receivable AS receivable,fr.receivable_at AS receivable_at,fr.updated_at AS updated_at,sc.myclass_id AS class_id, re.id AS id ,re.id AS re_id,re.receipt_no AS receipt_no, re.chietkhau AS chietkhau, re.receipt_date AS receipt_date,re.late_payment_amount as late_payment_amount, re.payment_status AS payment_status, re.collected_amount AS collected_amount,re.balance_amount AS balance_amount, re.payment_date AS payment_date, re.is_public AS is_public,re.number_push_notication AS number_push_notication' );

		$query->addSelect ( 'CONCAT(u.first_name, " ", u.last_name) AS updated_by, mc.iorder AS iorder, mc1.name as class_name' );

		$config_closing_date_fee = '01';

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {

			$ps_work_places = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $values ['ps_workplace_id'] );

			$config_closing_date_fee = $ps_work_places->getConfigClosingDateFee ();
		}

		$receivable_at = $config_closing_date_fee . '-' . $values ['ps_year_month'];

		$date_at = date ( 'Ymd', strtotime ( $receivable_at ) );

		$receivable_month = date ( 'Ym', strtotime ( $receivable_at ) );

		$query->leftJoin ( $a . '.PsFeeReports fr With DATE_FORMAT(fr.receivable_at,"%Y%m%d") = ?', date ( 'Ymd', strtotime ( $receivable_at ) ) );

		$query->leftJoin ( 'fr.UserUpdated u' );

		$query->leftJoin ( $a . '.Receipt re With DATE_FORMAT(re.receipt_date,"%Y%m%d") = ?', date ( 'Ymd', strtotime ( $receivable_at ) ) );
		
		$query->leftJoin ( $a.'.MyClass mc1' );

		/*
		 * $query->innerJoin ( $a . '.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= ? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array (
		 * $date_at,
		 * $date_at ) );
		 */

		$query->innerJoin ( $a . '.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m") <= ? AND (sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array (
				$receivable_month,
				$date_at ) );

		$query->leftJoin ( 'sc.MyClass mc' );

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {

			//$query->innerJoin ( 'mc.PsClassRooms cr With cr.ps_workplace_id = ?', $values ['ps_workplace_id'] );
			
			$query->andWhere ( 'mc.ps_workplace_id = ?', $values ['ps_workplace_id'] );
			
		}

		/*
		 * $query->andWhereIn ( 'sc.type', array (
		 * PreSchool::SC_STATUS_OFFICIAL,
		 * PreSchool::SC_STATUS_TEST,
		 * PreSchool::SC_STATUS_PAUSE,
		 * PreSchool::SC_STATUS_HOLD
		 * ) );
		 */

		// Hiển thị tất cả các trạng thái. Nhưng khi xử lý tạo phiếu thì chỉ lấy đang học, học thử
		$query->andWhere ( 'sc.type != ?', PreSchool::NOT_IN_CLASS );

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

			$query->andWhere ( 'sc.myclass_id = ?', $values ['ps_class_id'] );
		}

		$keywords = (isset($values ['keywords'])) ?PreString::trim ( $values ['keywords']) : '';

		if ($keywords != '') {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.student_code) LIKE ? OR LOWER(' . $a . '.first_name) LIKE ? OR LOWER(' . $a . '.last_name) LIKE ? OR LOWER(re.receipt_no) LIKE ? OR LOWER( CONCAT(' . $a . '.first_name," ", ' . $a . '.last_name) ) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		// $query->orderBy ( 's.student_code, s.last_name, s.first_name, mc.iorder' );

		return $query;
	}
}
