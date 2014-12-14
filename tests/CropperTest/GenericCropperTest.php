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
	 * @dataProvider downscaleProvider
	 */
	public function testDimensionDownscale(
		$cropWidth,
		$cropHeight,
		$maxWidth,
		$maxHeight,
		$expectedWidth,
		$expectedHeight
	) {
		$cropper = $this->createCropper($cropWidth, $cropHeight, $maxWidth, $maxHeight);

		$results = $cropper->getCropDimensions();

		$this->assertEquals($expectedWidth, $results['width']);
		$this->assertEquals($expectedHeight, $results['height']);
	}

	/**
	 * @return array
	 */
	public function downscaleProvider() {
		return array(
			array(
				500, 300, 400, 300, 400, 240,
			),
			array(
				300, 500, 300, 400, 240, 400,
			),
		);
	}

	/**
	 * Decrease the height according to the aspect ratio
	 */
	public function testAspectSwitching() {
		$cropper = $this->createCropper(400, 300);
		$cropper->setAspectRatio(16/9);

		$results = $cropper->getCropDimensions();

		$this->assertEquals(225, $results['height']);
		$this->assertEquals(300, $results['crop_height']);
	}

	/**
	 * Make me a cropper with some sensible defaults
	 */
	protected function createCropper(
		$cropWidth,
		$cropHeight,
		$maxWidth = null,
		$maxHeight = null,
		$x = 0,
		$y = 0
	) {

		$maxWidth = $maxWidth ?: $cropWidth;
		$maxHeight = $maxHeight ?: $cropHeight;

		$cropper = new MockCropper();
		$cropper->setCropX($x);
		$cropper->setCropY($y);
		$cropper->setCropWidth($cropWidth);
		$cropper->setCropHeight($cropHeight);
		$cropper->setMaxWidth($maxWidth);
		$cropper->setMaxHeight($maxHeight);

		return $cropper;
	}

}
