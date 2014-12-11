<?php namespace CropperField\Cropper;

/**
 * GD Cropper
 */

use Image;

class GD extends GenericCropper {

	public function crop(Image $image) {
		$file = $this->getSourceImage();
		$filename = $file->getFilename();
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
	 * @param string $filename
	 * @param string $extension
	 * @return resource
	 */
	public function loadExistingImage($filename, $extension) {
		$filename = BASE_PATH . DIRECTORY_SEPARATOR . $filename;
		if(!is_readable($filename)) {
			throw new GD_ImageReadException($filename);
		}
		switch($extension) {
			case 'jpeg':
			case 'jpg':
				return imagecreatefromjpeg($filename);
			case 'png':
				return imagecreatefrompng($filename);
			case 'gif':
				return imagecreatefromgif($filename);
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
