<?php namespace CropperTest;

/**
 * GenerationProcessTest
 * Make sure that we can at least generate something. Uses GD.
 */

class GenerationProcessTest extends TestCase {

	protected static $fixture_file = array(
		'../Fixture/GenerationProcessTest.yml'
	);

	/**
	 * If there's no data sent from cropper.js, moan about it.
	 */
	public function testGenerateCroppedWithoutData() {
		$cropObject = $this->cropObjFromFixture('object_1');
		$field = $this->createField();
		$this->setExpectedException(
			'CropperField\CropperField_NoCropDataException'
		);
		$field->generateCropped();
	}

	/**
	 * Test the general cropping process, and quickly sanity check the file.
	 */
	public function testGenerateCropped() {
		$this->prepareFileAssets('Image');
		$cropObject = $this->cropObjFromFixture('object_1');
		$field = $this->createField();
		$field->setValue(array(
			'Enabled' => 1,
			'Data' => json_encode($this->sampleCropData()),
		));
		$image = $field->generateCropped();
		$this->assertInstanceOf('Image', $image);
		$this->assertTrue(
			file_exists($image->Filename),
			'The cropped image was not physically created'
		);
		$options = $field->getOptions();
		list($genWidth, $genHeight) = getimagesize($image->Filename);

		$this->assertEquals(100, $genWidth);
		$this->assertEquals(200, $genHeight);

		$this->cleanFileAssets('Image');
	}

	protected function createField() {
		$form = $this->createForm();
		$form->loadDataFrom($this->getCropObject());
		return $form->Fields()->fieldByName('ThumbnailImage');
	}

}
