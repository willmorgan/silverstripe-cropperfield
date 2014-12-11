<?php namespace CropperTest;

/**
 * GenericCropperTest
 * Test some of the concrete methods of the GenericCropper
 */

use CropperTest\Mock\GenericCropper as MockCropper;

class GenericCropperTest extends TestCase {

	public function testDimensionReturn() {
		$cropper = $this->createCropper(400, 300);

		$results = $cropper->getCropDimensions();

		$this->assertInternalType('array', $results);
		$this->assertEquals(400, $results['width']);
		$this->assertEquals(400, $results['crop_width']);
		$this->assertEquals(300, $results['height']);
		$this->assertEquals(300, $results['crop_height']);
	}

	/**
	 * Decrease the height according to the aspect ratio
	 */
	public function testAspectSwitchingMinimiseHeight() {
		$cropper = $this->createCropper(400, 300);
		$cropper->setAspectRatio(16/9);

		$results = $cropper->getCropDimensions();

		$this->assertEquals(225, $results['height']);
		$this->assertEquals(225, $results['crop_height']);
	}

	/**
	 * Increase the height according to the aspect ratio
	 */
	public function testAspectSwitchingOnWidth() {
		$cropper = $this->createCropper(800, 450, 400, 225);
		$cropper->setAspectRatio(4/3);

		$results = $cropper->getCropDimensions();

		$this->assertEquals(400, $results['width']);
		$this->assertEquals(300, $results['height']);
		$this->assertEquals(600, $results['crop_height']);
	}

	/**
	 * Make me a cropper with some sensible defaults
	 */
	protected function createCropper(
		$width,
		$height,
		$targetWidth = null,
		$targetHeight = null,
		$x = 0,
		$y = 0
	) {

		$targetWidth = $targetWidth ?: $width;
		$targetHeight = $targetHeight ?: $height;

		$cropper = new MockCropper();
		$cropper->setCropX($x);
		$cropper->setCropY($y);
		$cropper->setCropWidth($width);
		$cropper->setCropHeight($height);
		$cropper->setTargetWidth($targetWidth);
		$cropper->setTargetHeight($targetHeight);

		return $cropper;
	}

}
