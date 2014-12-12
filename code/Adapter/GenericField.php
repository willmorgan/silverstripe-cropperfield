<?php namespace CropperField\Adapter;

/**
 * GenericField
 * An Adapter for generic SilverStripe FormFields to inherit from
 */

use ViewableData;
use FormField;

abstract class GenericField extends ViewableData implements AdapterInterface {

	protected $formField;

	public function __construct(FormField $field) {
		$this->setFormField($field);
		parent::__construct();
	}

	public function setFormField(FormField $field) {
		$this->formField = $field;
		return $this;
	}

	/**
	 * @return \FormField
	 */
	public function getFormField() {
		return $this->formField;
	}

	/**
	 * @return string
	 */
	public function getFilename() {
		return $this->getFile()->getFilename();
	}

	public function getImageFilename() {
		return $this->getSourceImage()->getFilename();
	}

}
