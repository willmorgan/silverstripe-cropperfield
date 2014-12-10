<?php namespace CropperTest;

/**
 * TestCase
 * An abstract base from which Cropper tests can extend with some useful
 * helper functionality.
 */

use CropperField\CropperField;
use Config;
use i18n;
use Form;
use Controller;
use FieldList;

abstract class TestCase extends \SapphireTest {

	protected $extraDataObjects = array(
		'CropObject',
	);

	protected $cropObject;

	public function setUpOnce() {
		i18n::set_locale('en_GB');
		parent::setUpOnce();
	}

	public function setUp() {
		parent::setUp();
		$this->setCropObject();
	}

	/**
	 * @return \CropperField\CropperField
	 */
	protected function createField() {
		return $this->getCropObject()->getCMSFields()->fieldByName('ThumbnailImage');
	}

	/**
	 * @return Form
	 */
	protected function createForm() {
		return new Form(
			new Controller(),
			'TestForm',
			new FieldList(),
			new FieldList()
		);
	}

	/**
	 * @param \CropObject $object (or null to clear)
	 */
	protected function setCropObject(\CropObject $object = null) {
		$this->cropObject = $object;
		return $this;
	}

	/**
	 * @return \CropObject
	 */
	protected function getCropObject() {
		return $this->cropObject ?: singleton('CropObject');
	}

}
