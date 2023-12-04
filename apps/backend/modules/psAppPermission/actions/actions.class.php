<?php
require_once dirname ( __FILE__ ) . '/../lib/psAppPermissionGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psAppPermissionGeneratorHelper.class.php';

/**
 * psAppPermission actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psAppPermission
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psAppPermissionActions extends autoPsAppPermissionActions {

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$ps_app_permission = $form->save ();

				if ($ps_app_permission != null) {

					$sfGuardPermission = Doctrine::getTable ( 'sfGuardPermission' )->findOneBy ( 'ps_app_permission_id', $ps_app_permission->getId () );

					if (! $sfGuardPermission) {
						$sfGuardPermission = new sfGuardPermission ();
					}

					$sfGuardPermission->setName ( $ps_app_permission->getAppPermissionCode () );
					$sfGuardPermission->setTitle ( $ps_app_permission->getTitle () );
					$sfGuardPermission->setPsAppId ( $ps_app_permission->getPsAppId () );
					$sfGuardPermission->setAppPermissionCode ( $ps_app_permission->getAppPermissionCode () );
					$sfGuardPermission->setDescription ( $ps_app_permission->getDescription () );
					$sfGuardPermission->setIorder ( $ps_app_permission->getIorder () );
					$sfGuardPermission->setIsSystem ( $ps_app_permission->getIsSystem () );
					$sfGuardPermission->setPsAppPermissionId ( $ps_app_permission->getId () );
					$sfGuardPermission->save ();
				}
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
					'object' => $ps_app_permission ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_app_permission_new?ps_app_id=' . $form->getObject ()
					->getPsAppId () );
				// $this->redirect('@ps_app_permission_new');
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_app_permission_edit',
						'sf_subject' => $ps_app_permission ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
}
