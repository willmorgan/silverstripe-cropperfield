<?php namespace CropperTest\Mock;

use CropperField\Cropper\GenericCropper as AbstractCropper;

class GenericCropper extends AbstractCropper implements \TestOnly {

	public function crop(\Image $out) {
		return $out;
	}

}
