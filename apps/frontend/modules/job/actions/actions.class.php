<?php

/**
 * job actions.
 *
 * @package    Preschool
 * @subpackage job
 * @author     Your name here
 * @version    1.0
 */
class jobActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {  	
    if (!$request->getParameter('sf_culture'))
	  {
	    if ($this->getUser()->isFirstRequest())
	    {
	      $culture = $request->getPreferredCulture(array('en', 'vi'));
	      $this->getUser()->setCulture($culture);
	      $this->getUser()->isFirstRequest(false);
	    }
	    else
	    {
	      $culture = $this->getUser()->getCulture();
	    }
	 
	    $this->redirect('@localized_homepage');
	  }
    $this->categories = Doctrine::getTable('JobeetCategory')->getWithJobs();
  }

  public function executeShow(sfWebRequest $request)
  {
  	$this->job = $this->getRoute()->getObject();
		$this->getUser()->addJobToHistory($this->job);
  }

  public function executeNew(sfWebRequest $request)
  {
    $job = new JobeetJob();
  	$job->setType('full-time');
 
  	$this->form = new JobeetJobForm($job); 
  }

  public function executeCreate(sfWebRequest $request)
  {
	  $this->form = new JobeetJobForm();
	  $this->processForm($request, $this->form);
	  $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->form = new JobeetJobForm($this->getRoute()->getObject());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->form = new JobeetJobForm($this->getRoute()->getObject());
	  $this->processForm($request, $this->form);
	  $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($jobeet_job = $this->getRoute()->getObject(), sprintf('Object jobeet_job does not exist (%s).', $request->getParameter('token')));
    $jobeet_job->delete();

    $this->redirect('job/index');
  }

	public function executePublish(sfWebRequest $request)
	{
	  $request->checkCSRFProtection();
	 
	  $job = $this->getRoute()->getObject();
	  $job->publish();
	 
	  $this->getUser()->setFlash('notice', sprintf('Your job is now online for %s days.', sfConfig::get('app_active_days')));
	 
	  $this->redirect($this->generateUrl('job_show_user', $job));
	}

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind(
	    $request->getParameter($form->getName()),
	    $request->getFiles($form->getName())
	  );
	 
	  if ($form->isValid())
	  {
	    $job = $form->save();
	    $this->redirect($this->generateUrl('job_show', $job));
	  }
  }
  
	public function executeExtend(sfWebRequest $request)
	{
	  $request->checkCSRFProtection();
	 
	  $job = $this->getRoute()->getObject();
	  $this->forward404Unless($job->extend());
	 
	  $this->getUser()->setFlash('notice', sprintf('Your job validity has been extend until %s.', date('m/d/Y', strtotime($job->getExpiresAt()))));
	 
	  $this->redirect($this->generateUrl('job_show_user', $job));
	}

	public function executeSearch(sfWebRequest $request)
	{
	  echo $request->getParameter('query');
	  
	  if (!$query = $request->getParameter('query'))
	  {
	    return $this->forward('job', 'index');
	  }
	 
	  $this->jobs = Doctrine::getTable('JobeetJob')->getForLuceneQuery($query);
	 
	  if ($request->isXmlHttpRequest())
	  {
	    if ('*' == $query || !$this->jobs)
	    {
	      return $this->renderText('No results.');
	    }
	    else
	    {
	      return $this->renderPartial('job/list', array('jobs' => $this->jobs));
	    }
	  }
	}

}
