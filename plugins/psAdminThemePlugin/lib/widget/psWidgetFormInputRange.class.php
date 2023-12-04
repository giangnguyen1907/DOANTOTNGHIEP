<?php
/**
 * psWidgetFormInputRange represents an HTML text input tag.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Nguyen Chien Thang
 */
class psWidgetFormInputRange extends sfWidgetForm
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
    
    $this->addRequiredOption('input_first');
    $this->addRequiredOption('input_last');
    
    $this->addOption('class', 'form-control');

    $this->addOption('template', '%input_first% : %input_last%');
    
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
  	$value = array_merge(array('first' => '', 'last' => ''), is_array($value) ? $value : array());

    return strtr($this->translate($this->getOption('template')), array(
      '%input_first%'      => $this->getOption('input_first')->render($name.'[first]', $value['first'], $attributes),
      '%input_last%'        => $this->getOption('input_last')->render($name.'[last]', $value['last'],$attributes),
    ));
  }
}
