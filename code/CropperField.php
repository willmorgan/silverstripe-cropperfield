<?php namespace CropperField;

use CropperField\Cropper\GD as GDCropper;
use CropperField\AdapterInterface;
use DataObjectInterface;
use Requirements;
use FormField;
use Director;
use Image;
use Debug;
use Form;

class CropperField extends FormField {

	protected static $default_options = array(
	    'aspect_ratio' => 1,
	    'min_height' => 128,
	    'max_height' => 128,
	    'min_width' => 128,
	    'max_width' => 128,
	);

	public function __construct(
		$name,
		$title = null,
		AdapterInterface $adapter,
		array $options = array()
	) {
		parent::__construct($name, $title);
		$this->setAdapter($adapter);
		$this->setTemplate('CropperField');
		$this->addExtraClass('stacked');
		$this->setOptions($options);
	}

	/**
	 * @param \Form $form
	 */
	public function setForm($form) {
		$this->getAdapter()->getFormField()->setForm($form);
		return parent::setForm($form);
	}

	/**
	 * @param array $options
	 * @return $this
	 */
	public function setOptions(array $options) {
		$defaults = static::$default_options;
		$this->options = array_merge($defaults, $options);
		return $this;
	}

	/**
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @return string
	 */
	public function getOption($name) {
		return $this->options[$name];
	}

	public function getAdapter() {
		return $this->adapter;
	}

	public function setAdapter(AdapterInterface $adapter) {
		$this->adapter = $adapter;
		return $this;
	}

	public function saveInto(DataObjectInterface $object) {
		$object->setField($this->getName() . 'ID', $this->generateCropped()->ID);
		$object->write();
	}

	/**
	 * @return Image
	 */
	public function generateCropped() {
		$file = $this->getAdapter()->getFile();
		$thumbImage = new Image();
		$thumbImage->ParentID = $file->ParentID;
		$cropper = new GDCropper();
		$cropper->setCropData($this->getCropData());
		$cropper->setSourceImage($file);
		$cropper->setTargetWidth(
			$this->getOption('max_width')
		);
		$cropper->setTargetHeight(
			$this->getOption('max_height')
		);
		$cropper->crop($thumbImage);
		$thumbImage->write();
		return $thumbImage;
	}

	/**
	 * @return array
	 */
	public function getCropData() {
		$value = $this->Value();
		return json_decode($value['Data'], true);
	}

	public function Field($properties = array()) {
		static::require_frontend();
		return parent::Field($properties);
	}

	/**
	 * Pull in the cropper.js requirements. If in dev mode, bring in unminified.
	 * @return void
	 */
	public static function require_frontend() {
		$extension = Director::isLive() ? '.min' : '';
		$cssFile = CROPPERFIELD_PATH . '/cropper/cropper' . $extension . '.css';
		$jsFiles = array(
			CROPPERFIELD_PATH . '/cropper/cropper' . $extension . '.js',
			CROPPERFIELD_PATH . '/cropper/CropperField.js',
		);
		Requirements::css($cssFile);
		Requirements::combine_files('cropperfield-all.js', $jsFiles);
	}

}

function imagecreatefromjpg($file) {
	return imagecreatefromjpeg($file);
}
