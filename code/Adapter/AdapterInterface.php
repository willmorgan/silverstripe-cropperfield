<?php namespace CropperField\Adapter;

/**
 * Define a way for a file provider to communicate with CropperField
 * An implementor could be a form field, like an UploadField, or something
 * completely different.
 */

interface AdapterInterface {

	/**
	 * @return \FormField
	 */
	public function getFormField();

	/**
	 * @return \File
	 */
	public function getFile();

	/**
	 * @return \Image
	 */
	public function getSourceImage();

	/**
	 * @return string
	 */
	public function getFilename();

	/**
	 * @return string
	 */
	public function getImageFilename();

}
