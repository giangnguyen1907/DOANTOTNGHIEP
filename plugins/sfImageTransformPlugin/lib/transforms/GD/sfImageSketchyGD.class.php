<?php
/*
 * This file is part of the sfImageTransform package.
 * (c) 2007 Stuart Lowes <stuart.lowes@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * sfImageSketchyGD class.
 *
 * Embosses the image.
 *
 * @package sfImageTransform
 * @subpackage transforms
 * @author Stuart Lowes <stuart.lowes@gmail.com>
 * @version SVN: $Id$
 */
class sfImageSketchyGD extends sfImageTransformAbstract {

	/**
	 * Apply the transform to the sfImage object.
	 *
	 * @param
	 *        	sfImage
	 * @return sfImage
	 */
	protected function transform(sfImage $image) {

		$resource = $image->getAdapter ()
			->getHolder ();

		if (function_exists ( 'imagefilter' )) {
			imagefilter ( $resource, IMG_FILTER_MEAN_REMOVAL );
		} else {
			throw new sfImageTransformException ( sprintf ( 'Cannot perform transform, GD does not support imagefilter ' ) );
		}

		return $image;
	}
}
