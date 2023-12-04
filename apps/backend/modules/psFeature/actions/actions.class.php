<?php
require_once dirname ( __FILE__ ) . '/../lib/psFeatureGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFeatureGeneratorHelper.class.php';

/**
 * psFeature actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psFeature
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureActions extends autoPsFeatureActions {

	// Lay Feature boi Customer
	public function executePsFeatureCustomer(sfWebRequest $request) {

		$ps_customer_id = $request->getParameter ( 'cid' );

		if ($ps_customer_id > 0) {

			$ps_features = Doctrine::getTable ( 'Feature' )->setSQLByCustomerId ( 'id, name AS title', $ps_customer_id )
				->execute ();

			return $this->renderPartial ( 'psFeature/option_select', array (
					'option_select' => $ps_features ) );
		} else {
			exit ( 0 );
		}
	}

	/**
	 * Cap nhat thu tu iorder
	 *
	 * @author ThangNC
	 *        
	 *         *
	 */
	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter ( 'iorder' );

		if (! count ( $iorder )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );
		} else {

			$conn = Doctrine_Manager::connection ();

			$conn->beginTransaction ();

			if (myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_FILTER_SCHOOL' )) { // La Admin he thong
				foreach ( $iorder as $key => $value ) {
					if (! is_numeric ( $value )) {
						$this->getUser ()
							->setFlash ( 'error', 'Is not a number' );
						break;
					} else {
						$obj = Doctrine::getTable ( 'Feature' )->findOneById ( $key );
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
						$this->getUser ()
							->setFlash ( 'error', 'Is not a number' );
						break;
					} else {
						$obj = Doctrine::getTable ( 'Feature' )->findOneById ( $key );
						if ($obj->getPsCustomerId () == myUser::getPscustomerID ()) { // Kiem tra quyen tac dong vao obj
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
		$this->redirect ( '@feature' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->feature = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_SYSTEM_FEATURE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->feature );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->feature = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_SYSTEM_FEATURE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->feature );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}
}
