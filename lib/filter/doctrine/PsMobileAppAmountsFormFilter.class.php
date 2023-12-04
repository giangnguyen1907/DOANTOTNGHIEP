<?php

/**
 * PsMobileAppAmounts filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMobileAppAmountsFormFilter extends BasePsMobileAppAmountsFormFilter {

	public function configure() {

		// $this->addPsCustomerFormFilter ( 'PS_MOBILE_APP_AMOUNTS_FILTER_SCHOOL' );
		$date_at = date ( 'Ymd' );
		$this->addPsCustomerFormFilter ( 'PS_CMS_ARTICLES_ADD' );
		// $this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array (
		// 'model' => 'PsCustomer',
		// 'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null),
		// 'add_empty' => _('-All school-')
		// ), array('style' =>'min-width:250px;width:100%;', 'class' => 'select2'));

		// $this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
		// 'required' => false,
		// 'model' => 'PsCustomer',
		// 'column' => 'id'
		// ));

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
				'required' => false ) );

		$expire = array (
				'0' => _ ( 'Expire' ),
				'1' => _ ( 'Not Expire' ) );

		$this->widgetSchema ['is_expire'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Is expire-' ) ) + $expire ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Is expire-' ) ) );

		$this->validatorSchema ['is_expire'] = new sfValidatorNumber ( array (
				'required' => false ) );

		$this->widgetSchema ['expiration_date_at'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['expiration_date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Expiration at',
				'required' => false ) );
		$this->validatorSchema ['expiration_date_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		$min_year = Doctrine::getTable ( 'PsSchoolYear' )->getMinYear ();
		$max_year = Doctrine::getTable ( 'PsSchoolYear' )->getMaxYear ();
		$years = range ( $min_year, $max_year );

		$this->widgetSchema ['year'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select year-' ) ) + array_combine ( $years, $years ) ) );

		$this->widgetSchema ['year']->setAttributes ( array (
				'class' => 'select2',
				'placeholder' => 'yyyy',
				'title' => 'Year',
				'required' => false,
				'style' => 'width:200px;' ) );
		$this->validatorSchema ['year'] = new sfValidatorNumber ( array (
				'min' => 0,
				'max' => 2500,
				'required' => false ) );

		$month = array (
				'' => sfContext::getInstance ()->getI18n ()
					->__ ( '- Select month -' ),
				'1' => sfContext::getInstance ()->getI18n ()
					->__ ( 'January' ),
				'2' => sfContext::getInstance ()->getI18n ()
					->__ ( 'February' ),
				'3' => sfContext::getInstance ()->getI18n ()
					->__ ( 'March' ),
				'4' => sfContext::getInstance ()->getI18n ()
					->__ ( 'April' ),
				'5' => sfContext::getInstance ()->getI18n ()
					->__ ( 'May' ),
				'6' => sfContext::getInstance ()->getI18n ()
					->__ ( 'June' ),
				'7' => sfContext::getInstance ()->getI18n ()
					->__ ( 'July' ),
				'8' => sfContext::getInstance ()->getI18n ()
					->__ ( 'August' ),
				'9' => sfContext::getInstance ()->getI18n ()
					->__ ( 'September' ),
				'10' => sfContext::getInstance ()->getI18n ()
					->__ ( 'October' ),
				'11' => sfContext::getInstance ()->getI18n ()
					->__ ( 'November' ),
				'12' => sfContext::getInstance ()->getI18n ()
					->__ ( 'December' ) );

		$this->widgetSchema ['month'] = new sfWidgetFormChoice ( array (
				'choices' => $month ) );

		$this->widgetSchema ['month']->setAttributes ( array (
				'class' => 'select2',
				// 'data-dateformat' => 'm',
				'placeholder' => 'm',
				'title' => 'Month',
				'required' => false ) );
		$this->validatorSchema ['month'] = new sfValidatorNumber ( array (
				'min' => 0,
				'max' => 12,
				'required' => false ) );

		// $ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$this->showUseFields ();
	}

	public function addYearColumnQuery($query, $field, $value) {

		$query->addWhere ( 'YEAR(ama.expiration_date_at) = ?', $value );
		return $query;
	}

	public function addMonthColumnQuery($query, $field, $value) {

		if ($value > 0) {
			$query->addWhere ( 'MONTH(ama.expiration_date_at) = ?', $value );
		}

		return $query;
	}

	public function addIsExpireColumnQuery($query, $field, $value) {

		if ($value > 0) {
			$query->addWhere ( 'DATE_FORMAT(ama.expiration_date_at,"%Y%m%d%H%i%s") >= NOW()' );
		} else if ($value == 0) {
			$query->addWhere ( 'DATE_FORMAT(ama.expiration_date_at,"%Y%m%d%H%i%s") < NOW() OR ama.expiration_date_at IS NULL' );
		}

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'u.ps_customer_id = ?', $value );
		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'wp.id = ?', $value );

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(u.first_name) LIKE ? OR LOWER(u.last_name) LIKE ? OR (LOWER( CONCAT(u.first_name," ", u.last_name) ) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'ps_customer_id',
				'ps_workplace_id',
				'keywords',
				'is_expire',
				// 'amount',
				'expiration_date_at',
				'year',
				'month' ) );
	}
}
