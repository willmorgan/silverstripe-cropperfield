<?php namespace CropperField\Cropper;

/**
 * GD Cropper
 */

use Image;

class GD extends GenericCropper {

	public function crop(Image $image) {
		$file = $this->getSourceImage();
		$filename = $file->getFullPath();
		$extension = strtolower($file->getExtension());
		$existing = $this->loadExistingImage($filename, $extension);
		$dimensions = $this->getCropDimensions();
		$new = imagecreatetruecolor(
			$dimensions['width'],
			$dimensions['height']
		);
		if(!$new) {
			throw new GD_ResourceException();
		}
		$resampleResult = imagecopyresampled(
			$new,
			$existing,
			0,
			0,
			$dimensions['x'],
			$dimensions['y'],
			$dimensions['width'],
			$dimensions['height'],
			$dimensions['crop_width'],
			$dimensions['crop_height']
		);
		if(!$resampleResult) {
			throw new GD_CropException();
		}
		$thumbFile = $this->saveCanvas($new, $extension);
		$image->Filename = $thumbFile;
		$image->write();
		return $image;
	}

	/**
	 * @param string $filepath
	 * @param string $extension
	 * @return resource
	 */
	public function loadExistingImage($filepath, $extension) {
		if(!is_readable($filepath)) {
			throw new GD_ImageReadException($filepath);
		}
		switch($extension) {
			case 'jpeg':
			case 'jpg':
				return imagecreatefromjpeg($filepath);
			case 'png':
				return imagecreatefrompng($filepath);
			case 'gif':
				return imagecreatefromgif($filepath);
			default:
				throw new GD_InvalidFormatException();
		}
	}

	/**
	 * @param resource $canvas
	 * @param string $extension
	 */
	public function saveCanvas($canvas, $extension) {
		$filename = $this->createThumbnailFilename();
		switch($extension) {
			case 'jpeg':
			case 'jpg':
				$response = imagejpeg($canvas, $filename, 80);
			break;
			case 'png':
				$response = imagepng($canvas, $filename, 8);
			break;
			case 'gif':
				$response = imagegif($canvas, $filename);
			break;
			default:
				throw new GD_InvalidFormatException();
		}
		if(!$response) {
			throw new GD_ImageWriteException();
		}
		return $this->normaliseFilename($filename);
	}

}

class GD_InvalidFormatException extends \InvalidArgumentException { }
class GD_ImageWriteException extends \ErrorException { }
class GD_ResourceException extends \ErrorException { }
class GD_ImageReadException extends \ErrorException { }
class GD_CropException extends \ErrorException { }
