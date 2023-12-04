<?php
require_once dirname ( __FILE__ ) . '/../lib/psAppGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psAppGeneratorHelper.class.php';

/**
 * psApp actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psApp
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psAppActions extends autoPsAppActions {

	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		// $ids = $request->getParameter('ids');
		$iorders = $request->getParameter ( 'iorder' );

		// print_r($iorders);die;

		if (! count ( $iorders )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );
		} else {

			$conn = Doctrine_Manager::connection ();
			try {

				$conn->beginTransaction ();

				foreach ( $iorders as $key => $value ) {
					if (! is_numeric ( $value )) {
						$this->getUser ()
							->setFlash ( 'error', 'Is not a number' );
						break;
					} else {
						Doctrine_Query::create ()->from ( 'PsApp' )
							->update ()
							->set ( 'iorder', $value )
							->where ( 'id = ?', $key )
							->execute ();
					}
				}

				// $this->getUser()->setFlash('notice', $this->getContext()->getI18N()->__('The item was updated successfully.'));
				$this->getUser ()
					->setFlash ( 'notice', 'The selected items have been update successfully.' );

				$conn->commit ();
			} catch ( Exception $e ) {
				throw new Exception ( $e->getMessage () );
				$conn->rollback ();
				$this->getUser ()
					->setFlash ( 'error', $this->getContext ()
					->getI18N ()
					->__ ( 'Update failed' ) );
			}
		}

		$this->redirect ( '@ps_app' );
	}
}
