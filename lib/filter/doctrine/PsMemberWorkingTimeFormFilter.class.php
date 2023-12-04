<?php

/**
 * PsMemberWorkingTime filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberWorkingTimeFormFilter extends BasePsMemberWorkingTimeFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_HR_WORKINGTIME_FILTER_SCHOOL' );

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

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->widgetSchema ['start_at'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'From date' ) ) );

		$this->widgetSchema ['start_at']->addOption ( 'tooltip', sfContext::getInstance ()->getI18n ()
			->__ ( 'From date' ) );

		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['stop_at'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['stop_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'To date' ) ) );

		$this->widgetSchema ['stop_at']->addOption ( 'tooltip', sfContext::getInstance ()->getI18n ()
			->__ ( 'To date' ) );

		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsWorkplaceidColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		return $query;
	}
}
