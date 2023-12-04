<?php
/**
 * PsFeeNewsLetters filter form.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFeeNewsLettersFormFilter extends BasePsFeeNewsLettersFormFilter {
	
	protected $ps_customer_id;
	
	public function configure() {
		
		$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );

		if (! $psHeaderFilter) {

			$ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );

			$ps_school_year_id = $ps_school_year_default->id;

			$ps_customer_id = sfContext::getInstance ()->getUser ()->getPsCustomerId ();
		
		} else {
			$ps_school_year_id  = $psHeaderFilter ['ps_school_year_id'];
			$ps_customer_id 	= $psHeaderFilter ['ps_customer_id'];
		}
		
		$this->ps_customer_id 	= $ps_customer_id;
		
		$this->addPsWorkplaceIdFormFilter ( $ps_customer_id, null );
		
		$this->addPsYearMonthFormFilter ( $ps_school_year_id, false );

		$this->widgetSchema ['is_public'] = new sfWidgetFormChoice ( array (
				'choices' => array ('' => '-Select state-') + PreSchool::loadCmsArticles ()
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;"
		) );

		$this->validatorSchema ['is_public'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array (
						'',
						PreSchool::NOT_PUBLISH,
						PreSchool::PUBLISH
				)
		) );
	}
	
	public function addPsYearMonthColumnQuery($query, $field, $value) {
		
		$a = $query->getRootAlias ();
		
		$query->andWhere ( $a . '.ps_year_month = ?', $value );
		
		return $query;
	}
	
	public function doBuildQuery(array $values) {
		
		$query = parent::doBuildQuery ( $values );
		$a = $query->getRootAlias ();
		
		$query->addSelect("wp.title AS wp_title");
		
		if ($this->ps_customer_id > 0) {
			//$query->andWhere ( 'wp.ps_customer_id = ?', $this->ps_customer_id );
			$query->innerJoin ( $a . '.PsWorkPlaces wp With wp.ps_customer_id = ?', $this->ps_customer_id);
		} else {
			$query->innerJoin ( $a . '.PsWorkPlaces wp' );
		}
		
		return $query;
	}
}
