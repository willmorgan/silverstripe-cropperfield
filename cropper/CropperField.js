(function($) {

	var CropperField = function(field) {
		var _inst = this;
		var target = field.find('.js-cropperfield__target');
		var dataField = field.find('.js-cropperfield__data');
		var cropperHeight;
		var construct = function() {
			var options = JSON.parse(field.attr('data-config'));
			var fieldID = field.attr('data-field-id');
			var preview = field.find('.cropperfield__preview');
			target.cropper({
				multiple: true,
				viewMode: options.viewMode,
				dragMode: options.dragMode,
                aspectRatio: options.aspectRatio,
				modal: options.modal,
				guides: options.guides,
				background: options.background,
				autoCropArea: options.autoCropArea,
				movable: options.movable,
				rotatable: options.rotatable,
				scalable: options.scalable,
				zoomable: options.zoomable,
				zoomOnTouch: options.zoomOnTouch,
				zoomOnWheel: options.zoomOnWheel,
                wheelZoomRatio: options.wheelZoomRatio,
                cropBoxMovable: options.cropBoxMovable,
                cropBoxResizable: options.cropBoxResizable,
                toggleDragModeOnDblclick: options.toggleDragModeOnDblclick,
                minContainerWidth: options.minContainerWidth,
                minContainerHeight: options.minContainerHeight,
                minCanvasWidth: options.minCanvasWidth,
                minCanvasHeight: options.minCanvasHeight,
                minCropBoxWidth: options.minCropBoxWidth,
                minCropBoxHeight: options.minCropBoxHeight,
				preview: preview,
                crop: function(data) {
					dataField.val(JSON.stringify(data));
				},
				built: function() {
					_inst.setCropperHeight(
						field.find('.cropper-container')[0].clientHeight
					);
				}
			});
			target.cropper('disable');
			setupCheckbox();
			CropperField.instances.push(_inst);
			return this;
		};
		var setupCheckbox = function() {
			var toggleField = field.find('.js-cropperfield__toggle');
			toggleField.on('change click', onCheckboxToggle);
			onCheckboxToggle.call(toggleField);
		};
		var onCheckboxToggle = function() {
			var method = $(this).is(':checked') ? 'enable' : 'disable';
			target.cropper(method);
			_inst[method]();
		};
		this.id = function() {
			return field.attr('data-field-id');
		};
		this.setCropperHeight = function(height) {
			cropperHeight = height;
			return this;
		};
		this.getCropperHeight = function() {
			return cropperHeight || 600;
		};
		this.enable = function() {
			var inClass = 'cropperfield--cropping';
			var outClass = 'cropperfield--inactive';
			field.removeClass(outClass).addClass(inClass);
			field.find('.cropperfield__cropper-container').attr(
				'style',
				'height: ' + _inst.getCropperHeight() + 'px'
			);
			return this;
		};
		this.disable = function() {
			var inClass = 'cropperfield--inactive';
			var outClass = 'cropperfield--cropping';
			field.removeClass(outClass).addClass(inClass);
			field.find('.cropperfield__cropper-container').removeAttr('style');
			return this;
		};
		this.destroy = function() {
			_inst.disable();
			target.cropper('destroy');
			return this;
		};
		construct();
		return this;
	};

	CropperField.instances = [];

	CropperField.destroy = function() {
		CropperField.instances.forEach(function(instance, key, collection) {
			instance.destroy();
			delete collection[key];
		});
	};

	$(function() {

		$('.js-cropperfield').each(function() {
			new CropperField($(this));
		});

		// Set the init method to re-run if the page is saved or pjaxed
		if($.entwine) {
			$.entwine('ss', function($) {
				$('.js-cropperfield').entwine({
					onmatch: function() {
						CropperField.destroy();
						$('.js-cropperfield').each(function() {
							new CropperField($(this));
						});
					}
				});
			});
		}

	});

}(jQuery));
