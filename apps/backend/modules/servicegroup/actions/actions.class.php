<?php
require_once dirname ( __FILE__ ) . '/../lib/servicegroupGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/servicegroupGeneratorHelper.class.php';

/**
 * servicegroup actions.
 *
 * @package backend
 * @subpackage servicegroup
 * @author Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
// class servicegroupActions extends autoServicegroupActions
class servicegroupActions extends autoServicegroupActions {

	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter ( 'iorder' );

		if (! count ( $iorder )) {
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'Update failed' ) );
		} else {

			$conn = Doctrine_Manager::connection ();
			try {

				$conn->beginTransaction ();

				foreach ( $iorder as $key => $value ) {
					if (! is_numeric ( $value )) {
						$this->getUser ()
							->setFlash ( 'error', $this->getContext ()
							->getI18N ()
							->__ ( 'Is not a number' ) );
						break;
					} else {

						$obj = Doctrine::getTable ( 'ServiceGroup' )->findOneById ( $key );

						if (myUser::checkAccessObject ( $obj, 'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' )) {
							$obj->setIorder ( $value );
							$obj->setUserUpdatedId ( $this->getUser ()
								->getUserId () );
							$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
							$obj->save ();
						}
					}
				}

				$this->getUser ()
					->setFlash ( 'notice', $this->getContext ()
					->getI18N ()
					->__ ( 'The item was updated successfully.' ) );

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

		$this->redirect ( '@service_group' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->service_group = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->service_group, 'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->service_group );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->service_group = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->service_group, 'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->service_group );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		// Kiem tra du lieu lien quan
		$ids = array (
				$this->getRoute ()
					->getObject ()
					->getId () );

		$ps_objects = Doctrine_Query::create ()->select ( 'id' )
			->from ( 'Service' )
			->whereIn ( 'service_group_id', $ids )
			->execute ();

		if (count ( $ps_objects ) > 0) {

			$this->getUser ()
				->setFlash ( 'error', 'The item has not been remove due have data related.' );

			$this->redirect ( '@service_group' );
		} elseif ($this->getRoute ()
			->getObject ()
			->delete ()) {

			// parent::executeDelete($request);

			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );

			$this->redirect ( '@service_group' );
		}

		// $this->redirect('@service_group');
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		/*
		 * Khoa chuc nang nay
		 * $ids = $request->getParameter('ids');
		 * $records = Doctrine_Query::create()
		 * ->from('ServiceGroup')
		 * ->whereIn('id', $ids)
		 * ->whereIn('id', $ids);
		 * if (!myUser::isAdministrator())
		 * $records->andWhere('ps_customer_id = ?', myUser::getPscustomerID());
		 * $records->execute();
		 * foreach ($records as $record)
		 * {
		 * $record->delete();
		 * }
		 * $this->getUser()->setFlash('notice', 'The selected items have been deleted successfully.');
		 */
		$this->redirect ( '@service_group' );
	}
}
