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

	public function setSourceImage(Image $image) {
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
	 * @return string
	 */
	protected function createThumbnailFilename() {
		$file = $this->getSourceImage();
		$filename = $file->getFilename();
		$hashBase = $filename;
		$extension = strtolower($file->getExtension());
		do {
			$thumbFile = sprintf('%s/%s/%s-thumbnail.%s',
				BASE_PATH,
				dirname($filename),
				sha1($hashBase),
				$extension
			);
			$hashBase .= uniqid();
		}
		while(file_exists($thumbFile));
		return $thumbFile;
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
