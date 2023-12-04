<?php
/**
 * @project	: Preschool
 * @descr:
 *
 * @filename: myValidatorFile.php
 * @author	: NguyenChienThang(ntsc279@hotmail.com)
 * @copyright:  Copyright (C) Aug 29, 2011 - Intelligence Software Company
 * @version	: 1.0
 */
class myValidatorFile extends sfValidatorFile {

	protected function configure($options = array(), $messages = array()) {

		if (! ini_get ( 'file_uploads' )) {
			throw new LogicException ( sprintf ( 'Unable to use a file validator as "file_uploads" is disabled in your php.ini file (%s)', get_cfg_var ( 'cfg_file_path' ) ) );
		}

		$this->addOption ( 'max_size' );
		$this->addOption ( 'mime_types' );
		$this->addOption ( 'mime_type_guessers', array (
				array (
						$this,
						'guessFromFileinfo' ),
				array (
						$this,
						'guessFromMimeContentType' ) /* array($this, 'guessFromFileBinary'), */
		) );
		$this->addOption ( 'mime_categories', array (
				'web_images' => array (
						'image/jpeg',
						'image/pjpeg',
						'image/png',
						'image/x-png',
						'image/gif' ),
				'web_excel' => array (
						'application/vnd.ms-excel',
						'application/msexcel',
						'application/x-msexcel',
						'application/x-ms-excel',
						'application/x-excel',
						'application/x-dos_ms_excel',
						'application/xls',
						'application/x-xls',
						'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) ) 
		);

		$this->addOption ( 'validated_file_class', 'sfValidatedFile' );

		$this->addOption ( 'path', null );

		$this->addMessage ( 'max_size', 'File is too large (maximum is %max_size% bytes).' );
		$this->addMessage ( 'mime_types', 'Invalid mime type (%mime_type%).' );
		$this->addMessage ( 'partial', 'The uploaded file was only partially uploaded.' );
		$this->addMessage ( 'no_tmp_dir', 'Missing a temporary folder.' );
		$this->addMessage ( 'cant_write', 'Failed to write file to disk.' );
		$this->addMessage ( 'extension', 'File upload stopped by extension.' );
	}

	protected function getMimeType($file, $fallback) {

		foreach ( $this->getOption ( 'mime_type_guessers' ) as $method ) {
			$type = call_user_func ( $method, $fallback );

			if (null !== $type && $type !== false) {
				return strtolower ( $type );
			}
		}

		return strtolower ( $fallback );
	}

	/**
	 * Guess the file mime type with PECL Fileinfo extension
	 *
	 * @param string $file
	 *        	The absolute path of a file
	 *        	
	 * @return string The mime type of the file (null if not guessable)
	 */
	protected function guessFromFileinfo($file) {

		return $file;
	}

	/**
	 * Guess the file mime type with mime_content_type function (deprecated)
	 *
	 * @param string $file
	 *        	The absolute path of a file
	 *        	
	 * @return string The mime type of the file (null if not guessable)
	 */
	protected function guessFromMimeContentType($file) {

		/*
		 * if (!function_exists('mime_content_type') || !is_readable($file))
		 * {
		 * return null;
		 * }
		 * return mime_content_type($file);
		 */
		return $file;
	}
}
class sfValidatedFileCustom extends sfValidatedFile {

	private $savedFilename = null;

	// Override sfValidatedFile's save method
	public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777) {

		// This makes sure we use only one savedFilename (it will be the first)
		if ($this->savedFilename === null)
			$this->savedFilename = $file;

		// Let the original save method do its magic :)
		$saved = parent::save ( $this->savedFilename, $fileMode, $create, $dirMode );

		$path_file_root = $this->getPath () . DIRECTORY_SEPARATOR . $saved;

		// Size file
		/*
		 * $size_file = new sfThumbnail(250, 250, false, false, 75, '');
		 * $size_file->loadFile($path_file_root);
		 * $size_file->save($path_file_root, $size_file->getMime());
		 */

		$img = new sfImage ( $path_file_root );

		$img->thumbnail ( 250, 250 );

		$img->setQuality ( 100 );

		$img->saveAs ( $path_file_root );

		return $saved;
	}
}

// Giu nguyen kich thuoc anh
class psValidatedFileCustom extends sfValidatedFile {

	private $savedFilename = null;

	// Override sfValidatedFile's save method
	public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777) {

		// This makes sure we use only one savedFilename (it will be the first)
		if ($this->savedFilename === null)
			$this->savedFilename = $file;

		// Let the original save method do its magic :)
		$saved = parent::save ( $this->savedFilename, $fileMode, $create, $dirMode );

		return $saved;
	}
}
