<?php

class CropObject extends DataObject implements TestOnly {

	private static $has_one = array(
		'MainImage' => 'Image',
		'ThumbnailImage' => 'Image',
	);

	/**
	 * @return FieldList
	 */
	public function getCMSFields() {
		$fields = new FieldList(array(
			$mainImageField = new UploadField('MainImage'),
			new CropperField\CropperField(
				'ThumbnailImage',
				null,
				new CropperField\Adapter\UploadField($mainImageField)
			),
		));
		return $fields;
	}

}
