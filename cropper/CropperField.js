(function($) {

	var CropperField = {
		init: function() {
			var field = $(this);
			var image = field.find('.js-cropperfield__target');
			var dataField = field.find('.js-cropperfield__data');
			var fieldID = field.attr('data-field-id');
			image.cropper({
				multiple: true,
				autoCropArea: .8,
				done: function(data) {
					dataField.val(JSON.stringify(data));
				}
			});
		}
	};

	$('.js-cropperfield').each(CropperField.init);

	// Set the init method to re-run if the page is saved or pjaxed
	if($.entwine) {
		$.entwine('ss', function($) {
			$('.js-cropperfield').entwine({
				onmatch: function() {
					$('.js-cropperfield').each(CropperField.init);
				}
			});
		});
	}

}(jQuery));
