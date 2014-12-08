(function($) {
	$('.js-cropperfield').each(function() {
		var field = $(this);
		var image = field.find('.js-cropperfield__target');
		var dataField = field.find('.js-cropperfield__data');
		var fieldID = field.attr('data-field-id');
		image.cropper({
			multiple: true,
			autoCropArea: 1,
			done: function(data) {
				dataField.val(JSON.stringify(data));
			}
		});

	});
}(jQuery));
