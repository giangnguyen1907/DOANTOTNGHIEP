<?php

/**
 * psExamination module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psExamination
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psExaminationGeneratorConfiguration extends BasePsExaminationGeneratorConfiguration {

	// mac dinh la thong bao da nhan
	public function getFilterDefaults() {

		return array (
				'type' => 'received',
				'ps_customer_id' => myUser::getPscustomerID () );
	}
}
