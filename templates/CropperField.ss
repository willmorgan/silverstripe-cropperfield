<div class="container cropperfield js-cropperfield" $AttributesHTML>
	<% if $Adapter.File || $ExistingThumbnail %>
	<div class="cropperfield__thumbarea">
		<div class="cropperfield__existing">
			<% if $ExistingThumbnail %>
			$ExistingThumbnail
			<% else %>
			<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="<%t CropperField.LABELS.ExistingNone "No existing crop" %>" />
			<% end_if %>
			<span class="cropperfield__label cropperfield__label--existing">
				<%t CropperField.LABELS.Existing "Existing Crop" %>
			</span>
		</div>
		<div class="cropperfield__preview-container">
			<div class="cropperfield__preview"></div>
			<span class="cropperfield__label  cropperfield__label--preview">
				<%t CropperField.LABELS.Preview "Cropping Preview" %>
			</span>
		</div>
		<div class="cropperfield__controls">
			<label>
				<input type="checkbox" class="js-cropperfield__toggle" name="{$Name}[Enabled]"<% if not $ExistingThumbnail %> checked="checked"<% end_if %> />
				<%t CropperField.CONTROLS.ToggleEdit "Edit this cropped image" %>
			</label>
		</div>
	</div>
	<div class="cropperfield__cropper-container">
		<div class="cropperfield__cropper">
			<% with $Adapter %>
				<img src="$ImageFilename" class="js-cropperfield__target" />
			<% end_with %>
		</div>
	</div>
	<input type="hidden" class="js-cropperfield__data" name="{$Name}[Data]" />
	<% else %>
	<%t CropperField.MESSAGES.NoSource "Please first upload a file and save it to this record" %>
	<% end_if %>
</div>
