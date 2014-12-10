<?php namespace CropperTest;

use Form;
use FieldList;
use Controller;
use CropperField;

class CropperFieldTest extends TestCase {

	/**
	 * The field must set its adapter's form field
	 */
	public function testSetForm() {
		$field = $this->createField();
		$form = new Form(
			new Controller(),
			'Test',
			new FieldList(),
			new FieldList()
		);
		$field->setForm($form);
		$this->assertEquals(
			$form,
			$field->getAdapter()->getFormField()->getForm()
		);
	}

	/**
	 * Test the Injector config to ensure a Cropper is always available
	 */
	public function testGetInjectCropper() {
		$field = $this->createField();
		$this->assertInstanceOf(
			'CropperField\Cropper\CropperInterface',
			$field->getCropper()
		);
	}

	/**
	 * Test that canCrop returns true at the right time.
	 * This is currently only when the field's "Enabled" value is set
	 */
	public function testCanCrop() {
		$field = $this->createField();
		$this->assertFalse($field->canCrop());
		$field->setValue(array(
			'Enabled' => 'on',
		));
		$this->assertTrue($field->canCrop());
		$field->setValue(array());
		$this->assertFalse($field->canCrop());
	}

	/**
	 * Test that getCropData returns valid JSON
	 */
	public function testGetCropData() {
		$field = $this->createField();
		try {
			$field->getCropData();
			$this->fail('Expected a NoCropDataException');
		}
		catch(CropperField\CropperField_NoCropDataException $e) {
		}
		$field->setValue(array(
			'Data' => 'this is not valid JSON, m8',
		));
		try {
			$field->getCropData();
			$this->fail('Expected an InvalidCropDataException');
		}
		catch(CropperField\CropperField_InvalidCropDataException $e) {
		}
		$sampleCropData = array(
			'x' => 30,
			'y' => 60,
			'width' => 100,
			'height' => 200,
		);
		$field->setValue(array(
			'Data' => json_encode($sampleCropData),
		));
		$data = $field->getCropData();
		$this->assertInternalType(
			'array',
			$data
		);
		$this->assertEquals(
			$sampleCropData,
			$data
		);
	}

}
