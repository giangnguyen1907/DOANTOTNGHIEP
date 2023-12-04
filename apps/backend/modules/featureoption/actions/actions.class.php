<?php
require_once dirname(__FILE__) . '/../lib/featureoptionGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/featureoptionGeneratorHelper.class.php';

/**
 * featureoption actions.
 *
 * @package backend
 * @subpackage featureoption
 * @author Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class featureoptionActions extends autoFeatureoptionActions {

	public function executeEdit(sfWebRequest $request) {

		$this->feature_option = $this->getRoute()->getObject();
		
		$this->forward404Unless(myUser::checkAccessObject($this->feature_option, 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL'), sprintf('Object does not exist or you limited access.'));
		
		$this->form = $this->configuration->getForm($this->feature_option);
	
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->feature_option = $this->getRoute()->getObject();
		
		$this->forward404Unless(myUser::checkAccessObject($this->feature_option, 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL'), sprintf('Object does not exist or you limited access.'));
		
		$this->form = $this->configuration->getForm($this->feature_option);
		
		$this->processForm($request, $this->form);
		
		$this->setTemplate('edit');
	
	}

	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter('iorder');
		
		if (! count($iorder)) {
			$this->getUser()->setFlash('error', 'You must at least select one item.');
		} else {
			
			$conn = Doctrine_Manager::connection();
			$conn->beginTransaction();
			
			if (myUser::credentialPsCustomers('PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL')) {
				foreach ($iorder as $key => $value) {
					if (! is_numeric ( $value )) {
						$this->getUser ()
							->setFlash ( 'error', 'Is not a number' );
						break;
					} else {
						$obj = Doctrine::getTable ( 'FeatureOption' )->findOneById ( $key );
						$obj->setIorder ( $value );
						$obj->setUserUpdatedId ( $this->getUser ()
							->getUserId () );
						$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
						$obj->save ();
					}
				}
			} else {
				foreach ( $iorder as $key => $value ) {
					if (! is_numeric ( $value )) {
						$this->getUser ()->setFlash ( 'error', 'Is not a number' );
						break;
					} else {
						$obj = Doctrine::getTable ( 'FeatureOption' )->findOneById ( $key );
						if ($obj->getPsCustomerId () == myUser::getPscustomerID ()) {
							$obj->setIorder ( $value );
							$obj->setUserUpdatedId ( $this->getUser ()
								->getUserId () );
							$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
							$obj->save ();
						}
					}
				}
			}

			$this->getUser ()
				->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'The item was updated successfully.' ) );
			$conn->commit ();
		}
		$this->redirect ( '@feature_option' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL' ), sprintf ( 'Object does not exist or you limited access.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$optionid = $this->getRoute ()->getObject ()->getId();
		
		// Kiem tra xem tieu chi nay da gan chua
		$check_option = Doctrine::getTable('FeatureOptionFeature')->checkOptionFeature($optionid);
		
		if($check_option == 0){
			if ($this->getRoute () ->getObject () ->delete ()) {
				$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
			}
		}else{
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ('The item used, not deleted item.') );
		}
		
		$this->redirect ( '@feature_option' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'FeatureOption' )
			->whereIn ( 'id', $ids );

		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL' ))
			$records->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );

		$_records = $records->execute ();
		
		$daxoa = $khongxoa = 0;
		
		foreach ( $_records as $record ) {
			$optionid = $record->getId();
			// Kiem tra xem tieu chi nay da gan chua
			$check_option = Doctrine::getTable('FeatureOptionFeature')->checkOptionFeature($optionid);
			
			if($check_option == 0){
				$daxoa ++ ;
				$record->delete ();
			}else{
				$khongxoa ++ ;
			}
		}
		
		if($daxoa == 0){
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ('Not deleted item.') );
		}elseif($khongxoa == 0){
			$this->getUser ()->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		}else{
			$this->getUser ()->setFlash ( 'warning',  $this->getContext ()->getI18N ()->__ ('Someone the item is deleted.' ));
		}
		
		$this->redirect ( '@feature_option' );
	}
}
