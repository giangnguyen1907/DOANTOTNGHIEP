<?php
/**
 * PsImages filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsImagesFormFilter extends BasePsImagesFormFilter {

	public function configure() {

		$this->widgetSchema ['file_group'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select group-' ) + PreSchool::loadPsFileGroup () ) );

		$this->widgetSchema ['file_group']->setAttributes ( array (
				'class' => 'form-control' ) );

		// $this->validatorSchema['file_group'] = new sfValidatorChoice(array('required' => false, 'choices' => array('','SERVICE', 'FEATURE')));
	}

	// Add virtual_column_name for filter
	public function addFileGroupColumnQuery($query, $field, $value) {

		$alias = $query->getRootAlias ();

		$query->where ( $alias . ".file_group =?", $value );

		return $query;
	}
}
