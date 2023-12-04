<?php

/**
 * PsAdvices form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAdvicesForm extends BasePsAdvicesForm {

	public function configure() {

		if ($this->getObject ()
			->isNew ()) {

			$this->widgetSchema ['feedback_content'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['feedback_content'] = new sfValidatorString ( array (
					'required' => false ) );
		} else {

			// unset($this['category_id'],$this['student_id'],$this['user_id']);
			$this->widgetSchema ['category_id'] = new sfWidgetFormInputHidden ();

			$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();

			$this->widgetSchema ['user_id'] = new sfWidgetFormInputHidden ();
			
			$this->widgetSchema ['date_at'] = new sfWidgetFormInputHidden ();
			
			$this->widgetSchema ['relative_id'] = new sfWidgetFormInputHidden ();
			
			$this->widgetSchema ['title']->setAttribute ( 'readonly', 'readonly' );

			$this->widgetSchema ['content']->setAttribute ( 'readonly', 'readonly' );

			// Lay noi dung feedback_content
			$obj_feedback_content = Doctrine::getTable ( 'PsAdviceFeedbacks' )->findOneByAdviceId ( $this->getObject ()
				->getId () );
			$feedback_content = ($obj_feedback_content) ? $obj_feedback_content->getContent () : '';

			$this->widgetSchema ['feedback_content'] = new sfWidgetFormTextarea ( array (), array (
					'class' => 'form-control' ) );

			$this->setDefault ( 'feedback_content', $feedback_content );

			$this->validatorSchema ['feedback_content'] = new sfValidatorString ( array (
					'required' => false ) );

			$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
					'choices' => PreSchool::getStatus () ), array (
					'class' => 'radiobox' ) );
		}

		$this->setDefault ( 'date_at', date ( 'Y-m-d H:i:s' ) );

		$this->addBootstrapForm ();
	}
}
