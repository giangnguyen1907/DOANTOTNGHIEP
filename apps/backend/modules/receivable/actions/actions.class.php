<?php
require_once dirname ( __FILE__ ) . '/../lib/receivableGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/receivableGeneratorHelper.class.php';

/**
 * receivable actions.
 *
 * @package backend
 * @subpackage receivable
 * @author Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class receivableActions extends autoReceivableActions {

	// Loc khoan phai thu khac theo trường, cơ sở
	public function executeReceivableCustomer(sfWebRequest $request) {
		if ($request->isXmlHttpRequest ()) {

			$y_id = $request->getParameter ( "y_id" );

			$c_id = $request->getParameter ( "c_id" );

			$w_id = $request->getParameter ( "w_id" );

			$psCustomer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $c_id );

			if ($psCustomer) {

				// Check quyen loc du lieu
				if (myUser::credentialPsCustomers ( 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ) || ($c_id == myUser::getPscustomerID ())) {
					$receivable = Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
							'ps_customer_id' => $c_id,
							'ps_workplace_id' => $w_id,
							'ps_school_year_id' => $y_id
					) )->execute ();
				} else {
					$receivable = array ();
				}
				return $this->renderPartial ( 'option_receivable', array (
						'receivable' => $receivable
				) );
			}
		} else {
			exit ( 0 );
		}
	}
	public function executeIndex(sfWebRequest $request) {

		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' )
			) );
		}

		$ps_receivable_detail_id = $request->getParameter ( 'fbid' );

		if ($ps_receivable_detail_id > 0) {

			$ps_receivable = Doctrine::getTable ( 'Receivable' )->findOneById ( $ps_receivable_detail_id );

			$this->forward404Unless ( $ps_receivable, sprintf ( 'Object does not exist.' ) );
		}

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();
	}
	public function executeCreate(sfWebRequest $request) {
		$choice = $request->getParameter ( 'is_choice' );

		// if form fee
		if ($choice) {
			$this->setLayout ( "layout_nomenu" );

			$this->form = $this->configuration->getForm ();
			$this->receivable = $this->form->getObject ();

			$this->student_id = $request->getParameter ( 'student_id' );
			$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );

			if (! $this->myProcessForm ( $request, $this->form )) {
				$this->setTemplate ( 'forChoiceNew' );
			} else if ($request->getParameter ( 'is_choice' )) {
				$this->redirect ( '@receivable_for_choice?student_id=' . $this->student_id . '&date=' . $this->date );
			}
		} // if form defeaut
		else {

			$this->form = $this->configuration->getForm ();

			$this->receivable = $this->form->getObject ();

			$result = $this->myProcessForm ( $request, $this->form );

			if ($result) {

				$notice = $this->form->getObject ()->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

				if ($request->hasParameter ( '_save_and_add' )) {
					$this->getUser ()->setFlash ( 'notice', $notice . ' You can add another one below.' );

					$this->redirect ( '@receivable_new' );
				}

				// $this->redirect(array('sf_route' => 'receivable_edit', 'sf_subject' => $result));
				$this->redirect ( '@receivable' );
			}

			$this->setTemplate ( 'new' );
		}
	}
	public function executeBatch(sfWebRequest $request) {
		$request->checkCSRFProtection ();

		$is_choice = $request->getParameter ( 'is_choice' );

		if (! $ids = $request->getParameter ( 'ids' )) {
			$this->getUser ()->setFlash ( 'error', 'You must at least select one item.' );

			if ($is_choice) {
				$this->redirect ( '@receivable_for_choice?student_id=' . $request->getParameter ( 'student_id' ) . '&date=' . $request->getParameter ( 'date' ) );
			}

			$this->redirect ( '@receivable' );
		}

		if (! $action = $request->getParameter ( 'batch_action' )) {
			$this->getUser ()->setFlash ( 'error', 'You must select an action to execute on the selected items.' );

			if ($is_choice) {
				$this->redirect ( '@receivable_for_choice?student_id=' . $request->getParameter ( 'student_id' ) . '&date=' . $request->getParameter ( 'date' ) );
			}

			$this->redirect ( '@receivable' );
		}

		if (! method_exists ( $this, $method = 'execute' . ucfirst ( $action ) )) {
			throw new InvalidArgumentException ( sprintf ( 'You must create a "%s" method for action "%s"', $method, $action ) );
		}

		if (! $this->getUser ()->hasCredential ( $this->configuration->getCredentials ( $action ) )) {
			$this->forward ( sfConfig::get ( 'sf_secure_module' ), sfConfig::get ( 'sf_secure_action' ) );
		}

		$validator = new sfValidatorDoctrineChoice ( array (
				'multiple' => true,
				'model' => 'Receivable'
		) );
		try {
			// validate ids
			$ids = $validator->clean ( $ids );

			// execute batch
			$this->$method ( $request );
		} catch ( sfValidatorError $e ) {
			$this->getUser ()->setFlash ( 'error', 'You must at least select one item.' );
		}

		if ($is_choice) {
			$this->redirect ( '@receivable_for_choice?student_id=' . $request->getParameter ( 'student_id' ) . '&date=' . $request->getParameter ( 'date' ) );
		}

		$this->redirect ( '@receivable' );
	}
	protected function executeBatchDelete(sfWebRequest $request) {
		$ids = $request->getParameter ( 'ids' );

		// Lay nhung item khong duoc xoa
		$receivable = Doctrine::getTable ( "Receivable" )->findInReceivableStudent ( $ids );

		// Lay nhung item duoc xoa
		$receivable_del = Doctrine::getTable ( "Receivable" )->findNotInReceivableStudent ( $ids );

		$msg = '';
		if ($receivable_del) {
			foreach ( $receivable_del as $record ) {
				$record->delete ();
			}
			$msg .= $this->getContext ()->getI18N ()->__ ( 'The item was deleted successfully.' );
			$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		if ($receivable) {
			$msg .= ($msg) ? '<br/>' . $this->getContext ()->getI18N ()->__ ( 'The following records contain data:', array (), 'messages' ) . '<br/>' : $this->getContext ()->getI18N ()->__ ( 'The following records contain data:' ) . ':<br/>';
			foreach ( $receivable as $rec ) {
				$msg .= $rec->getTitle () . "<br/>";
			}
			$msg .= $this->getContext ()->getI18N ()->__ ( 'Not allowed to delete' );
		}

		$this->getUser ()->setFlash ( 'coll_message', $msg );

		if ($request->getParameter ( 'is_choice' )) {
			$this->redirect ( '@receivable_for_choice?student_id=' . $request->getParameter ( 'student_id' ) . '&date=' . $request->getParameter ( 'date' ) );
		}

		$this->redirect ( '@receivable' );
	}
	public function executeEdit(sfWebRequest $request) {
		$this->receivable = $this->getRoute ()->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->receivable, 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->receivable ) );

		$this->form = $this->configuration->getForm ( $this->receivable );
	}
	protected function executeBatchUpdateOrder(sfWebRequest $request) {
		$iorder = $request->getParameter ( 'iorder' );

		if (! count ( $iorder )) {
			$this->getUser ()->setFlash ( 'error', 'You must at least select one item.' );
		} else {
			$conn = Doctrine_Manager::connection ();
			try {

				$conn->beginTransaction ();

				if (myUser::credentialPsCustomers ( 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' )) {
					foreach ( $iorder as $key => $value ) {
						if (! is_numeric ( $value )) {
							$this->getUser ()->setFlash ( 'error', 'Is not a number' );
							break;
						} else {
							$obj = Doctrine::getTable ( 'Receivable' )->findOneById ( $key );
							$obj->setIorder ( $value );
							$obj->setUserUpdatedId ( $this->getUser ()->getUserId () );
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
							$obj = Doctrine::getTable ( 'Receivable' )->findOneById ( $key );
							if ($obj->getPsCustomerId () == myUser::getPscustomerID ()) {
								$obj->setIorder ( $value );
								$obj->setUserUpdatedId ( $this->getUser ()->getUserId () );
								$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
								$obj->save ();
							}
						}
					}
				}

				$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'The item was updated successfully.' ) );
				$conn->commit ();
			} catch ( Exception $e ) {
				throw new Exception ( $e->getMessage () );
				$conn->rollback ();
				$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Update failed' ) );
			}
		}

		$this->redirect ( '@receivable' );
	}
	public function executeUpdate(sfWebRequest $request) {
		$this->receivable = $this->getRoute ()->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->receivable, 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->receivable ) );

		$this->form = $this->configuration->getForm ( $this->receivable );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}
	public function executeDelete(sfWebRequest $request) {
		$request->checkCSRFProtection ();

		$receivable = $this->getRoute ()->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $receivable, 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $receivable->getId () ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $receivable
		) ) );

		// Check dang su dung
		// ReceivableStudent, ReceivableTemp
		if ($receivable->getCountReceivableStudent () > 0 || $receivable->getCountReceivableTemp () > 0) {

			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'This item has generated data. Can not delete.' ) );
		} else {

			$notice = $this->getContext ()->getI18N ()->__ ( 'The %value% was deleted successfully.', array (
					"%value%" => $receivable->getTitle ()
			), 'messages' );

			if ($receivable->delete ()) {
				$this->getUser ()->setFlash ( 'notice', $notice );
			}
		}

		$this->redirect ( '@receivable' );
	}

	/**
	 * Hien thi danh sach Khoan phai thu khac de chon
	 */
	public function executeListForFeeReports(sfWebRequest $request) {
		if ($this->getRequest ()->isXmlHttpRequest ()) {

			$ps_date = $request->getParameter ( 'ps_date' ); // Thang xem bao phi

			$ps_workplace_id = $request->getParameter ( 'wp_id' );

			$ps_myclass_id = $request->getParameter ( 'ps_class_id' );

			$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $ps_workplace_id );

			if (! myUser::checkAccessObject ( $ps_workplace, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
				return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
			}

			if ($ps_myclass_id != '') { // Check lai cac ps_myclass_id voi co so
			}

			if ($ps_myclass_id > 0) {

				$my_class = Doctrine::getTable ( 'MyClass' )->findOneById ( $ps_myclass_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $my_class, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				// Lay danh sach cac khoan phai thu cua lop nay boi $ps_date
				$params = array ();
				$params ['ps_customer_id'] = $my_class->getPsCustomerId ();
				$params ['ps_workplace_id'] = $my_class->getPsClassRooms ()->getPsWorkplaceId ();
				$params ['ps_school_year_id'] = $my_class->getSchoolYearId ();
				$params ['is_activated'] = PreSchool::ACTIVE;

				$params ['date'] = $ps_date;
				$params ['ps_myclass_id'] = $ps_myclass_id;

				// Lay danh sach cac khoan phai thu con lai co the chon cua thang
				$receivable_for_fee_report = Doctrine::getTable ( "Receivable" )->getListReceivableSkipTempByParams ( $params );

				return $this->renderPartial ( 'receivable/listForFeeReportsSuccess', array (
						'my_class' => $my_class,
						'ps_date' => $ps_date,
						'params' => $params,
						'receivable_for_fee_report' => $receivable_for_fee_report
				) );
			} else {
				return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
			}
		} else {
			exit ( 0 );
		}
	}

	// *********END NEW VESION CLOUD *************************************************************************************

	/**
	 * Xu ly rieng cho truong hop add gia tri tu form receivable student
	 *
	 * @param sfWebRequest $request
	 */
	public function executeExtend(sfWebRequest $request) {
		$this->form = new ReceivableForm ( new Receivable () );
		if ($this->myProcessForm ( $request, $this->form )) {
			echo "true";
			exit ( 0 );
		}
	}
	protected function myProcessForm(sfWebRequest $request, sfForm $form) {
		$form->bind ( $request->getParameter ( $form->getName () ) );

		if ($form->isValid ()) {

			if ($form->getObject ()->isNew ())
				$this->getUser ()->setFlash ( 'coll_message', 'The item was created successfully.' );
			else
				$this->getUser ()->setFlash ( 'coll_message', 'The item was updated successfully.' );

			// $form->save();

			return $form->save ();
		} else {
			return false;
		}
	}

	/**
	 * Hien thi danh dach colected co the chon chofor hoc sinh
	 */
	public function executeForChoice(sfWebRequest $request) {
		$this->student_id = $request->getParameter ( 'student_id' );

		$this->student = Doctrine::getTable ( "Student" )->find ( $this->student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->student, 'PS_STUDENT_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Kiem tra su ton tai
		if (! $this->student || ($this->student && $this->student->getDeletedAt ())) {
			$this->getUser ()->setFlash ( 'coll_error_message', $this->getContext ()->getI18N ()->__ ( 'This object does not exist' ) );
		}

		$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );

		$this->receivable_form = new MyReceivableListForm ();
		$this->embedForm = new MyReceivableForm ();
		$this->embedForm->getWidgetSchema ()->setNameFormat ( 'receivable[%s]' );

		$this->receivable = Doctrine::getTable ( "Receivable" )->getReceivablesSkipIfExists ( $this->student_id, $this->date );
	}

	/**
	 * Ham load lai du lieu cho cac collected co the chon cho hoc sinh
	 */
	public function executeForChoiceList(sfWebRequest $request) {
		$this->student_id = $request->getParameter ( 'student_id' );
		$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );

		$this->receivable_form = new MyReceivableListForm ();
		$this->embedForm = new MyReceivableForm ();
		$this->embedForm->getWidgetSchema ()->setNameFormat ( 'receivable[%s]' );

		$this->receivable = Doctrine::getTable ( "Receivable" )->getReceivablesSkipIfExists ( $this->student_id, $this->date );
	}

	/**
	 * Them moi collected tren iframe
	 */
	public function executeForChoiceNew(sfWebRequest $request) {
		$this->form = $this->configuration->getForm ();
		$this->receivable = $this->form->getObject ();

		$this->student_id = $request->getParameter ( 'student_id' );
		$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );
	}
	public function executeAjaxUpdate(sfWebRequest $request) {

		/*
		 * if($this->getRequest()->isXmlHttpRequest()) {
		 * $receivable = new Receivable();
		 * $receivable = $receivable->getTable("Receivable")->find($request->getParameter('id'));
		 * $this->forward404Unless($receivable, sprintf('Object receivable does not exist (%s).', $request->getParameter('token')));
		 * $receivable->setAmount($request->getParameter('amount'));
		 * $receivable->save();
		 * echo "true";
		 * } else {
		 * echo "false";
		 * }
		 * exit(0);
		 */
		$this->receivable_form = new MyReceivableListForm ();
		$this->embedForm = new MyReceivableForm ();

		if ($this->getRequest ()->isXmlHttpRequest ()) {

			$obj_receivable = new Receivable ();

			$receivable = $obj_receivable->getTable ( "Receivable" )->find ( $request->getParameter ( 'id' ) );

			if (! $receivable) {
				$this->getUser ()->setFlash ( 'coll_error_message', $this->getContext ()->getI18N ()->__ ( 'This object does not exist' ) );
			} else {

				$this->forward404Unless ( myUser::checkAccessObject ( $receivable, 'PS_STUDENT_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$params = $request->getParameter ( 'receivable' );

				$this->embedForm->bind ( $params ); // Chi de keep lai gia tri tren form

				if (isset ( $params ['new'] [$receivable->getId ()] )) {

					$obj = $obj_bin ['new'] [$receivable->getId ()] = $params ['new'] [$receivable->getId ()];

					$this->getUser ()->setFlash ( 'row_note_error_' . $receivable->getId (), null );
					$this->getUser ()->setFlash ( 'row_error_' . $receivable->getId (), null );
					$this->getUser ()->setFlash ( 'coll_error_message', null );
					$this->getUser ()->setFlash ( 'coll_message', null );

					if (is_numeric ( $obj ['amount'] )) {
						$receivable->setAmount ( $obj ['amount'] );
						$receivable->save ();

						if ($receivable->getId ()) {
							$this->getUser ()->setFlash ( 'coll_message', $this->getContext ()->getI18N ()->__ ( 'Update successfully' ) );
						} else {
							$this->getUser ()->setFlash ( 'coll_error_message', $this->getContext ()->getI18N ()->__ ( 'Update failed' ) );
						}
					} else {
						$this->getUser ()->setFlash ( 'coll_error_message', $this->getContext ()->getI18N ()->__ ( 'Update failed' ) );
						$this->getUser ()->setFlash ( 'row_error_' . $receivable->getId (), $this->getContext ()->getI18N ()->__ ( 'Is not a number' ) );
					}
				}
			}
		}

		$this->student_id = $request->getParameter ( 'student_id' );

		$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );

		$this->embedForm->getWidgetSchema ()->setNameFormat ( 'receivable[%s]' );

		$this->receivable = Doctrine::getTable ( "Receivable" )->getReceivablesSkipIfExists ( $this->student_id, $this->date );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->receivable, 'PS_STUDENT_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		return $this->renderPartial ( 'receivable/list_for_choice', array (
				'receivable' => $this->receivable,
				'receivable_form' => $this->receivable_form,
				'embedForm' => $this->embedForm
		) );
	}
	public function executeAjaxDelete(sfWebRequest $request) {

		// $request->checkCSRFProtection();
		/*
		 * if($this->getRequest()->isXmlHttpRequest()) {
		 * $this->forward404Unless($receivable = $this->getRoute()->getObject(), sprintf('Object receivable does not exist (%s).', $request->getParameter('token')));
		 * $receivable->delete();
		 * echo "true";
		 * } else {
		 * echo "false";
		 * }
		 * exit(0);
		 */

		// $request->checkCSRFProtection();
		$this->getUser ()->setFlash ( 'coll_error_message', null );
		$this->getUser ()->setFlash ( 'coll_message', null );

		if ($this->getRequest ()->isXmlHttpRequest ()) {

			$obj_receivable = new Receivable ();

			$receivable = $obj_receivable->getTable ( "Receivable" )->find ( $request->getParameter ( 'id' ) );

			$this->forward404Unless ( myUser::checkAccessObject ( $receivable, 'PS_STUDENT_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			if ($receivable) {

				$receivableStudent = Doctrine::getTable ( "ReceivableStudent" )->findByReceivableId ( $receivable->getId () );

				if (count ( $receivableStudent )) {
					$this->getUser ()->setFlash ( 'coll_error_message', 'Exist related information. Can not delete' );
				} else {
					$this->getUser ()->setFlash ( 'coll_message', 'Delete successfully' );
					$receivable->delete ();
				}
			} else {
				$this->getUser ()->setFlash ( 'coll_error_message', 'Delete object failed' );
			}
		} else {
			$this->getUser ()->setFlash ( 'coll_error_message', 'Delete object failed' );
		}

		echo "true";

		exit ( 0 );
	}

	/**
	 * Hien thi bang cac khoan phai thu khac
	 */
	public function executeForBatch(sfWebRequest $request) {
		$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );

		$this->receivable_batch_form = new MyReceivableListForm ();
		$this->embedForm = new MyReceivableForm ();
		$this->embedForm->getWidgetSchema ()->setNameFormat ( 'receivable[%s]' );

		$this->receivable = Doctrine::getTable ( "Receivable" )->getReceivablesSkipTemp ( $this->date );
	}
	
	public function executeAddNew(sfWebRequest $request) {
		$this->form = $this->configuration->getForm ();
		$this->receivable = $this->form->getObject ();
		$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );
	}
	
	public function executeFormAdd(sfWebRequest $request) {
		$choice = $request->getParameter ( 'is_choice' );

		// if form fee
		if ($choice) {
			$this->setLayout ( "layout_nomenu" );

			$this->form = $this->configuration->getForm ();
			$this->receivable = $this->form->getObject ();

			$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );

			if (! $this->myProcessForm ( $request, $this->form )) {
				$this->setTemplate ( 'addNew' );
			} else if ($request->getParameter ( 'is_choice' )) {
				$this->redirect ( '@receivable_for_batch?date=' . $this->date );
			}
		} // if form defeaut
		else {
			$this->form = $this->configuration->getForm ();
			$this->receivable = $this->form->getObject ();

			$this->myProcessForm ( $request, $this->form );

			$this->setTemplate ( 'new' );
		}
	}

	/**
	 * insert into ReceivableTemp
	 */
	public function executeAjaxInsertTemp(sfWebRequest $request) {
		$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );

		$this->receivable_batch_form = new MyReceivableListForm ();
		$this->embedForm = new MyReceivableForm ();

		if ($this->getRequest ()->isXmlHttpRequest ()) {

			$obj_receivable = new Receivable ();
			$receivable = $obj_receivable->getTable ( "Receivable" )->find ( $request->getParameter ( 'receivable_id' ) );

			if (! $receivable) {
				$this->getUser ()->setFlash ( 'coll_error_message', $this->getContext ()->getI18N ()->__ ( 'Additional amounts receivable failed' ) );
			} else {

				$params = $request->getParameter ( 'receivable' );
				$this->embedForm->bind ( $params );

				if (isset ( $params ['new'] [$receivable->getId ()] )) {

					$obj = $obj_bin ['new'] [$receivable->getId ()] = $params ['new'] [$receivable->getId ()];

					$this->getUser ()->setFlash ( 'row_error_' . $receivable->getId (), null );
					$this->getUser ()->setFlash ( 'row_note_error_' . $receivable->getId (), null );
					$this->getUser ()->setFlash ( 'coll_message', null );
					$this->getUser ()->setFlash ( 'coll_error_message', null );

					$b_check = true;

					if (! is_numeric ( $obj ['amount'] )) {
						$this->getUser ()->setFlash ( 'row_error_' . $receivable->getId (), $this->getContext ()->getI18N ()->__ ( 'Is not a number' ) );
						$b_check = false;
					}

					if (strlen ( $obj ['note'] ) > 255) {
						$this->getUser ()->setFlash ( 'row_note_error_' . $receivable->getId (), $this->getContext ()->getI18N ()->__ ( 'Tối đa 255 ký tự' ) );
						$b_check = false;
					}

					if ($b_check) {
						$params = $request->getParameter ( 'receivable' );

						$receivable_temp = new ReceivableTemp ();
						$receivable_temp->setReceivableId ( $receivable->getId () );
						$receivable_temp->setAmount ( $obj ['amount'] );
						$receivable_temp->setNote ( $obj ['note'] );
						$receivable_temp->setReceivableAt ( date ( 'Y-m', $this->date ) . '-01' );
						$receivable_temp->save ();

						if ($receivable_temp->getId ()) {
							$this->getUser ()->setFlash ( 'coll_message', $this->getContext ()->getI18N ()->__ ( 'Additional amounts receivable successfully' ) );
						} else {
							$this->getUser ()->setFlash ( 'coll_error_message', $this->getContext ()->getI18N ()->__ ( 'Additional amounts receivable failed' ) );
						}
					} else {
						$this->getUser ()->setFlash ( 'coll_error_message', $this->getContext ()->getI18N ()->__ ( 'Additional amounts receivable failed' ) );
					}
				}
			}
		}

		$this->embedForm->getWidgetSchema ()->setNameFormat ( 'receivable[%s]' );

		$this->receivable = Doctrine::getTable ( "Receivable" )->getReceivablesSkipTemp ( $this->date );

		return $this->renderPartial ( 'receivable/list_for_choice_batch', array (
				'receivable' => $this->receivable,
				'receivable_batch_form' => $this->receivable_batch_form,
				'embedForm' => $this->embedForm
		) );
	}
	public function executeForBatchList(sfWebRequest $request) {
		$this->date = ($request->getParameter ( 'date' )) ? PreSchool::getDate ( $request->getParameter ( 'date' ) ) : mktime ( 0, 0, 0, date ( 'm' ), 1, date ( 'Y' ) );

		$this->receivable_batch_form = new MyReceivableListForm ();
		$this->embedForm = new MyReceivableForm ();
		$this->embedForm->getWidgetSchema ()->setNameFormat ( 'receivable[%s]' );

		$this->receivable = Doctrine::getTable ( "Receivable" )->getReceivablesSkipTemp ( $this->date );
	}
}