<?php namespace CropperTest;

use CropperField\CropperField;

/**
 * TestCase
 * An abstract base from which Cropper tests can extend with some useful
 * helper functionality.
 */
abstract class TestCase extends \SapphireTest {

	/**
	 * @return \CropperField\CropperField
	 */
	protected function createField() {
		return $this->getCropObject()->getCMSFields()->fieldByName('ThumbnailImage');
	}

	/**
	 * @return \CropObject
	 */
	protected function getCropObject() {
		return singleton('CropObject');
	}

}
