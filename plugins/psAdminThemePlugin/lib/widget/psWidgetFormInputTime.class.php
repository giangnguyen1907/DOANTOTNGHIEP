<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormInput represents an HTML text input tag.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormInputText.class.php 30762 2010-08-25 12:33:33Z fabien $
 */
class psWidgetFormInputTime extends sfWidgetFormInput
{
  /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('class', 'form-control');
    $this->setOption('type', 'text');
    
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
  	//if ($value) $value = date("d-m-Y", strtotime($value));
	
  	if (!empty($attributes['required']))
    	$renderContentTag = $this->renderContentTag('div', '<span class="input-group-addon"><i class="icon-append fa fa-clock-o text-danger"></i></span>'.$this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value, 'class' => $this->getOption('class')), $attributes)), array('class' => 'input-group'));
    else
    	$renderContentTag = $this->renderContentTag('div', '<span class="input-group-addon"><i class="icon-append fa fa-clock-o"></i></span>'.$this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value, 'class' => $this->getOption('class')), $attributes)), array('class' => 'input-group'));
  	
  	return $renderContentTag;

    //return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
  }
}
