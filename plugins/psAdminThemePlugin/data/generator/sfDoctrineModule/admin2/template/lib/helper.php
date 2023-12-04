[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: helper.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class Base<?php echo ucfirst($this->getModuleName()) ?>GeneratorHelper extends sfModelGeneratorHelper
{
  public function getUrlForAction($action)
  {
    return 'list' == $action ? '<?php echo $this->params['route_prefix'] ?>' : '<?php echo $this->params['route_prefix'] ?>_'.$action;
  }
  
  public function linkToCancel($params)
  {
    return '<button type="button" class="btn btn-default btn-sm btn-psadmin btn-cancel" data-dismiss="modal"><i class="fa-fw fa fa-ban"></i> '.__($params['label'], array(), 'sf_admin').'</button>';
  }
  
  public function linkToNew($params)
  {
    return link_to('<i class="fa-fw fa fa-plus"></i> '.__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('new'), array('class' => 'btn btn-default btn-success btn-sm btn-psadmin'));
  }

  public function linkToConfig($params)
  {
    return link_to('<i class="fa-fw fa fa-cogs"></i> '.__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('new'), array('class' => 'btn btn-default btn-success btn-sm btn-psadmin'));
  }

  public function linkToDetail($object, $params)
  {
    return link_to('<i class="fa-fw fa fa-eye txt-color-blue" title="'.__($params['label'], array(), 'sf_admin').'"></i>', $this->getUrlForAction('detail'), $object, array('class' => 'btn btn-xs btn-default', 'data-backdrop' => 'static', 'data-toggle' => 'modal', 'data-target' => '#remoteModal'));
  }

  public function linkToEdit($object, $params)
  {
    return link_to('<i class="fa-fw fa fa-pencil txt-color-orange" title="'.__($params['label'], array(), 'sf_admin').'"></i>', $this->getUrlForAction('edit'), $object, array('class' => 'btn btn-xs btn-default btn-edit-td-action'));
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }
	
	  $label = '<i class="fa-fw fa fa-times txt-color-red" title="'.__($params['label'], array(), 'sf_admin').'"></i>';
	
    return link_to($label, $this->getUrlForAction('delete'), $object, array('method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'], 'class' => 'btn btn-xs btn-default pull-right'));
  }

  public function linkToFormDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }
  
    $label = '<i class="fa-fw fa fa-trash-o" title="'.__($params['label'], array(), 'sf_admin').'"></i> ';
  
    return link_to($label.__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('delete'), $object, array('method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'], 'class' => 'btn btn-default btn-danger btn-sm btn-psadmin btn-delete hidden-xs'));
  }

  public function linkToList($params)
  {
    
    $label = '<i class="fa-fw fa fa-list-ul" title="'.__($params['label'], array(), 'sf_admin').'"></i> ';

    return link_to($label.__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('list'), array('class' => 'btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left'));
  }

  public function linkToSave($object, $params)
  {
    $label = '<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="'.__($params['label'], array(), 'sf_admin').'"></i> ';

    return '<button type="submit" class="btn btn-default btn-success btn-sm btn-psadmin">'.$label.__($params['label'], array(), 'sf_admin').'</button>';
  }

  public function linkToSaveAndAdd($object, $params)
  {
    $label = '<i class="fa-fw fa fa-cloud-upload" aria-hidden="true" title="'.__($params['label'], array(), 'sf_admin').'"></i> ';

    return '<button type="submit" value="'.__($params['label'], array(), 'sf_admin').'" name="_save_and_add" class="btn btn-default btn-success btn-sm btn-psadmin">'.$label.__($params['label'], array(), 'sf_admin').'</button>';
  }

  public function linkToFilterReset() {
    
    return link_to('<i class="fa-fw fa fa-refresh"></i> '.__('Reset', array(), 'sf_admin'), $this->getUrlForAction('collection'), array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post', 'class' => 'btn btn-sm btn-default btn-filter-reset btn-psadmin'));
  }

  public function linkToFilterSearch()
  {
    return '<button type="submit" rel="tooltip" data-placement="bottom" data-original-title="'.__('Filter', array(), 'sf_admin').'" class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin" ><i class="fa-fw fa fa-search"></i></button>';
  }

}

