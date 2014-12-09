<?php namespace CropperField\Service;

/**
 * CropperFactory
 * Really basic factory that goes and creates a Cropper instance with Injector.
 * By default this is the GD cropper.
 * The interface validation happens on CropperField's setter.
 */

class CropperFactory implements \SilverStripe\Framework\Injector\Factory {

	/**
	 * @return \CropperField\Cropper\CropperInterface
	 */
	public function create($service, array $params = array()) {
		$class = \Config::inst()->get('CropperFactory', 'cropper');
		return new $class();
	}

}
