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
		Config::nest();
		// Stop updating the file system, it's not helpful and causes issues with
		// the automatic fixture image loader
		Config::inst()->update('File', 'update_filesystem', false);
		parent::setUpOnce();
	}

	public function tearDownOnce() {
		parent::tearDownOnce();
		Config::unnest();
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
			$this->getCropObject()->getCMSFields(),
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

	/**
	 * Like objFromFixture but does some CropObject sugar for us
	 * @return \CropObject
	 */
	protected function cropObjFromFixture($fixtureName) {
		$cropObject = $this->objFromFixture('CropObject', $fixtureName);
		$this->setCropObject($cropObject);
		return $cropObject;
	}

	/**
	 * @return array
	 */
	protected function sampleCropData() {
		return array(
			'x' => 30,
			'y' => 60,
			'width' => 100,
			'height' => 200,
		);
	}

	/**
	 * The name of the test class, relative to the asset directory.
	 * @return string
	 */
	protected function getClassTestDir() {
		$refClass = new \ReflectionClass(get_class($this));
		return $refClass->getShortName();
	}

	/**
	 * The fully qualified test asset directory.
	 * @return string
	 */
	protected function getTestAssetDir() {
		return ASSETS_DIR . DIRECTORY_SEPARATOR . $this->getClassTestDir();
	}

	/**
	 * Copy the file assets from the fixture distribution folder into assets/.
	 */
	protected function prepareFileAssets($fixtureClass = 'Image') {
		$fileIDs = $this->allFixtureIDs($fixtureClass);
		$sourceDir = CROPPERFIELD_PATH . '/tests/TestAssets';
		$absPath = $this->getTestAssetDir();
		$classTestDir = $this->getClassTestDir();
		if(!file_exists($absPath)) {
			mkdir($absPath);
		}
		foreach($fileIDs as $fileID) {
			$file = $fixtureClass::get()->byId($fileID);
			if($file->Name == 'BROKENIMAGE') {
				continue;
			}
			$target = ASSETS_PATH.'/'.$classTestDir.'/'.$file->Filename;
			$source = $sourceDir.'/'.$file->Filename;
			if(!copy($source, $target)) {
				throw new \LogicException(
					'Could not copy fixture file'
				);
			}
			$file->Filename = ASSETS_DIR.'/'.$classTestDir.'/'.$file->Filename;
			$file->write();
		}
	}

	/**
	 * Clean up the directory afterwards
	 */
	protected function cleanFileAssets($fixtureClass = 'Image') {
		$fileIDs = $this->allFixtureIDs($fixtureClass);
		foreach($fileIDs as $fileID) {
			$file = $fixtureClass::get()->byId($fileID);
			if(!$file || $file->Name == 'BROKENIMAGE') {
				continue;
			}
			if(!unlink($file->Filename)) {
				throw new \LogicException(
					'Could not remove fixture file'
				);
			}
		}
		static::rm_rf($this->getTestAssetDir());
	}

	/**
	 * PHP.net comments are the best
	 */
	private static function rm_rf($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}


}
