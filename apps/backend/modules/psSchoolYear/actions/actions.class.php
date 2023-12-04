<?php
require_once dirname ( __FILE__ ) . '/../lib/psSchoolYearGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psSchoolYearGeneratorHelper.class.php';

/**
 * psSchoolYear actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psSchoolYear
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psSchoolYearActions extends autoPsSchoolYearActions {

	// Lay thoi gian bat dau, ket thuc cua nam hoc
	public function executeStart_EndYear(sfWebRequest $request) {

		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$ym_id = $request->getParameter ( "y_id" );

			if ($ym_id > 0)
				$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $ym_id );

			$yearsDefaultStart = date ( "d-m-Y", strtotime ( $schoolYearsDefault->getFromDate () ) );

			$yearsDefaultEnd = date ( "d-m-Y", strtotime ( $schoolYearsDefault->getToDate () ) );

			$str = '';
			$str .= "<div class='form-group' id='years-start'>";
			$str .= $yearsDefaultStart;
			$str .= "</div>";
			$str .= "<div class='form-group' id='years-end'>";
			$str .= $yearsDefaultEnd;
			$str .= "</div>";

			echo $str;
			die ();
		} else {
			exit ( 0 );
		}
	}

	// Lay cac thang cua nam hoc
	public function executeYearMonth(sfWebRequest $request) {

		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$ym_id = $request->getParameter ( "ym_id" );

			if ($ym_id > 0)
				$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $ym_id );

			$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

			$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

			$year_month = PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd );

			return $this->renderPartial ( 'psSchoolYear/option_select', array (
					'option_select' => $year_month ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->getIsDefault () == PreSchool::ACTIVE) {

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'The item was deleted is default.' ) );
		} else {
			// Kiem tra du lieu rang buoc
			// Kiem tra Khoan phai thu - Receivable
			$ps_receivable = $this->getRoute ()
				->getObject ()
				->getPsReceivable ();

			// Du lieu MyClass
			$ps_myclass = $this->getRoute ()
				->getObject ()
				->getPsMyClass ();

			if (count ( $ps_receivable ) > 0 || count ( $ps_myclass ) > 0) {

				$this->getUser ()
					->setFlash ( 'error', 'The item has not been remove due have data related.' );
			} elseif ($this->getRoute ()
				->getObject ()
				->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}
		}
		$this->redirect ( '@ps_school_year' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		// Kiem tra du lieu rang buoc
		$ps_receivable = Doctrine_Query::create ()->select ( 'id' )
			->from ( 'Receivable' )
			->whereIn ( 'ps_school_year_id', $ids )
			->execute ();

		// Check trong MyClass
		$ps_myclass = Doctrine_Query::create ()->select ( 'id' )
			->from ( 'MyClass' )
			->whereIn ( 'school_year_id', $ids )
			->execute ();

		if (count ( $ps_receivable ) <= 0 || count ( $ps_myclass ) <= 0) {
			$this->getUser ()
				->setFlash ( 'error', 'The selected items has not been remove due have data related.' );
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsSchoolYear' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'is_default = ?', PreSchool::NOT_ACTIVE )
				->execute ();
			foreach ( $records as $record ) {
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $record ) ) );

				$record->delete ();
			}

			$this->getUser ()
				->setFlash ( 'notice', 'The selected items and not default have been deleted successfully.' );
		}

		$this->redirect ( '@ps_school_year' );
	}
}
