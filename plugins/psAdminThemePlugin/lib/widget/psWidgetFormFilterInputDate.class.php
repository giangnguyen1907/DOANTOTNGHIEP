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
class psWidgetFormFilterInputDate extends sfWidgetFormInput
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
  	if ($value) $value = date("d-m-Y", strtotime($value));

  	
    $renderContentTag = $this->renderContentTag('div', $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value, 'class' => $this->getOption('class')), $attributes)).'<label for="dateselect_filter" class="icon-append fa fa-calendar padding-left-5" rel="tooltip" title="" data-original-title="'.$this->getOption('tooltip').'"></label>', array('class' => 'icon-addon'));
  	
  	return $renderContentTag;
  	

    //return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value, 'class' => $this->getOption('class')), $attributes)).'<label for="dateselect_filter" class="glyphicon glyphicon-calendar no-margin padding-top-15" rel="tooltip" title="" data-original-title="Filter Date"></label>';
  }
}
