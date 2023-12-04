<?php
class ExportHelper extends PHPExcel {

	public $objReader;

	public $objPHPExcel;

	public $object;

	public function __construct($object) {

		parent::__construct ();

		$this->object = $object;
	}

	/**
	 * Load file template export
	 */
	public function loadTemplate($path_filename) {

		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' );

		$this->objPHPExcel = $objReader->load ( $path_filename );
	}

	/**
	 * Xuat file cho client dow ve
	 */
	public function saveAsFile($file_name) {

		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename="' . $file_name . '"' );
		header ( 'Cache-Control: max-age=0' );
		header ( 'Cache-Control: max-age=1' );

		header ( 'Cache-Control: cache, must-revalidate' ); // HTTP/1.1
		header ( 'Pragma: public' ); // HTTP/1.0

		$this->objPHPExcel->setActiveSheetIndex ( 0 );

		$objWriter = PHPExcel_IOFactory::createWriter ( $this->objPHPExcel, 'Excel5' );

		$objWriter->save ( 'php://output' );
		exit ();
	}
}