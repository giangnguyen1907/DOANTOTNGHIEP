<?php
require_once dirname ( __FILE__ ) . '/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';

sfCoreAutoload::register ();

class ProjectConfiguration extends sfProjectConfiguration {
	
	public function setup() {
		
		// $this->setWebDir($this->getRootDir().'/truongnet');
		$this->setWebDir ( $this->getRootDir () . '/web' );
		
		$this->enablePlugins ( 'sfDoctrinePlugin' );

		$this->enablePlugins ( 'sfDoctrineGuardPlugin' );
		
		$this->enablePlugins ( 'psAdminThemePlugin' );

		$this->enablePlugins ( 'sfPreSchoolPlugin' );
		
		$this->enablePlugins ( 'sfFormExtraPlugin' );
	  	
	  	//$this->enablePlugins ( 'sfJQueryUIPlugin' );
	  	
		$this->enablePlugins('sfImageTransformPlugin');
		
    	$this->enablePlugins('sfThumbnailPlugin');
    	
    	$this->enablePlugins('PHPExcelPlugin');
  }
  
}



