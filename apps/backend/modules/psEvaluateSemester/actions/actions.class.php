<?php
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateSemesterGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateSemesterGeneratorHelper.class.php';

/**
 * psEvaluateSemester actions.
 *
 * @package kidsschool.vn
 * @subpackage psEvaluateSemester
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psEvaluateSemesterActions extends autoPsEvaluateSemesterActions {

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_evaluate_semester = $this->form->getObject ();
		$this->form = $this->configuration->getForm ( $this->form->getObject () );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$ps_evaluate_semester = $form->save ();

				if ($ps_evaluate_semester->getTitle () == '') {
					$title = $this->getContext ()
						->getI18N ()
						->__ ( 'Comment study semester' );
					$ps_evaluate_semester->setTitle ( 'Phiếu nhận xét, đánh giá học sinh - Học kỳ I' );
				}

				$ps_evaluate_semester->save ();

				/*
				 * upload file
				 * if ($form->getObject()->isNew()){}else{
				 * $semester_id = $form->getValue('id');
				 * $ps_valueate = Doctrine::getTable('PsEvaluateSemester')->findOneById($semester_id);
				 * $path_file_old = $ps_valueate->getPathFile(); // lay duong dan cua file cu
				 * $file_old = $ps_valueate->getFile(); // lay ten file cu
				 * unlink($path_file_old.$file_old); // xoa bo file cu
				 * }
				 * $file = $form->getValue('file');
				 * $ps_workplace_id = $form->getValue('ps_workplace_id');
				 * $student_id = $form->getValue('student_id');
				 * $filename = $student_id.'_'.time().'_'.$file->getOriginalName();
				 * $file_link = 'EvaluateSemester'.'/'.'CoSoDaoTao_'.$ps_workplace_id.'/'.date('Ym');
				 * $path_file = sfConfig::get('sf_upload_dir') .'/'.'import_data'.'/'.$file_link .'/';
				 * $file->save($path_file . $filename);
				 * $ps_evaluate_semester = $form->save();
				 * if($ps_evaluate_semester -> getTitle() == ''){
				 * $title = $this->getContext ()->getI18N ()->__ ( 'Comment study semester' );
				 * $ps_evaluate_semester -> setTitle($title);
				 * }
				 * $ps_evaluate_semester -> setPathFile($path_file);
				 * $ps_evaluate_semester -> setFile($filename);
				 * $ps_evaluate_semester -> save();
				 */
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
					'object' => $ps_evaluate_semester ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				// $this->redirect('@ps_evaluate_semester_new');

				if (myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {
					$this->redirect ( '@ps_evaluate_semester_new?ps_customer_id=' . $ps_evaluate_semester->getStudent ()
						->getPsCustomerId () . '&ps_workplace_id=' . $form->getValue ( 'ps_workplace_id' ) . '&ps_class_id=' . $form->getValue ( 'ps_class_id' ) );
				} else {
					$this->redirect ( '@ps_evaluate_semester_new' );
				}
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_evaluate_semester_edit',
						'sf_subject' => $ps_evaluate_semester ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	protected function executeBatchPublishReceipts(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsEvaluateSemester' )
			->whereIn ( 'id', $ids )
			->execute ();

		foreach ( $records as $record ) {

			$record->setIsPublic ( 1 );

			$record->save ();
		}

		$this->getUser ()
			->setFlash ( 'notice', $this->getContext ()
			->getI18N ()
			->__ ( 'The selected items have been publish successfully.' ) );

		$this->redirect ( '@ps_evaluate_semester' );
	}
}
