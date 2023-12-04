<?php
/**
 * PsCmsArticles filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCmsArticlesFormFilter extends BasePsCmsArticlesFormFilter {

	public function configure() {

		// Kiem tra quyen Quan ly tin bai tren he thong
		//$this->addPsCustomerFormFilter ('PS_CMS_ARTICLES_FILTER_SCHOOL');
		
		$this->setPsCustomerFormFilter(true);

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		
		$this->addPsWorkplaceIdFormFilter($ps_customer_id);
		
		if (myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_ADD' ) || myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_EDIT' ) || myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_DELETE' )) {

			$this->widgetSchema ['is_publish'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => '-Select state-' ) + PreSchool::loadCmsArticlesLock () ), array (
					'class' => 'select2',
					'style' => "min-width:200px;" ) );

			$this->validatorSchema ['is_publish'] = new sfValidatorChoice ( array (
					'required' => false,
					'choices' => array (
							'',
							PreSchool::PUBLISH,
							PreSchool::NOT_PUBLISH ) ) );
		} else {

			$this->widgetSchema ['is_publish'] = new sfWidgetFormInputHidden ();

			$this->setDefault ( 'is_publish', PreSchool::PUBLISH );

			$this->validatorSchema ['is_publish'] = new sfValidatorChoice ( array ('required' => false,'choices' => array (PreSchool::PUBLISH) ) );			
			
		}
		
		$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		$param_class = array(
				'ps_school_year_id' => $school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated'=> PreSchool::ACTIVE
		);
		
		$this->widgetSchema ['school_year_id'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['school_year_id'] = new sfValidatorString ( array (
				'required' => true ) );
		
		$this->setDefault ( 'school_year_id', $school_year_id );
		
		if($ps_workplace_id > 0){
			
			$this->widgetSchema['ps_class_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'MyClass',
					'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
					'add_empty' => _ ( '-Select class-' )
			), array(
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _('-Select class-')
			));
			
			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice( array (
					'model' => 'MyClass',
					'required' => false,
					
			) );
		}else{
			
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
									'class' => 'select2',
									'style' => "min-width:200px;",
									'required' => false,
									'data-placeholder' => _ ( '-Select class-' ) ) );
			
			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ();
		}
		
		$this->widgetSchema ['is_access'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select access-' ) + PreSchool::loadCmsArticleAccess () ), array (
				'class' => 'select2',
				'style' => "min-width:200px;" ) );

		$this->validatorSchema ['is_access'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array (
						'',
						PreSchool::ACTIVE,
						PreSchool::NOT_ACTIVE ) ) );

		$this->widgetSchema ['is_global'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select Kidsschool.vn-' ) + PreSchool::loadPsBoolean () ), array (
				'class' => 'select2',
				'style' => "min-width:200px;" ) );

		$this->validatorSchema ['is_global'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array (
						'',
						PreSchool::ACTIVE,
						PreSchool::NOT_ACTIVE ) ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}
	
	/*
	public function addPsClassIdColumnQuery($query, $field, $value) {

		//echo $value; die;
		$a = $query->getRootAlias ();

		$query->andWhereIn ( explode(",",$a.'.ps_class_ids'), $value );
		
		return $query;
	}
	
	*/

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.title) LIKE ? OR LOWER(' . $a . '.note) LIKE ?', array (
					$keywords,
					$keywords ) );
		}
		
		return $query;
	}
	
	
	public function doBuildQuery(array $values) {
		$query = parent::doBuildQuery ( $values );
		$a = $query->getRootAlias ();
		
		if(isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0){
			$query->leftJoin($a.'.PsCmsArticlesClass ctc');
			$query->andWhere('ctc.ps_class_id=?',$values ['ps_class_id']);
		}
		return $query;
	}
	 
}
