<?php namespace CropperField\Cropper;

/**
 * GenericCropper
 * Implements most of the CropperInterface and provides some helper methods
 */

abstract class GenericCropper implements CropperInterface {

	/**
	 * @var Image
	 */
	protected $source;

	/**
	 * @var int
	 */
	protected $x;

	/**
	 * @var int
	 */
	protected $y;

	/**
	 * @var int
	 */
	protected $cropWidth;

	/**
	 * @var int
	 */
	protected $cropHeight;

	/**
	 * @var int
	 */
	protected $targetWidth;

	/**
	 * @var int
	 */
	protected $targetHeight;

	/**
	 * @var float
	 */
	protected $aspectRatio;

	public function setSourceImage(\Image $image) {
		$filename = $image->getFilename();
		if(!(is_file($filename) && is_readable($filename))) {
			throw new GenericCropper_InvalidFileException;
		}
		$this->source = $image;
		return $this;
	}

	public function getSourceImage() {
		return $this->source;
	}

	/**
	 * @param array
	 */
	public function setCropData(array $data) {
		$this->setCropX($data['x']);
		$this->setCropY($data['y']);
		$this->setCropWidth($data['width']);
		$this->setCropHeight($data['height']);
		return $this;
	}

	/**
	 * @param int $x X coordinate of the crop origin (anchored top left)
	 */
	public function setCropX($x) {
		$this->x = $x;
		return $this;
	}

	public function getCropX() {
		return $this->x;
	}

	/**
	 * @param int $y Y coordinate of the crop origin (anchored top left)
	 */
	public function setCropY($y) {
		$this->y = $y;
		return $this;
	}

	public function getCropY() {
		return $this->y;
	}

	/**
	 * @param int $width width of the user drawn crop
	 */
	public function setCropWidth($width) {
		$this->cropWidth = $width;
		return $this;
	}

	public function getCropWidth() {
		return $this->cropWidth;
	}

	/**
	 * @param int $height height of the user drawn crop
	 */
	public function setCropHeight($height) {
		$this->cropHeight = $height;
		return $this;
	}

	public function getCropHeight() {
		return $this->cropHeight;
	}

	/**
	 * @param int $width width of the target cropped image
	 */
	public function setTargetWidth($width) {
		$this->targetWidth = $width;
		return $this;
	}

	public function getTargetWidth() {
		return $this->targetWidth;
	}

	/**
	 * @param int $height height of the target cropped image
	 */
	public function setTargetHeight($height) {
		$this->targetHeight = $height;
		return $this;
	}

	public function getTargetHeight() {
		return $this->targetHeight;
	}

	/**
	 * @param float $ratio aspect ratio of the target cropped image
	 */
	public function setAspectRatio($ratio) {
		$this->aspectRatio = $ratio;
		return $this;
	}

	public function getAspectRatio() {
		return $this->aspectRatio;
	}

	/**
	 * @return string
	 */
	protected function createThumbnailFilename() {
		$file = $this->getSourceImage();
		$filename = $file->getFilename();
		$hashBase = $filename;
		$extension = strtolower($file->getExtension());
		$cropDirectory = sprintf('%s/%s/%s',
			BASE_PATH,
			dirname($filename),
			'cropped'
		);
		$ssFolderpath = str_replace(ASSETS_PATH, '', $cropDirectory);
		$folder = \Folder::find_or_make($ssFolderpath);
		do {
			$cropFile = sprintf('%s/%s.%s',
				$cropDirectory,
				sha1($hashBase),
				$extension
			);
			$hashBase .= uniqid();
		}
		while(file_exists($cropFile));
		return $cropFile;
	}

	/**
	 * Convert an absolute path to one relative to the base path
	 * @return string
	 */
	protected function normaliseFilename($absolutePath) {
		$regex = sprintf(
			'#^%s#',
			addslashes(BASE_PATH) . DIRECTORY_SEPARATOR
		);
		return preg_replace($regex, '', $absolutePath);
	}

}

class GenericCropper_InvalidFileException extends \InvalidArgumentException { }
