SilverStripe CropperField
===


Do you need to crop your images for art direction purposes?

Perhaps this is for responsive design, or perhaps you're tired of `CroppedResize`, `PaddedResize`, `Square` and general centre-based resizing functionality in SilverStripe that doesn't quite meet your image cropping needs.

Maybe `CropperField` is for you. Whilst it's called `CropperField`, that's just the user interface. Behind the scenes, a clean and extensible architecture allows you to crop almost anything - external URLs, documents, video frames, etc..

For the field itself, the frontend is powered by [Cropper v0.7.5](https://github.com/fengyuanchen/cropper) by Fengyuan Chen.

**Warning: Under development / alpha. Do not use unless you are prepared to contribute!**

### Installation

0. `composer require willmorgan/silverstripe-cropperfield`
1. Specify a version constraint.

### Screenshot

The field currently looks like this in a CMS setting.

![Screenshot of the field](docs/images/screenshot.png)

### Usage

0. Specify a `$has_one` `Image` relationship on the object that should own the cropped image.
1. Specify an image based `UploadField` whose image you wish to crop.
  * Only `UploadField` is supported right now. Non-images will break. Sorry.
2. Create an `AdapterInterface` implementor to pass into your `CropperField`
  * See [1]; the adapters are generic enough for you to supply anything - as long as there's a `File`
object backing it (regardless of whether it exists).
3. Happy cropping.

#### Example

In this example, a configuration like this has been set up:

```php
$has_one = array(
	'MyBigPhoto' => 'Image',
	'MyArtDirectionCrop' => 'Image',
);
```

Inside a `getCMSFields` call, or similar:

```php
$uploadField = new UploadField('MyBigPhoto', 'Big Photo Uploader');
$cropperField = new CropperField\CropperField(
	'MyArtDirectionCrop',
	'Cropped Image',
	new CropperField\Adapter\UploadField(
		$uploadField
	)
);
```

#### Customisation

* Templates: you can easily customise the template used - just override `CropperField.ss`
* You can write your own croppers (say, for `Imagick`).
  1. Implement the `CropperInterface`
  2. Either set it on a per-instance basis using `CropperField->setCropper()`
  3. Set it in YML, overriding `CropperFactory.cropper`. The factory uses `Injector` for DI.

### Compatibility

* While Cropper is able to support browsers as old as IE8, the `CropperField` CMS UI has been tested only in Chrome.

### Licensing
* SilverStripe CropperField is released under the BSD License; however, please note:
* [Cropper v0.7.5](https://github.com/fengyuanchen/cropper) is released under the MIT license.
