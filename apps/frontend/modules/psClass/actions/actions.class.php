<?php

/**
 * psClass actions.
 *
 * @package    quanlymamnon.vn
 * @subpackage psClass
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    1.0
 */
class psClassActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->my_classs = Doctrine_Core::getTable('MyClass')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->my_class = Doctrine_Core::getTable('MyClass')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->my_class);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new MyClassForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new MyClassForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($my_class = Doctrine_Core::getTable('MyClass')->find(array($request->getParameter('id'))), sprintf('Object my_class does not exist (%s).', $request->getParameter('id')));
    $this->form = new MyClassForm($my_class);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($my_class = Doctrine_Core::getTable('MyClass')->find(array($request->getParameter('id'))), sprintf('Object my_class does not exist (%s).', $request->getParameter('id')));
    $this->form = new MyClassForm($my_class);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($my_class = Doctrine_Core::getTable('MyClass')->find(array($request->getParameter('id'))), sprintf('Object my_class does not exist (%s).', $request->getParameter('id')));
    $my_class->delete();

    $this->redirect('psClass/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $my_class = $form->save();

      $this->redirect('psClass/edit?
Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362

Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead in C:\thangnc\OneDrive\Jobs\Preschool.vn\lib\vendor\symfony\lib\util\sfToolkit.class.php on line 362
id='.$my_class->getId());
    }
  }
}
