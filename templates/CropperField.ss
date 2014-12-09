<div class="container cropperfield js-cropperfield" data-field-id="$Name">
	<div class="cropperfield__thumbarea">
		<div class="cropperfield__existing">
			$ExistingThumbnail
		</div>
		<div class="cropperfield__preview-container">
			<div class="cropperfield__preview"></div>
		</div>
	</div>
	<div class="cropperfield__controls">
		<label>
			<input type="checkbox" class="js-cropperfield__toggle" name="{$Name}[Enabled]" />
			<%t CropperField.CONTROLS.Toggle "Recreate thumbnail" %>
		</label>
	</div>
	<div class="cropperfield__cropper">
		<% with $Adapter %>
			<img src="$ImageFilename" class="js-cropperfield__target" />
		<% end_with %>
	</div>
	<input type="hidden" class="js-cropperfield__data" name="{$Name}[Data]" />
</div>
