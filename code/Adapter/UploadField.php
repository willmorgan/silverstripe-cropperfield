<?php namespace CropperField\Adapter;

/**
 * UploadField adapter
 */

class UploadField extends GenericField {

	/**
	 * @return \File
	 */
	public function getFile() {
		return $this->getFormField()->getItems()->first();
	}

}
