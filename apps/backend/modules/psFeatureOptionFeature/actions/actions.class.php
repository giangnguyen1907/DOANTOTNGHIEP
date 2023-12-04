<?php
require_once dirname ( __FILE__ ) . '/../lib/psFeatureOptionFeatureGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFeatureOptionFeatureGeneratorHelper.class.php';

/**
 * psFeatureOptionFeature actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psFeatureOptionFeature
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureOptionFeatureActions extends autoPsFeatureOptionFeatureActions {

	public function executeIndex(sfWebRequest $request) {
		
// 		$this->helper = new psFeatureOptionFeatureGeneratorHelper();
		
// 		$this->configuration = new psFeatureOptionFeatureGeneratorConfiguration ();
		
		$this->formFilter = new sfFormFilter ();
		
		$this->feature_branch_id = $request->getParameter ( 'branch_id' );

		$this->forward404Unless ( $this->feature_branch_id, sprintf ( 'Object is not exist.' ) );

		$this->feature_branch = Doctrine_Core::getTable ( 'FeatureBranch' )->findOneById ( $this->feature_branch_id );

		$this->forward404Unless ( $this->feature_branch, sprintf ( 'Object is not exist.' ) );

		$this->feature = $this->feature_branch->getFeature ();

		// Check role
		$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->setFilters ( array (
				'feature_branch_id' => $this->feature_branch_id ) );

		$request->setParameter ( 'feature_branch_id', $this->feature_branch_id );

		
		$ps_customer_id = $this->feature->getPsCustomerId ();
		
		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {
			$ps_customer_id = myUser::getPscustomerID ();
		}
		
		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		
		$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
		
		$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
				'required' => true ) ) );
		
		if($ps_customer_id > 0){
			
			$this->formFilter->setWidget ('feature_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Feature',
					'query' => Doctrine::getTable ( 'Feature' )->setSQLByCustomerId ( 'id, name', $ps_customer_id ),
					'add_empty' => _ ( '-Select feature-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _ ( '-Select feature-' ) ) ) );
			
			$this->formFilter->setValidator ('feature_id', new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'Feature',
					'column' => 'id' ) ) );
			
			$this->formFilter->setWidget ('servicegroup_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'ServiceGroup',
					'query' => Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ( 'id, title', $ps_customer_id ),
					'add_empty' => _ ( '-Select servicegroup-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _ ( '-Select servicegroup-' ) ) ) );
			
			$this->formFilter->setValidator ('servicegroup_id', new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'ServiceGroup',
					'column' => 'id' ) ) );
		}else{
			$this->formFilter->setWidget ('feature_id', new sfWidgetFormChoice ( array (
					'choices' => array (
					'' => _ ( '-Select feature-' ) ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select feature-' ) ) ) );
			
			$this->formFilter->setValidator ('feature_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
			
			$this->formFilter->setWidget ('servicegroup_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select servicegroup-' ) ) ), array (
									'class' => 'select2',
									'style' => "min-width:200px;",
									'data-placeholder' => _ ( '-Select servicegroup-' ) ) ) );
			
			$this->formFilter->setValidator ('servicegroup_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
		}
		
		
		$this->formFilter->setWidget ( 'keyword', new sfWidgetFormInput(array(),array(
				'class'=>'form-control',
				'style' => "min-width:200px;",
				'placeholder'=> sfContext::getInstance ()->getI18n () ->__ ( 'Keywords' )
		)) );
		
		$this->formFilter->setValidator ( 'keyword', new sfValidatorString() );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'feature_option[%s]' );
		
		$this->pager = new sfDoctrinePager ( 'FeatureOption', 30 );
		$this->pager->setQuery ( Doctrine::getTable ( 'FeatureOption' )->findAllForAddNew ( $this->feature_branch_id, $ps_customer_id, null,null,null ) );
		$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
		$this->pager->init ();
		$this->feature_options = $this->pager->getResults ();
		
		// get all FeatureOption of User login
		// $this->feature_options = Doctrine::getTable ( 'FeatureOption' )->findAllForAddNew ( $this->feature_branch_id, $ps_customer_id )->execute();

		parent::executeIndex ( $request );
		
		$this->feature_branch2 = $this->feature_branch;

		$this->setTemplate ( 'show' );
	}
	
	// Cap nhat trang thai
	public function executeUpdatedStatus(sfWebRequest $request) {
		
		$fee_id = $request->getParameter ( 'fee_id' );
		
		$records = Doctrine::getTable('FeatureOptionFeature')->findOneById($fee_id);
		
		if (!$records) {
			
			$this->setTemplate('detailError404','psCpanel');
			
		} else {
			$conn = Doctrine_Manager::connection ();
			
			try {
				
				$conn->beginTransaction ();
				
				// Kiem tra xem co quyen thao tac chon truong hay khong
// 				if (! myUser::credentialPsCustomers('PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL')) {
// 					$ps_customer_id = $records->getPsCustomerId ();
// 					if($ps_customer_id != myUser::getPscustomerID()){
// 						$this->setTemplate('detailError404','psCpanel');
// 					}
// 				}
				
				if($records->getIsActivated() == PreSchool::ACTIVE){
					$is_activated = PreSchool::NOT_ACTIVE;
				}else{
					$is_activated = PreSchool::ACTIVE;
				}
				
				$records->setIsActivated ( $is_activated );
				
				$records->save ();
				
				$conn->commit ();
				
				return $this->renderPartial ( 'psFeatureOptionFeature/list_field_boolean', array ('value'=> $records->getIsActivated() ) );
				
			} catch ( Exception $e ) {
				
				throw new Exception ( $e->getMessage () );
				
				$this->logMessage ( "ERROR FEE NEWS LETTERS: " . $e->getMessage () );
				
				$conn->rollback ();
				
				echo $this->getContext ()->getI18N ()->__ ( 'Not roll data' );
				
				exit ();
			}
		}
	}
	
	// load ajax
	public function executeLoadAjax(sfWebRequest $request) {
		
		$ps_customer_id = $request->getParameter ( 'c_id' );
		
		$feature_branch_id = $request->getParameter ( 'feature_branch_id' );
		
		$feature_id = $request->getParameter ( 'feature_id' );
		
		$keyword = $request->getParameter ( 'keyword' );
		
		$servicegroup_id = $request->getParameter ( 'servicegroup_id' );
		
		// Update number_option cua bang Feature Branch
//		$this->updateNumberFeatureBranch ( $feature_branch_id );
		
//		$this->pager = new sfDoctrinePager ( 'FeatureOption', 10 );
// 		$this->pager->setQuery ( Doctrine::getTable ( 'FeatureOption' )->findAllForAddNew ( $feature_branch_id, $ps_customer_id, $feature_id, $servicegroup_id,$keyword ) );
// 		$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
// 		$this->pager->init ();
// 		$feature_options = $this->pager->getResults ();
		
		$feature_options = Doctrine::getTable ( 'FeatureOption' )->findAllForAddNew ( $feature_branch_id, $ps_customer_id, $feature_id, $servicegroup_id,$keyword )->execute();
		
		return $this->renderPartial ( 'psFeatureOptionFeature/tpl_custom/table_list_option', array ('feature_options' => $feature_options ) );
	
	}
	
	public function executeSaveOptionFeature(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$branch_id = $request->getParameter ( 'branch_id' );

		$this->forward404Unless ( $branch_id, sprintf ( 'Object is not exist.' ) );

		$ids = $request->getParameter ( 'ids' );

		// Check ids
		if (! $ids) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );

			$this->redirect ( '@ps_feature_option_feature_branch?branch_id=' . $branch_id );
		}

		$this->feature_branch = Doctrine_Core::getTable ( 'FeatureBranch' )->findOneById ( $branch_id );

		$this->forward404Unless ( $this->feature_branch, sprintf ( 'Object is not exist.' ) );

		$this->feature = $this->feature_branch->getFeature ();

		// Kiem tra quyen tac dong
		$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$type = $request->getParameter ( 'type' );

		$validator = new sfValidatorDoctrineChoice ( array (
				'multiple' => true,
				'model' => 'FeatureOption' ) );

		$conn = Doctrine_Manager::connection ();

		try {
			
			$error_feature = $success_feature = 0;
			
			$conn->beginTransaction ();

			$ids = $validator->clean ( $ids );

			foreach ( $ids as $feature_option_id ) {

				if ( $type [$feature_option_id]  != '') {
					
					$success_feature ++;
					
					$obj = Doctrine_Core::getTable ( 'FeatureOptionFeature' )->findOneByFeatureBranchIdAndFeatureOptionId ( $branch_id, $feature_option_id );

					if (! $obj) {
						$obj = new FeatureOptionFeature ();
					}

					$obj->setType ( $type [$feature_option_id] );
					$obj->setOrderBy ( Doctrine_Core::getTable ( 'FeatureOptionFeature' )->getMaxOrderBy ( $branch_id ) + 1 );
					$obj->setFeatureBranchId ( $branch_id );
					$obj->setFeatureOptionId ( $feature_option_id );

					$obj->setUserCreatedId ( $this->getUser ()
						->getUserId () );
					$obj->setUserUpdatedId ( $this->getUser ()
						->getUserId () );
					$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );

					$obj->save ();
					
				} else {
					
					$error_feature ++ ;
					
					//$this->getUser () ->setFlash ( 'error', $this->getContext () ->getI18N () ->__ ( 'Error update failed' ) );

					//break;
					
				}
			}
			
			// Update number_option cua bang Feature Branch
			$this->updateNumberFeatureBranch ( $branch_id );

			$conn->commit ();
		} catch ( Exception $e ) {

			throw new Exception ( $e->getMessage () );

			$conn->rollback ();

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'Update failed' ) );
		}
		
		if($success_feature == 0){
			
			$this->getUser () ->setFlash ( 'error', $this->getContext () ->getI18N () ->__ ( 'Error update failed' ) );
		
		}elseif($error_feature == 0){
			
			$this->getUser ()->setFlash ( 'notice', 'The item was created successfully.' . ' You can add another one below.' );
			
		}else{
			
			$this->getUser () ->setFlash ( 'warning', $this->getContext () ->getI18N () ->__ ( 'Someone item not updated.' ) );
			
		}
		
		$this->redirect ( '@ps_feature_option_feature_branch?branch_id=' . $branch_id );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_feature_option_feature = $this->getRoute () ->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $ps_feature_option_feature ) ) );

		$feature_branch_id = $ps_feature_option_feature->getFeatureBranchId ();

		$this->forward404Unless ( $feature_branch_id, sprintf ( 'Object is not exist.' ) );

		$feature_branch = $ps_feature_option_feature->getFeatureBranch ();

		$this->forward404Unless ( $feature_branch, sprintf ( 'Object is not exist.' ) );

		$feature = $feature_branch->getFeature ();

		$this->forward404Unless ( myUser::checkAccessObject ( $feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Check StudentFeature da su dung chua
		if ($ps_feature_option_feature->getNumberRecordStudentFeature () > 0) {
			$this->getUser ()
				->setFlash ( 'danger', 'This evaluation is being used. Can not delete.' );
		} else {

			if ($ps_feature_option_feature->delete ()) {

				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}

			// Update number_option cua bang Feature Branch
			$this->updateNumberFeatureBranch ( $feature_branch_id );

			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_feature_option_feature_branch?branch_id=' . $feature_branch_id );
	}

	public function executeBatch(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->feature_branch = Doctrine_Core::getTable ( 'FeatureBranch' )->findOneById ( $request->getParameter ( 'branch_id' ) );

		$this->forward404Unless ( $this->feature_branch, sprintf ( 'Object is not exist.' ) );

		$this->feature = $this->feature_branch->getFeature ();

		// Kiem tra quyen tac dong
		$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		if (! $ids = $request->getParameter ( 'ids' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );

			$this->redirect ( '@ps_feature_option_feature_branch?branch_id=' . $request->getParameter ( 'branch_id' ) );
		}

		if (! $action = $request->getParameter ( 'batch_action' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must select an action to execute on the selected items.' );

			$this->redirect ( '@ps_feature_option_feature_branch?branch_id=' . $request->getParameter ( 'branch_id' ) );
		}

		if (! method_exists ( $this, $method = 'execute' . ucfirst ( $action ) )) {
			throw new InvalidArgumentException ( sprintf ( 'You must create a "%s" method for action "%s"', $method, $action ) );
		}

		if (! $this->getUser ()
			->hasCredential ( $this->configuration->getCredentials ( $action ) )) {
			$this->forward ( sfConfig::get ( 'sf_secure_module' ), sfConfig::get ( 'sf_secure_action' ) );
		}

		$validator = new sfValidatorDoctrineChoice ( array (
				'multiple' => true,
				'model' => 'FeatureOptionFeature' ) );
		try {
			// validate ids
			$ids = $validator->clean ( $ids );

			// execute batch
			$this->$method ( $request );
		} catch ( sfValidatorError $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items as some items do not exist anymore.' );
		}

		$this->redirect ( '@ps_feature_option_feature_branch?branch_id=' . $request->getParameter ( 'branch_id' ) );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'FeatureOptionFeature' )
			->whereIn ( 'id', $ids )
			->execute ();
		$true = $false = 0;
		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );
			
			$check_data = Doctrine::getTable('StudentFeature') -> getNumberRecordStudentFeatureByFOFeatureId($record->getId());
			if($check_data <= 0){
				$true ++ ;
				$record->delete ();
			}else{
				$false ++ ;
			}
		}

		// Update number_option cua bang Feature Branch
		$feature_branch_id = $request->getParameter ( 'branch_id' );
		$this->updateNumberFeatureBranch ( $feature_branch_id );
		// Neu tat ca du lieu duoc xoa
		if($true > 0 && $false == 0){
			$this->getUser ()->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		}elseif($true == 0 && $false > 0){ // Khong co du lieu nao duoc xoa
			$error_feature_option = $this->getContext ()->getI18N ()->__ ( 'Not deleted item feature option' );
			$this->getUser ()->setFlash ( 'error', $error_feature_option );
		}else{ // Mot so du lieu bi xoa
			$danger_feature_option = $this->getContext ()->getI18N ()->__ ( 'Someone item not deleted' );
			$this->getUser ()->setFlash ( 'danger', $danger_feature_option );
		}
		$this->redirect ( '@ps_feature_option_feature_branch?branch_id=' . $feature_branch_id );
	}

	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter ( 'order_by' );

		if (! count ( $iorder )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );
		} else {

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();

				foreach ( $iorder as $key => $value ) {
					if (! is_numeric ( $value )) {
						$this->getUser ()
							->setFlash ( 'error', 'Is not a number' );
						break;
					} else {
						$obj = Doctrine::getTable ( 'FeatureOptionFeature' )->find ( $key );
						$obj->setOrderBy ( $value );
						$obj->setUserUpdatedId ( $this->getUser ()
							->getUserId () );
						$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
						$obj->save ();
					}
				}

				// Update number_option cua bang Feature Branch
				$this->updateNumberFeatureBranch ( $request->getParameter ( 'branch_id' ) );
				
				$conn->commit ();

				$this->getUser ()
					->setFlash ( 'notice', $this->getContext ()
					->getI18N ()
					->__ ( 'The item was updated successfully.' ) );
			} catch ( Exception $e ) {
				$conn->rollback ();
				throw new Exception ( $e->getMessage () );
				$this->getUser ()
					->setFlash ( 'error', $this->getContext ()
					->getI18N ()
					->__ ( 'Update failed' ) );
			}

			/*
			 * $conn = Doctrine_Manager :: connection();
			 * try {
			 * $conn->beginTransaction();
			 * foreach ( $iorder as $key => $value ) {
			 * if (!is_numeric($value)) {
			 * $this->getUser()->setFlash('error', 'Is not a number');
			 * break;
			 * } else {
			 * $feature_option_feature = Doctrine::getTable('FeatureOptionFeature')->find($key);
			 * $feature_option_feature->setOrderBy($value);
			 * $feature_option_feature->save();
			 * }
			 * }
			 * $this->getUser()->setFlash('notice', $this->getContext()->getI18N()->__('The item was updated successfully.'));
			 * $conn->commit();
			 * } catch (Exception $e) {
			 * throw new Exception($e->getMessage());
			 * $conn->rollback();
			 * $this->getUser()->setFlash('error', $this->getContext()->getI18N()->__('Update failed'));
			 * }
			 */
		}

		$this->redirect ( '@ps_feature_option_feature_branch?branch_id=' . $request->getParameter ( 'branch_id' ) );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$feature_option_feature = $form->save ();
				print_r ( $feature_option_feature );
				die ();
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message );
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $feature_option_feature ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_feature_option_feature_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_feature_option_feature_edit',
						'sf_subject' => $feature_option_feature ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	// Update number option cua bang FeatureBranch khi insert/delete FeatureOptionFeature	
	public function updateNumberFeatureBranch($branch_id) {

		$number_option = Doctrine_Core::getTable ( 'FeatureOptionFeature' )->getCountBranchOption ( $branch_id );

		$feature_branch = Doctrine::getTable ( 'FeatureBranch' )->updateNumberOptionFeature ( 'id, number_option, updated_at,user_updated_id',$branch_id );
		
		if($feature_branch){
			$feature_branch->setNumberOption ( $number_option );
			$feature_branch->setUpdatedAt ( date ( 'YmdHis' ) );
			$feature_branch->setUserUpdatedId ( $this->getUser ()->getUserId () );
			$feature_branch->save ();
		}
	}
}
