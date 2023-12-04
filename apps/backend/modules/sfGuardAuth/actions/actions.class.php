<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once (sfConfig::get ( 'sf_plugins_dir' ) . '/sfDoctrineGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');
/**
 *
 * @package symfony
 * @subpackage plugin
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardAuthActions extends BasesfGuardAuthActions {

	public function executeSignin($request) {

		$user = $this->getUser ();
		if ($user->isAuthenticated ()) {
			return $this->redirect ( '@homepage' );
		}

		$class = sfConfig::get ( 'app_sf_guard_plugin_signin_form', 'sfGuardFormSignin' );

		$this->form = new $class ();

		if ($request->isMethod ( 'post' )) {

			$this->form->bind ( $request->getParameter ( 'signin' ) );

			if ($this->form->isValid ()) {
				$values = $this->form->getValues ();
				$this->getUser ()->signin ( $values ['user'], array_key_exists ( 'remember', $values ) ? $values ['remember'] : false );

				// always redirect to a URL set in app.yml
				// or to the referer
				// or to the homepage
				$signinUrl = sfConfig::get ( 'app_sf_guard_plugin_success_signin_url', $user->getReferer ( $request->getReferer () ) );

				$this->setCredentialForTeacher ();

				return $this->redirect ( '' != $signinUrl ? $signinUrl : '@homepage' );
			}

			// $this->getUser()->setFlash('error', 'Login system.');

			// $signinUrl = sfConfig :: get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));

			// return $this->redirect($signinUrl);
		} else {

			if ($request->isXmlHttpRequest ()) {
				$this->getResponse ()->setHeaderOnly ( true );
				$this->getResponse ()->setStatusCode ( 401 );

				return sfView::NONE;
			}

			// if we have been forwarded, then the referer is the current URL
			// if not, this is the referer of the current request
			$user->setReferer ( $this->getContext ()->getActionStack ()->getSize () > 1 ? $request->getUri () : $request->getReferer () );

			$module = sfConfig::get ( 'sf_login_module' );
			if ($this->getModuleName () != $module) {

				return $this->redirect ( $module . '/' . sfConfig::get ( 'sf_login_action' ) );
			}

			$this->getResponse ()->setStatusCode ( 401 );
		}

	}

	public function executeSignout($request) {

		$this->getUser ()->getAttributeHolder ()->removeNamespace ( 'myUser' );

		$this->getUser ()->getAttributeHolder ()->clear ();

		$this->getUser ()->getAttributeHolder ()->remove ( 'ADpsCustomerID', null, 'myUser' );

		$this->getUser ()->signOut ();
		
		$signoutUrl = sfConfig::get ( 'app_sf_guard_plugin_success_signout_url', $request->getReferer () );

		$this->redirect ( '' != $signoutUrl ? $signoutUrl : '@homepage' );

	}

	protected function setCredentialForTeacher() {

		$access_ps_logtimes = $this->getUser ()->hasCredential ( array (
				'PS_STUDENT_ATTENDANCE_SHOW',
				'PS_STUDENT_ATTENDANCE_ADD',
				'PS_STUDENT_ATTENDANCE_EDIT',
				'PS_STUDENT_ATTENDANCE_DELETE',
				'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL'
		), false );

		// Neu ko duoc phan quyen xu ly => kiem tra su phan cong
		if (! $access_ps_logtimes) {

			$ps_teacher_class = Doctrine::getTable ( 'PsTeacherClass' )->getAllClassByUserId ( myUser::getUserId () );

			if (count ( $ps_teacher_class ) > 0) {
				$this->getUser ()->addCredentials ( 'PS_STUDENT_ATTENDANCE_SHOW', 'PS_STUDENT_ATTENDANCE_DETAIL', 'PS_STUDENT_ATTENDANCE_ADD', 'PS_STUDENT_ATTENDANCE_EDIT', 'PS_STUDENT_ATTENDANCE_DELETE' );
				// $this->getUser ()->addCredentials('PS_STUDENT_ATTENDANCE_SHOW', 'PS_STUDENT_ATTENDANCE_DETAIL');
				$this->getUser ()->addCredential ( 'PS_STUDENT_ATTENDANCE_TEACHER' );
			}
		}

		// Kiem tra quyen nhan xet mon hoc
		$access_course_comment = $this->getUser ()->hasCredential ( array (
				'PS_STUDENT_SERVICE_COURSE_COMMENT_SHOW',
				'PS_STUDENT_SERVICE_COURSE_COMMENT_DETAIL',
				'PS_STUDENT_SERVICE_COURSE_COMMENT_ADD',
				'PS_STUDENT_SERVICE_COURSE_COMMENT_EDIT',
				'PS_STUDENT_SERVICE_COURSE_COMMENT_DELETE',
				'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL',
				'PS_STUDENT_SERVICE_COURSE_COMMENT_TEACHER'
		), false );

		if (! $access_course_comment) { // Neu khong co quyen thi kiem tra xem giao vien co duoc phan cong giang day mon hoc nao ko

			$ps_service_courses = Doctrine::getTable ( 'PsServiceCourses' )->getServiceCoursesByUserId ( myUser::getUserId () );

			if (count ( $ps_service_courses ) > 0) {
				$this->getUser ()->addCredential ( 'PS_STUDENT_SERVICE_COURSE_COMMENT_TEACHER' ); // Bo sung quyen cho giao vien nay
			}
		}

	}

}