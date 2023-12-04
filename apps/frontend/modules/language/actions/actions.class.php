<?php

/**
 * language actions.
 *
 * @package    Preschool
 * @subpackage language
 * @author     Your name here
 * @version    1.0
 */
class languageActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  
	public function executeChangeLanguage(sfWebRequest $request)
  {
    $form = new sfFormLanguage(
      $this->getUser(),
      array('languages' => array('en', 'vi'))
    );
 
    $form->process($request);
 
    return $this->redirect('@localized_homepage');
  }

}
