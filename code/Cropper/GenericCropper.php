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
		if($ratio <= 0 || !(is_numeric($ratio) || $ratio === null)) {
			throw new \InvalidArgumentException;
		}
		$this->aspectRatio = $ratio * 1;
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
		if($impliedAspect <= 0) {
			throw new \InvalidArgumentException;
		}
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

		// Normalise the width/height with respect to the aspect ratio
		$aspect = $this->getAspectRatio();
		$width = $this->getMaxWidth();
		$height = $this->getMaxHeight();
		if($width < 1 || $height < 1) {
			throw new \InvalidArgumentException;
		}
		if($width / $height != $aspect) {
			$height = ceil($width / $aspect);
			$cropHeight = ceil($cropWidth / $aspect);
		}

		// Todo: adjust X/Y and width/height if out of source image bounds

		return array(
			// src_x, src_y
			'x' => $x,
			'y' => $y,
			// dst_w, dst_h
			'width' => $width,
			'height' => $height,
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
