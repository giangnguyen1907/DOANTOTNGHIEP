<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * psWidgetFormSelectImage.class.php.
 *
 * @package symfony
 * @subpackage widget
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version SVN: $Id: psWidgetFormSelectImage.class.php 30762 2010-08-25 12:33:33Z fabien $
 */
class psWidgetFormSelectImage extends sfWidgetFormSelect {
	
	protected function getOptionsForSelect($value, $choices) {
		
		$mainAttributes = $this->attributes;
		
		$this->attributes = array ();
		
		if (! is_array ( $value )) {
			$value = array (
					$value 
			);
		}
		
		$value_set = array ();
		
		foreach ( $value as $v ) {
			$value_set [strval ( $v )] = true;
		}
		
		$options = array ();
		
		foreach ( $choices as $key => $option ) {
			$attributes = array (
					'value' => self::escapeOnce ( $key ),
					'imagesrc' => self::escapeOnce ( isset($option ['imagesrc']) ? $option ['imagesrc'] : '')
			);
			if (isset ( $value_set [strval ( $key )] )) {
				$attributes ['selected'] = 'selected';
			}
			
			$options [] = $this->renderContentTag ( 'option', self::escapeOnce (  isset($option ['title']) ? $option ['title'] : ''), $attributes );
		}
		
		$this->attributes = $mainAttributes;
		
		return $options;
	}
}
