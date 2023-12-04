<?php
/**
 * Feature form.
 *
 * @package    backend
 * @subpackage form
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureForm extends BaseFeatureForm {

	/**
	 * Bookmarks scheduled for deletion
	 *
	 * @var array
	 */
	protected $scheduledForDeletion = array ();

	public function configure() {

		// $this->loadPsCustomerForm ('PS_SYSTEM_FEATURE_FILTER_SCHOOL' );
		$this->addPsCustomerFormNotEdit ( 'PS_SYSTEM_FEATURE_FILTER_SCHOOL' );

		// Icon fof Feature
		$this->widgetSchema ['ps_image_id'] = new psWidgetFormSelectImage ( array (
				'choices' => Doctrine::getTable ( 'PsImages' )->setChoisPsImagesByGroup ( PreSchool::FILE_GROUP_FEATURE ) ), array (
				'class' => 'select2',
				'style' => "width:100%",
				'placeholder' => _ ( '-Select icon-' ) ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'form-control' ) );

		// $this->widgetSchema->setAttribute('name','feature');

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	/*
	 * public function checkExistOption($validator, $values) {
	 * foreach($values["FeatureBranch"] as $branch){
	 * if($branch['delete'] == "on") {
	 * $isExist = $this->getObject()->checkExistOption($branch['id']);
	 * if($isExist) {
	 * $error = new sfValidatorError($validator, 'Branch must be have not any options ');
	 * throw new sfValidatorErrorSchema($validator, array('delete' => $error));
	 * }
	 * }
	 * }
	 * return $values;
	 * }
	 * public function addNewFields($number) {
	 * $new_featurebranch = new BaseForm();
	 * for ($i = 0; $i <= $number; $i += 1) {
	 * $featurebranch = new FeatureBranch();
	 * $featurebranch -> setFeature($this -> getObject());
	 * $featurebranch_form = new FeatureBranchForm($featurebranch);
	 * $new_featurebranch -> embedForm($i, $featurebranch_form);
	 * }
	 * $this -> embedForm('new', $new_featurebranch);
	 * }
	 * public function bind(array $taintedValues = null, array $taintedFiles = null) {
	 * $new_featurebranchs = new BaseForm();
	 * if (isset($taintedValues['new'])) {
	 * foreach ($taintedValues['new'] as $key => $new_featurebranch) {
	 * // no caption and no filename, remove the empty values
	 * if ($this -> getObject() -> getId()) {
	 * if (!$new_featurebranch['name'] && !$new_featurebranch['mode']) {
	 * unset($taintedValues['new'][$key]);
	 * } else {
	 * $featurebranch = new FeatureBranch();
	 * $featurebranch -> setFeature($this -> getObject());
	 * $featurebranch_form = new FeatureBranchForm($featurebranch);
	 * $new_featurebranchs -> embedForm($key, $featurebranch_form);
	 * }
	 * } else {
	 * $featurebranch = new FeatureBranch();
	 * $featurebranch -> setFeature($this -> getObject());
	 * $featurebranch_form = new FeatureBranchForm($featurebranch);
	 * $new_featurebranchs -> embedForm($key, $featurebranch_form);
	 * }
	 * }
	 * $this -> embedForm('new', $new_featurebranchs);
	 * }
	 * parent::bind($taintedValues, $taintedFiles);
	 * }
	 * protected function doBind(array $values) {
	 * if (isset($values['FeatureBranch'])) {
	 * foreach ($values['FeatureBranch'] as $i => $bookmarkValues) {
	 * if (isset($bookmarkValues['delete']) && $bookmarkValues['id']) {
	 * $this -> scheduledForDeletion[$i] = $bookmarkValues['id'];
	 * }
	 * }
	 * }
	 * parent::doBind($values);
	 * }
	 * protected function doUpdateObject($values) {
	 * if (count($this -> scheduledForDeletion)) {
	 * foreach ($this->scheduledForDeletion as $index => $id) {
	 * unset($values['FeatureBranch'][$index]);
	 * unset($this -> object['FeatureBranch'][$index]);
	 * Doctrine::getTable('FeatureBranch') -> findOneById($id) -> delete();
	 * }
	 * }
	 * $userId = sfContext :: getInstance()->getUser()->getGuardUser()->getId();
	 * if ($this->getObject()->isNew()) {
	 * $this ->getObject()->setUserCreatedId($userId);
	 * $this ->getObject()->setUserUpdatedId($userId);
	 * } else {
	 * $this ->getObject()->setUserUpdatedId($userId);
	 * }
	 * $this -> getObject() -> fromArray($values);
	 * }
	 */

	/*
	 * protected function removeFields() {
	 * unset($this['created_at'], $this['updated_at']);
	 * }
	 */
}
