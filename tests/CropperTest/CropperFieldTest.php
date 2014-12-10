<?php namespace CropperTest;

use Form;
use FieldList;
use Controller;
use CropperField;
use CropObject;

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

	/**
	 * Test the getRecord method's "clever" form based path
	 */
	public function testCleverGetRecord() {
		$cropObject = new CropObject();
		$cropObject->write();
		$this->setCropObject($cropObject);

		// Create a test form
		$form = $this->createForm();
		$this->assertNotEquals(
			$cropObject,
			$form->getRecord(),
			'Precondition failed: initially, the form should have no record'
		);

		// Load its data, thus triggering Form's internal setRecord
		$form->loadDataFrom($cropObject);
		$this->assertEquals(
			$cropObject,
			$form->getRecord(),
			'Precondition failed: loadDataFrom should set the Form record'
		);

		$field = $this->createField();

		$this->assertNull($field->getRecord());

		$field->setForm($form);
		$this->assertEquals(
			$cropObject,
			$form->getRecord(),
			'Form should provide the record if none is directly specified'
		);
	}

	/**
	 * Mundane setting and getting...
	 */
	public function testSetGetRecord() {
		$cropObject = new CropObject();
		$cropObject->write();
		$this->setCropObject($cropObject);
		$field = $this->createField();
		$field->setRecord($cropObject);
		$this->assertEquals(
			$cropObject,
			$field->getRecord()
		);
	}

}
