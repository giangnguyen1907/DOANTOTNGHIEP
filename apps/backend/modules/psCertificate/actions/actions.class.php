<?php
require_once dirname ( __FILE__ ) . '/../lib/psCertificateGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psCertificateGeneratorHelper.class.php';

/**
 * psCertificate actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psCertificate
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psCertificateActions extends autoPsCertificateActions {

	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter ( 'iorder' );

		if (! count ( $iorder )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );
		} else {

			$conn = Doctrine_Manager::connection ();

			$conn->beginTransaction ();

			foreach ( $iorder as $key => $value ) {
				if (! is_numeric ( $value )) {
					$this->getUser ()
						->setFlash ( 'error', 'Is not a number' );
					break;
				} else {
					$obj = Doctrine::getTable ( 'PsCertificate' )->findOneById ( $key );
					$obj->setIorder ( $value );

					$obj->setUserUpdatedId ( $this->getUser ()
						->getUserId () );
					$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );

					$obj->save ();
				}
			}

			$this->getUser ()
				->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'The item was updated successfully.' ) );
			$conn->commit ();
		}
		$this->redirect ( '@ps_certificate' );
	}
}
