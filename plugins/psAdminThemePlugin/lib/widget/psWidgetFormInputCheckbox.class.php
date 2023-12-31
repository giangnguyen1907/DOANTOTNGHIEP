<?php
/**
 * psWidgetFormInputCheckbox represents an HTML checkbox tag.
 *
* @package    symfony
 * @subpackage widget
 * @author     Nguyen Chien Thang
 * @version    1.0
 */
class psWidgetFormInputCheckbox extends sfWidgetFormInput
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  - value_attribute_value: The "value" attribute value to set for the checkbox
   *
   * @param array  $options     An array of options
   * @param array  $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('value_attribute_value');

    parent::__construct($options, $attributes);
  }

  /**
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('type', 'checkbox');

    if (isset($attributes['value']))
    {
      $this->setOption('value_attribute_value', $attributes['value']);
    }
  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The this widget is checked if value is not null
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (null !== $value && $value !== false)
    {
      $attributes['checked'] = 'checked';
    }

    if (!isset($attributes['value']) && null !== $this->getOption('value_attribute_value'))
    {
      $attributes['value'] = $this->getOption('value_attribute_value');
    }

    return '<div class="checkbox"><label>'.parent::render($name, null, $attributes, $errors).'<span></span</label></div>';
  }
}
