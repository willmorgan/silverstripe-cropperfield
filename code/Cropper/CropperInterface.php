<?php namespace CropperField\Cropper;

/**
 * CropperInterface
 * The existing GD and Imagick backends aren't enough to specify the anchor
 * points of the image, and also cannot transpose arbitrary dimensions into
 * cleanly generated thumbnails.
 * Implementors of this interface could use GD, Imagick, or even a web service
 * to do the job.
 */

use Image;

interface CropperInterface {

	/**
	 * The main image we will be cropping + resizing
	 * @param Image $source
	 */
	public function setSourceImage(Image $source);

	/**
	 * Set x, y, width, height attributes from the JSON array
	 * @param array
	 */
	public function setCropData(array $cropData);

	/**
	 * @param int $x X coordinate of the crop origin (anchored top left)
	 */
	public function setCropX($x);

	/**
	 * @param int $y Y coordinate of the crop origin (anchored top left)
	 */
	public function setCropY($y);

	/**
	 * @param int $width width of the user drawn crop
	 */
	public function setCropWidth($width);

	/**
	 * @param int $height height of the user drawn crop
	 */
	public function setCropHeight($height);

	/**
	 * @param int $width max width of a generated image; if dimension exceeds, must downscale
	 */
	public function setMaxWidth($width);

	/**
	 * @param int $height max height of a generated image; if dimension exceeds, must downscale
	 */
	public function setMaxHeight($height);

	/**
	 * @param float $ratio the ratio (width:height) of the target cropped image
	 */
	public function setAspectRatio($ratio);

	/**
	 * Perform the cropping and return the $out file.
	 * @param Image $out
	 * @return Image
	 */
	public function crop(Image $out);

}
