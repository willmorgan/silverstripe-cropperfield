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
	protected $maxWidth;

	/**
	 * @var int
	 */
	protected $maxHeight;

	/**
	 * @var float
	 */
	protected $aspectRatio;

	public function setSourceImage(\Image $image) {
		$filename = $image->getFullPath();
		if(!(is_file($filename) && is_readable($filename))) {
			throw new GenericCropper_InvalidFileException($filename);
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
	public function setMaxWidth($width) {
		$this->maxWidth = $width;
		return $this;
	}

	public function getMaxWidth() {
		return $this->maxWidth;
	}

	/**
	 * @param int $height height of the target cropped image
	 */
	public function setMaxHeight($height) {
		$this->maxHeight = $height;
		return $this;
	}

	public function getMaxHeight() {
		return $this->maxHeight;
	}

	/**
	 * @param float $ratio aspect ratio of the target cropped image
	 */
	public function setAspectRatio($ratio) {
		if($ratio !== null && ($ratio <= 0 || !is_numeric($ratio))) {
			throw new \InvalidArgumentException('Ratio must be numeric or a float, and over 0');
		}
		else if($ratio !== null) {
			$ratio = (float) $ratio;
		}
		$this->aspectRatio = $ratio;
		return $this;
	}

	/**
	 * Look at the aspect ratio. If there's none, then set it from the implied
	 * crop width/height.
	 */
	public function getAspectRatio() {
		if(isset($this->aspectRatio)) {
			return $this->aspectRatio;
		}
		$impliedAspect = $this->getCropWidth() / $this->getCropHeight();
		return $impliedAspect;
	}

	/**
	 * Ensure the aspect ratio is respected regardless of any dodgy data.
	 * The aspect ratio is otherwise
	 */
	public function getCropDimensions() {
		$x = $this->getCropX();
		$y = $this->getCropY();
		$cropWidth = $this->getCropWidth();
		$cropHeight = $this->getCropHeight();
		$width = $cropWidth;
		$height = $cropHeight;

		// Normalise the width/height with respect to the aspect ratio
		$maxWidth = $this->getMaxWidth();
		$maxHeight = $this->getMaxHeight();

		$aspectRatio = $this->getAspectRatio();


		if($width < 1 || $height < 1) {
			throw new \InvalidArgumentException('Values must be over 1');
		}

		// Fix the aspect ratio if the crop is wrong
		// This must happen first so invalid values can be compensated for
		if($width / $height != $aspectRatio) {
			$height = $width / $aspectRatio;
		}

		if($width > $maxWidth) {
			$scaleDownRatio = $cropWidth / $maxWidth;
			$height = $height / $scaleDownRatio;
			$width = $maxWidth;
		}

		if($height > $maxHeight) {
			$scaleDownRatio = $cropHeight / $maxHeight;
			$width = $width / $scaleDownRatio;
			$height = $maxHeight;
		}

		return array(
			// src_x, src_y
			'x' => $x,
			'y' => $y,
			// dst_w, dst_h
			'width' => ceil($width),
			'height' => ceil($height),
			// src_w, src_h
			'crop_width' => $cropWidth,
			'crop_height' => $cropHeight,
		);
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
