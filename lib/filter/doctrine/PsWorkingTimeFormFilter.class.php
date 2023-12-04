<?php

/**
 * PsWorkingTime filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsWorkingTimeFormFilter extends BasePsWorkingTimeFormFilter {

	public function configure() {

		// $this->addPsCustomerFormFilter('PS_HR_WORKINGTIME_FILTER_SCHOOL');
		if (! myUser::credentialPsCustomers ( 'PS_HR_WORKINGTIME_FILTER_SCHOOL' )) { // Neu ko co quyen loc du lieu theo truong

			$ps_customer_id = myUser::getPscustomerID ();

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		} else {

			$ps_customer_id = null;
		}

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

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	// public function addPsCustomerIdColumnQuery($query, $field, $value)
	// {
	// if($value > 0){

	// $query->andwhere('ps_customer_id = ? ', $value);

	// }
	// return $query;
	// }

	// public function addPsWorkplaceIdColumnQuery($query, $field, $value)
	// {
	// if($value > 0){

	// $query->andwhere('ps_workplace_id = ? ', $value);

	// }
	// return $query;
	// }
	public function addKeywordsColumnQuery($query, $field, $value) {

		$keywords = PreString::trim ( $value );

		$keywords = PreString::strReplace ( $keywords );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->andWhere ( 'LOWER(title) LIKE ? OR LOWER(note) LIKE ?', array (
					$keywords,
					$keywords ) );
		}

		return $query;
	}
}
