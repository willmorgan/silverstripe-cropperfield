<?php namespace CropperField\Adapter;

/**
 * UploadField adapter
 */

class UploadField extends GenericField {

	/**
	 * @return \File
	 */
	public function getFile() {
		$loadedFile = $this->getFormField()->getItems()->first();
		if(!$loadedFile) {
			return new \Image();
		}
		return $loadedFile;
	}

	/**
	 * @return \Image
	 */
	public function getSourceImage() {
		$image = $this->getFile();
		if(!$image instanceof \Image) {
			throw new UploadField_BadFileTypeException;
		}
		return $image;
	}

}

class UploadField_BadFileTypeException extends \InvalidArgumentException { }
